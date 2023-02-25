<?php

namespace App\Controller\Back;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\BackUpdateUserProfile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use App\Form\UpdateUserProfile;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use App\Repository\MissionRepository;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(UserRepository $userRepository, UserInterface $user, EventRepository $eventRepository, MissionRepository $missionRepository): Response
    {
        return $this->render('back/default/index.html.twig', [
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
            'all_events' => $eventRepository->findAll(),
            'users' => $userRepository->findAll(),
            'lastest_users' => $userRepository->findAllByDate(),
            'missions' => $missionRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(BackUpdateUserProfile::class, $user);
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
            
            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $userRepository->save($user, true);

            return $this->redirectToRoute('back_default_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/profil/edit.html.twig', [
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
            'form' => $form,
        ]);
    }
}
