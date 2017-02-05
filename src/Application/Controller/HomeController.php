<?php declare(strict_types=1);

namespace Bassett\Application\Controller;

use Github\Client;
use League\Plates\Engine as Plates;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function index(Plates $plates)
    {
        return $plates->render('test', []);
    }

    public function authenticate(Client $client)
    {
        $client->authenticate(getenv('GITHUB_CLIENT_ID'), getenv('GITHUB_CLIENT_SECRET'), Client::AUTH_URL_CLIENT_ID);
    }
}
