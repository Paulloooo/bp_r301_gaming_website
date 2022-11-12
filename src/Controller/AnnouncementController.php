<?php

namespace App\Controller;

use App\Entity\Announcement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AnnouncementController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/admin/create-announcement', name: 'app_create_ann')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {

        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $title = $request->get('title');
            $content = $request->get('content');
            $link = $request->get('link');

            $announcement = new Announcement();
            $announcement->setTitle($title);
            $announcement->setContent($content);
            $announcement->setMedia($link);
            $announcement->setDate(\DateTime::createFromFormat("d/m/y", date('d/m/y')));


            $entityManager->persist($announcement);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->render('announcement/announcement_published.html.twig', [
                  'controller_name' => 'AnnouncementController',
            ]);

        }
        return $this->render('announcement/create.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);

    }


    #[Route('/show-announcements', name: 'app_show_ann')]
    public function show(ManagerRegistry $doctrine): Response
    {
        $announcement = $doctrine->getRepository(Announcement::class)->displayAllAnnouncements();

        if (!$announcement) {
            throw $this->createNotFoundException(
                'No announcements for the moment.'
            );
        }

        return $this->render('announcement/show_all_announcements.html.twig',
            ['announcements' => $announcement]
        );

    }

    #[Route('/show-announcements/{id}', name: 'app_show_ann_content')]
    public function show_content(Announcement $announcement): Response
    {
        return $this->render('announcement/show_announcement_content.html.twig',
            ['announcement' => $announcement]
        );
    }
}