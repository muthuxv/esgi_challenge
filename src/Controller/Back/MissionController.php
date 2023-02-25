<?php

namespace App\Controller\Back;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use DateTimeImmutable;

#[Route('/mission', name: 'mission_')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mission->setStatus('En attente');
            $mission->setCreatedAt(new DateTimeImmutable('now'));
            $mission->setUpdatedAt(new DateTimeImmutable('now'));
            $mission->setDateEnd(null);
            $mission->setUser($this->getUser());
            $mission->setHero(null);

            $missionRepository->save($mission, true);

            return $this->redirectToRoute('back_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('back/mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionRepository->save($mission, true);

            return $this->redirectToRoute('back_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, MissionRepository $missionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $missionRepository->remove($mission, true);
        }

        return $this->redirectToRoute('back_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
