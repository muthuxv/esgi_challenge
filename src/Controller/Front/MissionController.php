<?php

namespace App\Controller\Front;

use App\Entity\Mission;
use App\Entity\MissionHistory;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use App\Repository\MissionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

#[Route('/mission', name: 'mission_')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('front/mission/index.html.twig', [
            'missions' => $missionRepository->findMissionsByUserId($user->getId()),
            'user' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionRepository $missionRepository, MissionHistoryRepository $missionHistoryRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $mission = new Mission();
        $missionHistory = new MissionHistory();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setStatus('En attente');
            $mission->setCreatedAt(new DateTimeImmutable('now'));
            $mission->setUpdatedAt(new DateTimeImmutable('now'));
            $mission->setDateEnd(null);
            $mission->setUser($this->getUser());
            $mission->setHero(null);

            $missionHistory->setMission($mission);
            $missionHistory->setStatus('Création');
            $missionHistory->setUpdatedBy($this->getUser());
            $missionHistory->setUpdatedAt(new DateTimeImmutable('now'));

            $missionRepository->save($mission, true);
            $missionHistoryRepository->save($missionHistory, true);

            return $this->redirectToRoute('front_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'user' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Mission $mission, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('front/mission/show.html.twig', [
            'mission' => $mission,
            'user' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    /*
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, MissionRepository $missionRepository): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionRepository->save($mission, true);

            return $this->redirectToRoute('front_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }
    */

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, MissionRepository $missionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $missionRepository->remove($mission, true);
        }

        return $this->redirectToRoute('front_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
