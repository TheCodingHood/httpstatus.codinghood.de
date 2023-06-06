<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class HttpStatusController extends AbstractController
{
    private static array $statusCodeConfig = [
        Response::HTTP_FOUND                => [
            'headers' => [
                'Location' => '/',
            ],
        ],
        Response::HTTP_SEE_OTHER            => [
            'headers' => [
                'Location' => '/',
            ],
        ],
        Response::HTTP_USE_PROXY            => [
            'headers' => [
                'Location' => '/',
            ],
        ],
        Response::HTTP_TEMPORARY_REDIRECT   => [
            'headers' => [
                'Location' => '/',
            ],
        ],
        Response::HTTP_PERMANENTLY_REDIRECT => [
            'headers' => [
                'Location' => '/',
            ],
        ],
    ];

    private static array $nonStandardStatusCodes = [
        522 => 'Connection Timed out',
    ];

    #[Route('/status/{status_code}')]
    public function indexAction(
        #[ValueResolver('status_code')]
        int $statusCode
    ): Response
    {
        $statusText = Response::$statusTexts + self::$nonStandardStatusCodes;

        if (isset($statusText[$statusCode]) === false) {
            throw new \RuntimeException('Invalid status code');
        }

        $responseText = sprintf('%d %s', $statusCode, $statusText[$statusCode]);

        $headers = self::$statusCodeConfig[$statusCode]['headers'] ?? [];

        return new Response($responseText, $statusCode, $headers);
    }

}
