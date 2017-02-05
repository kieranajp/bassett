<?php declare(strict_types=1);

namespace Bassett\Application\Controller;

use Aura\Session\Segment;
use League\Plates\Engine as Plates;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Github;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function index(Plates $plates, Segment $session)
    {
        if (! $session->get('token')) {
            header('Location: /authenticate');
            return;
        }

        return $plates->render('index', [ 'token' => $session->get('token') ]);
    }

    public function authenticate(ServerRequestInterface $request, Segment $session)
    {
        $provider = new Github([
            'clientId'          => getenv('GITHUB_CLIENT_ID'),
            'clientSecret'      => getenv('GITHUB_CLIENT_SECRET'),
            'redirectUri'       => getenv('GITHUB_CALLBACK_URL')
        ]);

        if (! isset($request->getQueryParams()['code'])) {
            header('Location: ' . $provider->getAuthorizationUrl());
            return;
        }

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $request->getQueryParams()['code']
        ]);

        $session->set('token', $token->getToken());

        header('Location: /');
        return;
    }
}
