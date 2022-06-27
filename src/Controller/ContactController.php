<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact',  methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ManagerRegistry $doctrine,
        MailerInterface $mailer
    ): Response {
        $contact = new Contact();
        // Recupeéeratiopn des infos utilisateur connecté 
        //  \dump($this->getUser());
        if ($this->getUser()) {
            $contact->setFullName($this->getUser()->getFullname())
                ->setEmail($this->getUser()->getEmail());
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if (($form->isSubmitted() && $form->isValid())) {


            $contact = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($contact);
            $em->flush();

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('benquelm@gmail.com')
                ->subject($contact->getSubject())
                ->html($contact->getMessage());
            // \dd($email);

            $mailer->send($email);
            $this->addFlash('badge rounded-pill bg-info mt-2 text-white text-center', 'Votre demande a été envoyé avec succès !');

            return $this->redirectToRoute('contact');
        }



        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
