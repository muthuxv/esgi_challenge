<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Hero;
use App\Form\RegistrationFormType;
use App\Form\HeroRegistrationType;
use App\Repository\UserRepository;
use App\Repository\HeroRepository;
use App\Security\AppCustomAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('front_default_index');
        }
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('notification@herocall.fr', 'HeroCall'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            /*
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            */
            $this->addFlash('success', 'Votre compte a été créé avec succès. Veuillez confirmer votre adresse email');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    //hero register
    #[Route('/register-hero', name: 'app_register_hero')]
    public function registerHero(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, Filesystem $filesystem, HeroRepository $heroRepository, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('front_default_index');
        }
        
        $user = new User();
        $hero = new Hero();

        $form = $this->createForm(HeroRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $avatar = $form->get('hero')->get('avatar')->getData();

            if ($avatar) {
                $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/avatar/' . $hero->getAvatar());
                $originalAvatar = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $newAvatar = $originalAvatar.'-'.uniqid().'.'.$avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/avatar/',
                        $newAvatar
                    );
                } catch (FileException $e) {
                    // ... handle exception if something goes wrong
                }
    
                $hero->setAvatar($newAvatar);
            }

            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setUpdatedAt(new DateTimeImmutable('now'));
            $user->setRoles(['ROLE_HERO']);

            $hero->setName($form->get('hero')->get('name')->getData());

            $user->setHero($hero);

            $entityManager->persist($user);

            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('notification@herocall.fr', 'HeroCall'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Votre compte a été créé avec succès. Veuillez confirmer votre adresse email');

            return $this->redirectToRoute('app_login');
        
        }

        return $this->render('registration/register_hero.html.twig', [         
            'registrationForm' => $form->createView(),
        ]);

    }



    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a été confirmée. Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }
}
