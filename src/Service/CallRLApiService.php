<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallRLApiService
{
    //on cree un client pour lire des donnees
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRLData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://zsr.octane.gg/teams/6020bc70f1e4807cc70023d9'
        );
        return $response->toArray();
    }
}