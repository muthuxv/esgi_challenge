<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\UpdateUserProfile;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('front/default/index.html.twig', [
            'user' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UpdateUserProfile::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('plainPassword')->getData() != '') {
                // Encode(hash) the plain password, and set it.
                $encodedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                );

                $user->setPassword($encodedPassword);
            }

            $userRepository->save($user, true);

            return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/default/update_user_profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
