<?php

namespace App\Controller;

use App\Service\CallRLApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RLDataController extends AbstractController
{


    #[Route('/rldata', name: 'app_rl_data')]
    public function index(CallRLApiService $callRLApiService): Response
    {
        return $this->render('rl_data/index.html.twig', [
            'data' => $callRLApiService->getRLData(),
        ]);
    }
}
