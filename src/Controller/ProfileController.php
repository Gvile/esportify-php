<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\EventUserRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function profile(EventUserRepository $eventUserRepository, EventRepository $eventRepository): Response
    {
        $user = $this->getUser();

        $userEventsRegistrated = $eventUserRepository->findBy(
            ['user_participant' => $user]
        );

        $eventsOrganized = [];

        if (in_array('ROLE_ORGANIZER', $user->getRoles())) {
            $eventsOrganized = $eventRepository->findBy(
                ['ownerUser' => $user]
            );
        }

        return $this->render('profile/index.html.twig', [
            'events_registrated' => $userEventsRegistrated,
            'events_organized' => $eventsOrganized,
            'user' => $user,
        ]);
    }
}
