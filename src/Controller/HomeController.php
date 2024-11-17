<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        $currentDate = new \DateTime();

        $events = $eventRepository->findUpcomingAndOngoingEvents($currentDate);

        return $this->render('home/index.html.twig', [
            'upcomingEvents' => $events['upcoming'],
            'ongoingEvents' => $events['ongoing'],
        ]);
    }
}
