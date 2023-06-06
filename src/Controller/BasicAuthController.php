<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class BasicAuthController extends AbstractController
{
    #[Route('/basic-auth/{username}/{password}')]
    #[Cache(maxage: 0, mustRevalidate: true )]
    public function indexAction(
        #[ValueResolver('username')]
        string $username,
        #[ValueResolver('password')]
        string $password
    ): Response
    {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $hasSuppliedCredentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $isNotAuthenticated = (
            !$hasSuppliedCredentials ||
            $_SERVER['PHP_AUTH_USER'] !== $username ||
            $_SERVER['PHP_AUTH_PW']   !== $password
        );

        if ($isNotAuthenticated) {
            return new Response(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => 'Basic realm="Access denied"'
            ]);
        }

        $response = new Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);

        $response->setCache([
            'must_revalidate'  => true,
            'no_cache'         => true,
            'no_store'         => true,
        ]);

        return $response;
    }

}
