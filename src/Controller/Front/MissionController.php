<?php

namespace App\Controller\Front;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Hero;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/mission', name: 'mission_')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository, UserInterface $user): Response
    {
        return $this->render('front/mission/index.html.twig', [
            'missions' => $missionRepository->findBy(array('user' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionRepository $missionRepository): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setStatus('En attente');
            $mission->setCreatedAt(new DateTimeImmutable('now'));
            $mission->setUpdatedAt(new DateTimeImmutable('now'));
            $mission->setUser($this->getUser());
            // Corriger le sethero
            $mission->setHero();

            $missionRepository->save($mission, true);

            return $this->redirectToRoute('front_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('front/mission/show.html.twig', [
            'mission' => $mission,
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