<?php

namespace App\Controller\Back;

use App\Entity\EventPayment;
use App\Form\EventPaymentType;
use App\Repository\EventPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

#[Route('/event/payment', name: 'event_payment_')]
class EventPaymentController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EventPaymentRepository $eventPaymentRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/event_payment/index.html.twig', [
            'event_payments' => $eventPaymentRepository->showPayments(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }
}
