<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BPAdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function adminDashboard(ManagerRegistry $doctrine): Response
    {
        $messages = $doctrine->getRepository(Contact::class)->displayAllMessages();

        if (!$messages) {
            throw $this->createNotFoundException(
                'No messages for the moment'
            );
        }
        return $this->render('admin/index.html.twig',
            ['messages' => $messages]);
    }

    #[Route('/show_messages/{id}', name: 'app_show_messages')]
    public function show_content(MailerInterface $mailer,Request $request, ManagerRegistry $doctrine, Contact $contact): Response
    {
        $messages = $doctrine->getRepository(Contact::class)->displayAllMessages();
        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $message = $request->get('message');

            $doctrine->getRepository(Contact::class)->sendEmail($mailer, $message);

            return $this->render('admin/index.html.twig',
                ['messages' => $messages]
            );
        }

        return $this->render('admin/show_message.html.twig',
            ['messages' => $contact]
        );

    }
}
