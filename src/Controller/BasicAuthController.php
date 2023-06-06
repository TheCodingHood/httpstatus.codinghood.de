<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class BasicAuthController extends AbstractController
{
    #[Route('/basic-auth/{username}/{password}')]
    public function indexAction(
        #[ValueResolver('username')]
        string $username,
        #[ValueResolver('password')]
        string $password
    ): Response
    {
        $cacheConfig = [
            'must_revalidate' => true,
            'no_cache'        => true,
            'no_store'        => true,
        ];

        $hasSuppliedCredentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $isNotAuthenticated = (
            !$hasSuppliedCredentials ||
            $_SERVER['PHP_AUTH_USER'] !== $username ||
            $_SERVER['PHP_AUTH_PW'] !== $password
        );

        if ($isNotAuthenticated) {
            $response = new Response(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => 'Basic realm="Access denied"',
            ]);

            $response->setCache($cacheConfig);

            return $response;
        }

        $response = new Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        $response->setCache($cacheConfig);

        return $response;
    }

}
