<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventImage;
use App\Form\AddEventType;
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

        $form = $this->createForm(AddEventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('eventImages')->getData();
            $data = [];

            if (count($files) > 3) {
                $this->addFlash('error', 'Vous ne pouvez pas ajouter plus de 3 images.');
                return $this->redirectToRoute('app_event_create');
            }

            foreach ($files as $file) {
                if ($file) {
                    $imageBase64 = base64_encode(file_get_contents($file));

                    $eventImage = new EventImage();
                    $eventImage->setImage($imageBase64);
                    $eventImage->setEvent($event);

                    $em = $doctrine->getManager();
                    $em->persist($eventImage);
                }
            }

            $em = $doctrine->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute("app_home");
        }


        return $this->render('event/add-event.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
