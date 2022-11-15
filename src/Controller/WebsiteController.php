<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Video;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteController extends AbstractController
{
    #[Route('/', name: 'app_website')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $images = $doctrine->getRepository(Post::class)->displayAllArticles();
        $video = $doctrine->getRepository(Video::class)->displayAllVideosNbLikes();
        if (!$images) {
            throw $this->createNotFoundException(
                'No image found'
            );
        }

        return $this->render('website/index.html.twig',
            ['images' => $images, 'video' => $video]
        );
    }

}
