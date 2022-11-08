<?php

namespace App\Controller;

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
}
