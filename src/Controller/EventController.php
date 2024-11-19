<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventUser;
use App\Entity\EventImage;
use App\Form\AddEventType;
use App\Repository\EventRepository;
use App\Repository\EventUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    #[Route('/event/create', name: 'app_event_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $event = new Event();
        $event->setIsValidated(false);
        $event->setOwnerUser($this->getUser());

        $form = $this->createForm(AddEventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute("app_home");
        }

        return $this->render('event/add-event.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/events', name: 'app_event_list')]
    public function getAll(EventRepository $eventRepository): Response
    {
        $currentDate = new \DateTime();
        $events = $eventRepository->findBy(
            ['isValidated' => true],
            ['endDate' => 'ASC']
        );

        $filteredEvents = array_filter($events, function ($event) use ($currentDate) {
            return $event->getEndDate() > $currentDate;
        });

        return $this->render('event/list.html.twig', [
            "events" => $filteredEvents,
        ]);
    }

    #[Route('/filtered_events', name: 'filtered_events', methods: ['POST'])]
    public function filteredEvents(Request $request, EventRepository $eventRepository): JsonResponse
    {
        $filter = $request->request->all();
        $events = $eventRepository->findEventsByFilter($filter);

        // Préparer les données JSON
        $eventData = [];
        foreach ($events as $event) {
            $images = $event->getEventImages();
            $firstImage = null;
            if (count($images) > 0) {
                $firstImage = $images[0]->getName();
            }

            $eventData[] = [
                'title' => $event->getTitle(),
                'maxUser' => $event->getMaxUser(),
                'description' => $event->getDescription(),
                'startDate' => $event->getStartDate()->format('Y-m-d H:i'),
                'endDate' => $event->getEndDate()->format('Y-m-d H:i'),
                'image' => $firstImage,
                'detailLink' => $this->generateUrl('app_event_detail', ['id' => $event->getId()]),
            ];
        }

        return new JsonResponse($eventData);
    }


    #[Route('/event/{id}', name: 'app_event_detail')]
    public function getbyId(int $id, EventRepository $eventRepository, EventUserRepository $eventUserRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $user = $this->getUser();
        $isUserRegistered = false;

        if ($user) {
            $existingEventUser = $eventUserRepository->findOneBy([
                'user_participant' => $user,
                'event' => $event
            ]);

            if ($existingEventUser) {
                $isUserRegistered = true;
            }
        }

        $currentDateTime = new \DateTime();
        $eventStartDate = $event->getStartDate();
        $interval = $currentDateTime->diff($eventStartDate);
        $isEventStarted = $interval->invert === 0 && $interval->h === 0 && $interval->i <= 30;

        return $this->render('event/detail.html.twig', [
            'event' => $event,
            'isUserRegistered' => $isUserRegistered,
            'isEventStarted' => $isEventStarted
        ]);
    }


    #[Route('/event/{id}/join', name: 'app_event_join')]
    public function eventRegister(
        int $id,
        EventRepository $eventRepository,
        EventUserRepository $eventUserRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $user = $this->getUser();

        $existingEventUser = $eventUserRepository->findOneBy([
            'user_participant' => $user,
            'event' => $event
        ]);

        if ($existingEventUser) {
            $this->addFlash('notice', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_event_detail', ['id' => $id]);
        }

        $currentUserCount = $eventUserRepository->count(['event' => $event]);

        if ($currentUserCount >= $event->getMaxUser()) {
            $this->addFlash('notice', 'Le nombre maximal d\'utilisateurs pour cet événement a été atteint.');
            return $this->redirectToRoute('app_event_detail', ['id' => $id]);
        }

        $eventUser = new EventUser();
        $eventUser->setUserParticipant($user);
        $eventUser->setEvent($event);

        $entityManager->persist($eventUser);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez rejoint cet événement avec succès !');

        return $this->redirectToRoute('app_event_detail', ['id' => $id]);
    }

    #[Route('/event/{id}/leave', name: 'app_event_leave')]
    public function leaveEvent(
        int $id,
        EventRepository $eventRepository,
        EventUserRepository $eventUserRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $user = $this->getUser();

        $eventUser = $eventUserRepository->findOneBy([
            'user_participant' => $user,
            'event' => $event
        ]);

        if ($eventUser) {
            $entityManager->remove($eventUser);
            $entityManager->flush();

            $this->addFlash('success', 'Vous vous êtes désinscrit de cet événement.');
        } else {
            $this->addFlash('notice', 'Vous n\'êtes pas inscrit à cet événement.');
        }

        return $this->redirectToRoute('app_event_detail', ['id' => $id]);
    }

    #[Route('/event/{id}/joining', name: 'app_event_joining')]
    public function joinEvent(int $id, EventRepository $eventRepository, EventUserRepository $eventUserRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        $user = $this->getUser();

        $eventUser = $eventUserRepository->findOneBy([
            'user_participant' => $user,
            'event' => $event
        ]);

        if ($eventUser == null) {
            throw $this->createNotFoundException("Vous n'êtes pas inscrit à cet événement !");
        }

        return $this->render('event/joining.html.twig', [
            'event' => $event,
            'user' => $eventUser->getUserParticipant(),
        ]);
    }
}
