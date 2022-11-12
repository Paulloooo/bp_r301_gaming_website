<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ContactController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        if($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $message = $request->get('message');
            $idAuthor = $this->security->getUser()->getId();
            $mailAuthor = $this->security->getUser()->getEmail();

            $contact = new Contact();
            $contact->setContentMessage($message);
            $contact->setIdUser($idAuthor);
            $contact->setEmailUser($mailAuthor);


            $entityManager->persist($contact);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            $email = (new Email())
                ->from('hello@example.com')
                ->to('you@example.com')
                ->subject('Message sent!')
                ->text('Your request has been sent to developer ! ')
                ->html('<p>Vous avez envoyé ce message avec succès ! Il sera lu très prochainement.</p>');

            $mailer->send($email);

            return $this->render('contact/contact_confirmation.html.twig', [
                'controller_name' => 'ContactController',
            ]);
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
