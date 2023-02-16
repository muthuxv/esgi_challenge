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
    public function index(UserInterface $user, ): Response
    {
        $hero = $user->getHero();

        return $this->render('hero/index.html.twig', [
            'hero' => $hero,
        ]);
    }

    #[Route('/missions', name: 'missions')]
    public function missions(UserInterface $user, MissionRepository $missionRepository): Response
    {
        $hero = $user->getHero();

        $completed = $missionRepository->findBy(['hero' => $hero, 'status' => 'completed']);
        $assigned = $missionRepository->findBy(['hero' => $hero, 'status' => 'assigned']);
        $in_progress = $missionRepository->findBy(['hero' => $hero, 'status' => 'in_progress']);

        return $this->render('hero/missions.html.twig', [
            'hero' => $hero, 'completed' => $completed,
            'assigned' => $assigned, 'in_progress' => $in_progress
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
            return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
        }

        //if not already in progress
        if ($missionRepository->findBy(['hero' => $hero, 'status' => 'in_progress'])) {
            $this->addFlash('error', 'You already have a mission in progress.');
            return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
        }

        $mission = $missionRepository->find($id);

        $mission->setStatus('in_progress');
        $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        $mission->setDateEnd(new \DateTimeImmutable(''));

        $missionhistory = new MissionHistory();
        $missionhistory->setMission($mission);
        $missionhistory->setStatus($mission->getStatus());
        $missionhistory->setUpdatedAt(new \DateTimeImmutable('now'));
        $missionhistory->setUpdatedBy($hero->getId());

        $hero->setIsAvailable(false);

        $missionRepository->save($mission, true);
        $missionHistoryRepository->save($missionhistory, true);
        $heroRepository->save($hero, true);

        return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
    }

    //decline mission
    #[Route('/mission/{id}/reject', name: 'reject_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function declineMission(UserInterface $user, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, $id): Response
    {
        $hero = $user->getHero();

        $mission = $missionRepository->find($id);

        $mission->setStatus('pending');
        $mission->setHero(null);
        $mission->setUpdatedAt(new \DateTimeImmutable('now'));
        $mission->setDateEnd(new \DateTimeImmutable(''));

        $missionhistory = new MissionHistory();
        $missionhistory->setMission($mission);
        $missionhistory->setStatus('rejected');
        $missionhistory->setUpdatedAt(new \DateTimeImmutable('now'));
        $missionhistory->setUpdatedBy($hero->getId());

        $missionRepository->save($mission, true);
        $missionHistoryRepository->save($missionhistory, true);

        return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
    }

    //update mission
    #[Route('/mission/{id}/update', name: 'update_mission', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function updateMission(Request $request, UserInterface $user, Mission $mission, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, HeroRepository $heroRepository, $id): Response
    {
        $hero = $user->getHero();
        $mission = $missionRepository->find($id);
        $missionhistory = new MissionHistory();
        $form = $this->createForm(MissionHistoryType::class, $missionhistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $missionhistory->setMission($mission);
            $missionhistory->setUpdatedAt(new DateTimeImmutable('now'));
            $missionhistory->setUpdatedBy($hero->getId());

            $mission->setStatus($missionhistory->getStatus());
            $mission->setUpdatedAt(new DateTimeImmutable('now'));

            if ($missionhistory->getStatus() == 'completed') {
                $mission->setDateEnd(new DateTimeImmutable('now'));
                $hero->setIsAvailable(true);
            }

            $missionHistoryRepository->save($missionhistory, true);
            $missionRepository->save($mission, true);
            $heroRepository->save($hero, true);

            return $this->redirectToRoute('hero_missions', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hero/update_mission.html.twig', [
            'hero' => $hero,
            'mission' => $mission,
            'form' => $form,
        ]);
    }
}
