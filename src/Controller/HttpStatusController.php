<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class HttpStatusController extends AbstractController
{
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

        return new Response($statusText, $statusCode);
    }

}
