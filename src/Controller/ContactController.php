<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(ManagerRegistry $doctrine, Request $request): Response
    {
        $contact = new Contact();
        $contact->setUserSender($this->getUser());
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'notice',
                'Votre demande de contact a bien été envoyée.'
            );

            return $this->redirectToRoute("app_contact");
        }
        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
