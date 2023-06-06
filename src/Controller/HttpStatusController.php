<?php

namespace App\Controller;

use App\Model\CloudflareStatusCodes;
use App\Model\DefaultStatusCodes;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class HttpStatusController extends AbstractController
{
    #[Route('/{status_code<\d{3,3}>}', name: 'status')]
    public function indexAction(
        #[ValueResolver('status_code')]
        int $statusCode
    ): Response
    {
        $statusCodes = DefaultStatusCodes::$statusCodes + CloudflareStatusCodes::$statusCodes;

        if (isset($statusCodes[$statusCode]) === false) {
            throw new RuntimeException('Invalid status code');
        }

        $responseText = sprintf('%d %s', $statusCode, $statusCodes[$statusCode]['description']);

        $headers = $statusCodes[$statusCode]['headers'] ?? [];
        return new Response($responseText, $statusCode, $headers);
    }
}
