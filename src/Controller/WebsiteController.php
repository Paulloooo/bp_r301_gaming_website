<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteController extends AbstractController
{
    #[Route('/', name: 'app_website')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $images = $doctrine->getRepository(Post::class)->displayAllImages();

        if (!$images) {
            throw $this->createNotFoundException(
                'No image found'
            );
        }

        return $this->render('website/index.html.twig',
            ['images' => $images]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(ManagerRegistry $doctrine): Response
    {

        return $this->render('website/contact.html.twig');
    }

}
