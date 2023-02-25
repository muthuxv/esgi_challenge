<?php
 
namespace App\Controller;
 
use App\Entity\EventPayment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use Stripe;
use App\Repository\UserRepository;
use App\Repository\EventPaymentRepository;
use Symfony\Component\Security\Core\User\UserInterface;
 
class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'app_stripe', methods: ['GET'])]
    public function index(Request $request, $id, UserRepository $userRepository, UserInterface $user, EventRepository $eventRepository): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'user' => $userRepository->findBy(array('id' => $user->getId())),
            'event' => $eventRepository->findOneBy(array('id' => $id)),
        ]);
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, EventRepository $eventRepository, EventPaymentRepository $eventPaymentRepository)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => $request->request->get('payPrice') * 100,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );

        $eventPayment = new EventPayment();

        $eventPayment->setEvent($eventRepository->findOneBy(array('id' => $request->request->get('idEvent'))));
        $eventPayment->setUser($this->getUser());
        $eventPaymentRepository->save($eventPayment, true);

        return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
    }
}