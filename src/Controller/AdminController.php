<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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
    public function show_content(Contact $contact): Response
    {

        return $this->render('admin/show_message.html.twig',
            ['messages' => $contact]
        );

    }
}
