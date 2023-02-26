<?php

namespace App\Controller\Back;

use App\Entity\MissionHistory;
use App\Form\MissionHistoryType;
use App\Repository\MissionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/mission-history',  name: 'mission_history_')]
class MissionHistoryController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionHistoryRepository $missionHistoryRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/mission_history/index.html.twig', [
            'mission_histories' => $missionHistoryRepository->findMissionHistoryWithUser(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    /*
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionHistoryRepository $missionHistoryRepository): Response
    {
        $missionHistory = new MissionHistory();
        $form = $this->createForm(MissionHistoryType::class, $missionHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionHistoryRepository->save($missionHistory, true);

            return $this->redirectToRoute('back_mission_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission_history/new.html.twig', [
            'mission_history' => $missionHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MissionHistory $missionHistory): Response
    {
        return $this->render('back/mission_history/show.html.twig', [
            'mission_history' => $missionHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MissionHistory $missionHistory, MissionHistoryRepository $missionHistoryRepository): Response
    {
        $form = $this->createForm(MissionHistoryType::class, $missionHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionHistoryRepository->save($missionHistory, true);

            return $this->redirectToRoute('back_mission_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission_history/edit.html.twig', [
            'mission_history' => $missionHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MissionHistory $missionHistory, MissionHistoryRepository $missionHistoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$missionHistory->getId(), $request->request->get('_token'))) {
            $missionHistoryRepository->remove($missionHistory, true);
        }

        return $this->redirectToRoute('back_mission_history_index', [], Response::HTTP_SEE_OTHER);
    }
    */
}
