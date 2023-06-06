<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class HttpStatusController extends AbstractController
{
    private static array $statusCodeConfig = [
        Response::HTTP_FOUND => [
            'headers' => [
                'Location' => '/'
            ]
        ],
        Response::HTTP_SEE_OTHER => [
            'headers' => [
                'Location' => '/'
            ]
        ],
        Response::HTTP_USE_PROXY => [
            'headers' => [
                'Location' => '/'
            ]
        ],
        Response::HTTP_TEMPORARY_REDIRECT => [
            'headers' => [
                'Location' => '/'
            ]
        ],
        Response::HTTP_PERMANENTLY_REDIRECT => [
            'headers' => [
                'Location' => '/'
            ]
        ],
    ];

    #[Route('/status/{status_code}')]
    public function indexAction(
        #[ValueResolver('status_code')]
        int $statusCode
    ): Response
    {
        if(isset(Response::$statusTexts[$statusCode]) === false) {
            throw new \Exception('Invalid status code');
        }

        $statusText = Response::$statusTexts[$statusCode];

        $headers = self::$statusCodeConfig[$statusCode]['headers'] ?? [];

        return new Response($statusText, $statusCode, $headers);
    }

}
