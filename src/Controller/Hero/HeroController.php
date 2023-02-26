<?php

namespace App\Controller\Hero;

use App\Entity\MissionHistory;
use App\Entity\Mission;
use App\Repository\MissionRepository;
use App\Repository\MissionHistoryRepository;
use App\Repository\HeroRepository;
use App\Repository\UserRepository;
use App\Form\MissionHistoryType;
use App\Form\UpdateAbilitiesType;
use App\Form\UpdateHeroProfileType;
use App\Form\UpdateUserProfile;
use App\Form\UpdateUserType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class HeroController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(UserInterface $user, MissionRepository $missionRepository, HeroRepository $heroRepository): Response
    {
        //check if hero logged in
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        $hero = $user->getHero();

        $in_progress = $missionRepository->findBy(['hero' => $hero, 'status' => 'En cours']);
        $in_progress = $in_progress ? $in_progress[0] : null;

        $count_completed = $heroRepository->getCompletedMissions($hero);

        return $this->render('hero/index.html.twig', [
            'hero' => $hero, 'in_progress' => $in_progress, 'count_completed' => $count_completed
        ]);
    }

    #[Route('/missions/in_progress', name: 'mission_in_progress')]
    public function missionInProgress(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $in_progress = $missionRepository->findBy(['hero' => $hero, 'status' => 'En cours']);
        $in_progress = $in_progress ? $in_progress[0] : null;

        return $this->render('hero/in_progress.html.twig', [
            'hero' => $hero, 'in_progress' => $in_progress
        ]);
    }

    #[Route('/missions/assigned', name: 'mission_assigned')]
    public function missionAssigned(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $assigned = $missionRepository->findBy(['hero' => $hero, 'status' => 'Assigné']);

        return $this->render('hero/assigned.html.twig', [
            'hero' => $hero, 'assigned' => $assigned
        ]);
    }

    #[Route('/missions/completed', name: 'mission_completed')]
    public function missionCompleted(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $completed = $missionRepository->findBy(['hero' => $hero, 'status' => 'Terminé']);

        return $this->render('hero/completed.html.twig', [
            'hero' => $hero, 'completed' => $completed
        ]);
    }

    #[Route('/mission/{id}', name: 'show_mission', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(UserInterface $user, $id, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'Cette mission ne vous est pas assignée.');
            return $this->redirectToRoute('hero_default_index', [], Response::HTTP_SEE_OTHER);
        }

        $mission = $missionRepository->find($id);

        return $this->render('hero/show_mission.html.twig', [
            'hero' => $hero, 'mission' => $mission
        ]);
    }

    //accept mission
    #[Route('/mission/{id}/accept', name: 'accept_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function acceptMission(UserInterface $user, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, HeroRepository $heroRepository, $id, MailerInterface $mailer): Response
    {
        $hero = $user->getHero();

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'Cette mission ne vous est pas assignée.');
            return $this->redirectToRoute('hero_mission_assigned', [], Response::HTTP_SEE_OTHER);
        }

        //if not already in progress
        if ($missionRepository->findBy(['hero' => $hero, 'status' => 'En cours'])) {
            $this->addFlash('error', 'Vous avez déjà une mission en cours.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already in progress
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'En cours']) !== null) {
            $this->addFlash('error', 'Cette mission est déjà en cours.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'Terminé']) !== null) {
            $this->addFlash('error', 'Cette mission est déjà terminée.');
            return $this->redirectToRoute('hero_mission_completed', [], Response::HTTP_SEE_OTHER);
        }

        $mission = $missionRepository->find($id);

        $mission->setStatus('En cours');
        $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        $mission->setDateEnd(new \DateTimeImmutable(''));

        $missionhistory = new MissionHistory();
        $missionhistory->setMission($mission);
        $missionhistory->setStatus($mission->getStatus());
        $missionhistory->setUpdatedAt(new \DateTimeImmutable('now'));
        $missionhistory->setUpdatedBy($user);

        $hero->setIsAvailable(false);

        $missionRepository->save($mission, true);
        $missionHistoryRepository->save($missionhistory, true);
        $heroRepository->save($hero, true);

        //send email to user
        $email = (new TemplatedEmail())
            ->from(new Address('notifications@herocall.fr', 'HeroCall'))
            ->to($mission->getUser()->getEmail())
            ->subject('HeroCall - Mission acceptée')
            ->htmlTemplate('mailer/mission_accepted.html.twig')
            ->context([
                'mission' => $mission,
                'hero' => $hero,
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
    }

    //decline mission
    #[Route('/mission/{id}/reject', name: 'reject_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function declineMission(UserInterface $user, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, $id): Response
    {
        $hero = $user->getHero();

        $mission = $missionRepository->find($id);

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'Cette mission ne vous est pas assignée.');
            return $this->redirectToRoute('hero_mission_assigned', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'Terminé']) !== null) {
            $this->addFlash('error', 'Cette mission est déjà terminée.');
            return $this->redirectToRoute('hero_mission_completed', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already in progress
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'En cours']) !== null) {
            $this->addFlash('error', 'Cette mission est déjà en cours.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        $mission->setStatus('En attente');
        $mission->setHero(null);
        $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        $mission->setDateEnd(new \DateTimeImmutable(''));

        $missionhistory = new MissionHistory();
        $missionhistory->setMission($mission);
        $missionhistory->setStatus('Rejetée');
        $missionhistory->setUpdatedAt(new \DateTimeImmutable('now'));
        $missionhistory->setUpdatedBy($user);

        $missionRepository->save($mission, true);
        $missionHistoryRepository->save($missionhistory, true);

        return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
    }

    //update mission
    #[Route('/mission/{id}/update', name: 'update_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function updateMission(Request $request, UserInterface $user, Mission $mission, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, HeroRepository $heroRepository, $id, MailerInterface $mailer): Response
    {
        $hero = $user->getHero();
        $mission = $missionRepository->find($id);

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'Cette mission ne vous est pas assignée.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($mission->getStatus() == 'Terminé') {
            $this->addFlash('error', 'Cette mission est déjà terminée.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        $missionhistory = new MissionHistory();
        $form = $this->createForm(MissionHistoryType::class, $missionhistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $missionhistory->setMission($mission);
            $missionhistory->setUpdatedAt(new DateTimeImmutable('now'));
            $missionhistory->setUpdatedBy($user);

            $mission->setStatus($missionhistory->getStatus());
            $mission->setUpdatedAt(new DateTimeImmutable('now'));

            if ($missionhistory->getStatus() == 'Terminé') {
                $mission->setDateEnd(new DateTimeImmutable('now'));
                $hero->setIsAvailable(true);
            }

            $missionHistoryRepository->save($missionhistory, true);
            $missionRepository->save($mission, true);
            $heroRepository->save($hero, true);

            //update rank
            $heroRepository->updateRank($hero);

            //send email to user
            $email = (new TemplatedEmail())
                ->from(new Address('notifications@herocall.fr', 'HeroCall'))
                ->to($mission->getUser()->getEmail())
                ->subject('HeroCall - Mission mise à jour')
                ->htmlTemplate('mailer/mission_updated.html.twig')
                ->context([
                    'mission' => $mission,
                    'hero' => $hero,
                ]);
            
            $mailer->send($email);

            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hero/update_mission.html.twig', [
            'hero' => $hero,
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    //edit profile
    #[Route('/profile', name: 'edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserInterface $user, HeroRepository $heroRepository, UserRepository $userRepository, Filesystem $filesystem, UserPasswordHasherInterface $passwordHasher): Response
    {

        $hero = $user->getHero();

        $heroForm = $this->createForm(UpdateHeroProfileType::class, $hero);
        $heroForm->handleRequest($request);

        $abilitiesForm = $this->createForm(UpdateAbilitiesType::class, $hero);
        $abilitiesForm->handleRequest($request);

        $userForm = $this->createForm(UpdateUserProfile::class, $user);
        $userForm->handleRequest($request);


        if ($heroForm->isSubmitted() && $heroForm->isValid()) {
            $avatar= $heroForm['avatar']->getData();
            if ($avatar) {
                $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/avatar/' . $hero->getAvatar());
                $originalAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $newAvatar = $originalAvatar.'-'.uniqid().'.'.$avatar->guessExtension();
    
                try {
                    $avatar->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/avatar',
                        $newAvatar
                    );
                } catch (FileException $e) {
                    // ... handle exception if something goes wrong
                }
    
                $hero->setAvatar($newAvatar);
            }
            
            $heroRepository->save($hero, true);
            $this->addFlash('success', 'Votre profil héro a bien été mis à jour.');
            return $this->redirectToRoute('hero_edit_profile', [], Response::HTTP_SEE_OTHER);
        }

        if ($abilitiesForm->isSubmitted() && $abilitiesForm->isValid()) {
            $heroRepository->save($hero, true);
            $this->addFlash('success', 'Vos pouvoirs ont bien été mises à jour.');
            return $this->redirectToRoute('hero_edit_profile', [], Response::HTTP_SEE_OTHER);
        }

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            if ($userForm->get('plainPassword')->getData() != '') {
                // Encode(hash) the plain password, and set it.
                $encodedPassword = $passwordHasher->hashPassword(
                    $user,
                    $userForm->get('plainPassword')->getData()
                );

                $user->setPassword($encodedPassword);
            }

            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $userRepository->save($user, true);
            $this->addFlash('success', 'Votre profil a bien été mis à jour.');
            return $this->redirectToRoute('hero_edit_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hero/profile.html.twig', [
            'hero' => $hero,
            'userForm' => $userForm,
            'heroForm' => $heroForm,
            'abilitiesForm' => $abilitiesForm,
        ]);
    }
}
