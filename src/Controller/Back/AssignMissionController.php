<?php

namespace App\Controller\Back;

use App\Entity\Mission;
use App\Form\AssignMission;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/assign_mission', name: 'assign_mission_')]
class AssignMissionController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user): Response
    {

        $on_hold = $missionRepository->findBy(['hero' => null, 'status' => 'En attente']);

        return $this->render('back/assign_mission/index.html.twig', [
            'missions' => $on_hold, 'En attente' => $on_hold,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}/assign', name: 'assign', methods: ['GET', 'POST'])]
    public function assign(Request $request, Mission $mission, MissionRepository $missionRepository, UserRepository $userRepository, UserInterface $user, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AssignMission::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hero = $mission->getHero();
            $mission->setStatus('Assigné');
            $mission->setUpdatedAt(new DateTimeImmutable('now'));
            
            $missionRepository->save($mission, true);

            //send email to hero
            $email = (new TemplatedEmail())
                ->from(new Address('notifications@herocall.fr', 'HeroCall'))
                ->to($hero->getUser()->getEmail())
                ->subject('Une nouvelle mission vous a été assignée')
                ->htmlTemplate('mailer/mission_assigned.html.twig')
                ->context([
                    'hero' => $hero,
                    'mission' => $mission,
                ]);

            $mailer->send($email);


            return $this->redirectToRoute('back_assign_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/assign_mission/assign.html.twig', [
            'mission' => $mission,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }
}
