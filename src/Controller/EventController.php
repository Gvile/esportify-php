<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventImage;
use App\Form\AddEventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

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

            // Récupérer les images encodées en base64 envoyées depuis JS
            $base64Images = $request->get('base64Images'); // Vous allez passer cela depuis JS

            if ($base64Images) {
                $em = $doctrine->getManager();

                // Enregistrer les images en base64 dans la base de données
                foreach ($base64Images as $base64Image) {
                    $eventImage = new EventImage();
                    $eventImage->setImage($base64Image); // Sauvegarder l'image en base64
                    $eventImage->setEvent($event); // Associer l'image à l'événement
                    $em->persist($eventImage);
                }

                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute("app_home");
            }
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
}
