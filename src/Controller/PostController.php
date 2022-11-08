<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
{

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {

        if($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $title = $request->get('title');
            $author = $this->security->getUser()->getUsername();
            $content = $request->get('content');
            $link = $request->get('link');

            $publication = new Post();
            $publication->setTitle($title);
            $publication->setContent($content);
            $publication->setAuthor($author);
            $publication->setMedia($link);
            $publication->setDatePublication(\DateTime::createFromFormat("d/m/y",date('d/m/y')));


            $entityManager->persist($publication);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->render('post/post_created.html.twig', [
                'controller_name' => 'PostController',
            ]);

        }
        return $this->render('post/create.html.twig', [
            'controller_name' => '¨PostController',
        ]);

    }


    #[Route('/show', name: 'app_show')]
    public function show(ManagerRegistry $doctrine): Response
    {
        $article = $doctrine->getRepository(Post::class)->displayAllArticles();

        if (!$article) {
            throw $this->createNotFoundException(
                'No post found'
            );
        }

        return $this->render('post/show.html.twig',
            ['article' => $article]
        );

    }

    #[Route('/show/{id}', name: 'app_show_content')]
    public function show_content(Post $post): Response
    {

        return $this->render('post/show_content.html.twig',
            ['article' => $post]
        );

    }


}
