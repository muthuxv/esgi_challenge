<?php

namespace App\Controller\Back;

use App\Entity\Ability;
use App\Form\AbilityType;
use App\Repository\AbilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

#[Route('/ability', name: 'ability_')]
class AbilityController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AbilityRepository $abilityRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/ability/index.html.twig', [
            'abilities' => $abilityRepository->findAll(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, AbilityRepository $abilityRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $ability = new Ability();
        $form = $this->createForm(AbilityType::class, $ability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abilityRepository->save($ability, true);

            return $this->redirectToRoute('back_ability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/ability/new.html.twig', [
            'ability' => $ability,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    /*
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Ability $ability): Response
    {
        return $this->render('back/ability/show.html.twig', [
            'ability' => $ability,
        ]);
    }
    */

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ability $ability, AbilityRepository $abilityRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $form = $this->createForm(AbilityType::class, $ability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abilityRepository->save($ability, true);

            return $this->redirectToRoute('back_ability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/ability/edit.html.twig', [
            'ability' => $ability,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Ability $ability, AbilityRepository $abilityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ability->getId(), $request->request->get('_token'))) {
            $abilityRepository->remove($ability, true);
        }

        return $this->redirectToRoute('back_ability_index', [], Response::HTTP_SEE_OTHER);
    }
}
