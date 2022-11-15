<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\Video;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\DoctrineConfig;

class VideoController extends AbstractController
{
    #[Route('/video', name: 'app_video')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $video = $doctrine->getRepository(Video::class)->displayAllVideos();

        if (!$video) {
            throw $this->createNotFoundException(
                'No video found'
            );
        }

        return $this->render('video/video.html.twig',
            ['video' => $video]
        );
    }

    #[Route('/video/{id}/like', name: 'app_like', methods : ['POST'])]
    public function like(Video $video, Request $request, EntityManagerInterface $entityManager) : Response
    {
        if($video->getNbLikes()==null){
            $video->setNbLikes(1);
        }else{
            $video->setNbLikes($video->getNbLikes()+1);
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_video');

    }

    #[Route('/admin/create-video', name: 'app_create_vid')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {

        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $title = $request->get('title');
            $link = $request->get('link');
            $category = $request->get('category');

            $link = $doctrine->getRepository(Video::class)->setLinkForYtVideo($link);

            $video = new Video();
            $video ->setTitle($title);
            $video -> setLink($link);
            $video -> setCategory($category);

            $entityManager->persist($video);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->render('video/video_published.html.twig', [
                'controller_name' => 'VideoController',
            ]);

        }
        return $this->render('video/create.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);

    }

    #[Route('/video/{category}', name: 'app_show_vid_category')]
    public function showCategory(Request $request, ManagerRegistry $doctrine): Response
    {

        $category = $request->get('category');
        $video = $doctrine->getRepository(Video::class)->displayAllVideosByCategory($category);

        return $this->render('video/video.html.twig',
            ['video' => $video]
        );

    }
}
