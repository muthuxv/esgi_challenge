<?php

namespace App\Controller\Back;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\UpdateEventType;
use Symfony\Component\Filesystem\Filesystem;
use DateTimeImmutable;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EventRepository $eventRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository, UserRepository $userRepository, UserInterface $user): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['filename']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something goes wrong
                }
    
                $event->setFilename($newFilename);
            }
            $event->setCreatedAt(new DateTimeImmutable('now'));
            $event->setUpdatedAt(new DateTimeImmutable('now'));

            $eventRepository->save($event, true);

            return $this->redirectToRoute('back_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    /*
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Event $event, UserRepository $userRepository, UserInterface $user): Response
    {
        return $this->render('back/event/show.html.twig', [
            'event' => $event,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }
    */

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository, UserRepository $userRepository, UserInterface $user, Filesystem $filesystem): Response
    {
        $form = $this->createForm(UpdateEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['filename']->getData();

            if ($imageFile) {
                $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/' . $event->getFilename());
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something goes wrong
                }
    
                $event->setFilename($newFilename);
            }
            
            $event->setUpdatedAt(new DateTimeImmutable('now'));
            $eventRepository->save($event, true);

            return $this->redirectToRoute('back_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'user_admin' => $userRepository->findBy(array('id' => $user->getId())),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/' . $event->getFilename());
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('back_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
