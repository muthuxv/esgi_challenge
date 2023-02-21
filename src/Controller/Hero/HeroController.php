<?php

namespace App\Controller\Hero;

use App\Entity\MissionHistory;
use App\Entity\Mission;
use App\Repository\MissionRepository;
use App\Repository\MissionHistoryRepository;
use App\Repository\HeroRepository;
use App\Form\MissionHistoryType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


class HeroController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $in_progress = $missionRepository->findBy(['hero' => $hero, 'status' => 'in_progress']);
        $in_progress = $in_progress ? $in_progress[0] : null;

        return $this->render('hero/index.html.twig', [
            'hero' => $hero, 'in_progress' => $in_progress
        ]);
    }

    #[Route('/missions/in_progress', name: 'mission_in_progress')]
    public function missionInProgress(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $in_progress = $missionRepository->findBy(['hero' => $hero, 'status' => 'in_progress']);
        $in_progress = $in_progress ? $in_progress[0] : null;

        return $this->render('hero/in_progress.html.twig', [
            'hero' => $hero, 'in_progress' => $in_progress
        ]);
    }

    #[Route('/missions/assigned', name: 'mission_assigned')]
    public function missionAssigned(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $assigned = $missionRepository->findBy(['hero' => $hero, 'status' => 'assigned']);

        return $this->render('hero/assigned.html.twig', [
            'hero' => $hero, 'assigned' => $assigned
        ]);
    }

    #[Route('/missions/completed', name: 'mission_completed')]
    public function missionCompleted(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $completed = $missionRepository->findBy(['hero' => $hero, 'status' => 'completed']);

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
            $this->addFlash('error', 'This mission is not assigned to you.');
            return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
        }

        $mission = $missionRepository->find($id);

        return $this->render('hero/show_mission.html.twig', [
            'hero' => $hero, 'mission' => $mission
        ]);
    }

    //accept mission
    #[Route('/mission/{id}/accept', name: 'accept_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function acceptMission(UserInterface $user, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, HeroRepository $heroRepository, $id): Response
    {
        $hero = $user->getHero();

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'This mission is not assigned to you.');
            return $this->redirectToRoute('hero_mission_assigned', [], Response::HTTP_SEE_OTHER);
        }

        //if not already in progress
        if ($missionRepository->findBy(['hero' => $hero, 'status' => 'in_progress'])) {
            $this->addFlash('error', 'You already have a mission in progress.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already in progress
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'in_progress']) !== null) {
            $this->addFlash('error', 'This mission is already in progress.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'completed']) !== null) {
            $this->addFlash('error', 'This mission is already completed.');
            return $this->redirectToRoute('hero_mission_completed', [], Response::HTTP_SEE_OTHER);
        }

        $mission = $missionRepository->find($id);

        $mission->setStatus('in_progress');
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

        //set all the other missions to pending
        $missions = $missionRepository->findBy(['hero' => $hero, 'status' => 'assigned']);
        foreach ($missions as $mission) {
            $mission->setStatus('pending');
            $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        }

        return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
    }

    //decline mission
    #[Route('/mission/{id}/reject', name: 'reject_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function declineMission(UserInterface $user, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, $id): Response
    {
        $hero = $user->getHero();

        $mission = $missionRepository->find($id);

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'This mission is not assigned to you.');
            return $this->redirectToRoute('hero_mission_assigned', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already in progress
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'in_progress']) !== null) {
            $this->addFlash('error', 'This mission is already in progress.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($missionRepository->findOneBy(['id' => $id, 'status' => 'completed']) !== null) {
            $this->addFlash('error', 'This mission is already completed.');
            return $this->redirectToRoute('hero_mission_completed', [], Response::HTTP_SEE_OTHER);
        }

        $mission->setStatus('pending');
        $mission->setHero(null);
        $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        $mission->setDateEnd(new \DateTimeImmutable(''));

        $missionhistory = new MissionHistory();
        $missionhistory->setMission($mission);
        $missionhistory->setStatus('rejected');
        $missionhistory->setUpdatedAt(new \DateTimeImmutable('now'));
        $missionhistory->setUpdatedBy($user);

        $missionRepository->save($mission, true);
        $missionHistoryRepository->save($missionhistory, true);

        return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
    }

    //update mission
    #[Route('/mission/{id}/update', name: 'update_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function updateMission(Request $request, UserInterface $user, Mission $mission, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, HeroRepository $heroRepository, $id): Response
    {
        $hero = $user->getHero();
        $mission = $missionRepository->find($id);

        //check if this mission is assigned to this hero
        if ($missionRepository->findOneBy(['id' => $id, 'hero' => $hero]) === null) {
            $this->addFlash('error', 'This mission is not assigned to you.');
            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        //check if this mission is already completed
        if ($mission->getStatus() == 'completed') {
            $this->addFlash('error', 'This mission is already completed.');
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

            if ($missionhistory->getStatus() == 'completed') {
                $mission->setDateEnd(new DateTimeImmutable('now'));
                $hero->setIsAvailable(true);
            }

            $missionHistoryRepository->save($missionhistory, true);
            $missionRepository->save($mission, true);
            $heroRepository->save($hero, true);

            return $this->redirectToRoute('hero_mission_in_progress', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hero/update_mission.html.twig', [
            'hero' => $hero,
            'mission' => $mission,
            'form' => $form,
        ]);
    }
}
