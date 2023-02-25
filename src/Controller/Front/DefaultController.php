<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\HeroRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\UpdateUserProfile;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use App\Repository\EventRepository;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(UserRepository $userRepository, UserInterface $user, EventRepository $eventRepository, HeroRepository $heroRepository): Response
    {
        $bestHeroes = $heroRepository->findAll();

        //tri des heroes par ordre de rang en commencant par le rang S
        $ranks = ['S', 'A', 'B', 'C'];

        $bestHeroesByRank = [];
        foreach ($ranks as $rank) {
            foreach ($bestHeroes as $hero) {
                if ($hero->getRank() == $rank) {
                    $bestHeroesByRank[$rank][] = $hero;
                }
            }
        }
        //get the best heroes
        $bestHeroes = [];
        foreach ($bestHeroesByRank as $rank) {
            foreach ($rank as $hero) {
                $bestHeroes[] = $hero;
            }
        }

        return $this->render('front/default/index.html.twig', [
            'user' => $userRepository->findBy(array('id' => $user->getId())),
            'events' => $eventRepository->findAllByDateAndLimit(),
            'bestHeroes' => $bestHeroes,
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

            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $userRepository->save($user, true);

            return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/default/update_user_profile.html.twig', [
            'user' => $userRepository->findBy(array('id' => $user->getId())),
            'form' => $form,
        ]);
    }

    //liste des heroes par ordre de rang
    #[Route('/list-heroes', name: 'heroes')]
    public function heroes(HeroRepository $heroRepository, UserInterface $user, UserRepository $userRepository): Response
    {
        $heroes = $heroRepository->findAll();

        $heroesByRank = [];
        foreach ($heroes as $hero) {
            $rank = $hero->getRank();
            if (!isset($heroesByRank[$rank])) {
                $heroesByRank[$rank] = [];
                $heroesByRank[$rank][] = $hero;
            }else {
                $heroesByRank[$rank][] = $hero;
            }
        }

        return $this->render('front/default/heroes.html.twig', [
            'heroes' => $heroesByRank,
            'user' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }
}
