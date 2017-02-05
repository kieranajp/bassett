<?php declare(strict_types=1);

namespace Bassett\Application\Controller;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Aura\Session\Segment;

class PipelineController
{
    public function index(Client $client, Segment $session)
    {
        $request = new Psr7\Request('GET', 'http://ci000.tools.hellofresh.io:8080/api/v1/pipelines', [
            'Authorization' => sprintf('Bearer %s', $session->get('token')),
        ]);

        $response = $client->send($request);
        dd((string) $response->getBody());
    }
}
