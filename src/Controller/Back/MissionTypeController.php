<?php

namespace App\Controller\Back;

use App\Entity\MissionType;
use App\Form\MissionTypeType;
use App\Repository\MissionTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

#[Route('/mission-type',  name: 'mission_type_')]
class MissionTypeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionTypeRepository $missionTypeRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/mission_type/index.html.twig', [
            'mission_types' => $missionTypeRepository->findAll(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionTypeRepository $missionTypeRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $missionType = new MissionType();
        $form = $this->createForm(MissionTypeType::class, $missionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionTypeRepository->save($missionType, true);

            return $this->redirectToRoute('back_mission_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission_type/new.html.twig', [
            'mission_type' => $missionType,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    /*
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MissionType $missionType): Response
    {
        return $this->render('back/mission_type/show.html.twig', [
            'mission_type' => $missionType,
        ]);
    }
    */

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MissionType $missionType, MissionTypeRepository $missionTypeRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $form = $this->createForm(MissionTypeType::class, $missionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionTypeRepository->save($missionType, true);

            return $this->redirectToRoute('back_mission_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/mission_type/edit.html.twig', [
            'mission_type' => $missionType,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MissionType $missionType, MissionTypeRepository $missionTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$missionType->getId(), $request->request->get('_token'))) {
            $missionTypeRepository->remove($missionType, true);
        }

        return $this->redirectToRoute('back_mission_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
