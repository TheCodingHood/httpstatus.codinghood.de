<?php

namespace App\Controller;

use App\Model\CloudflareStatusCodes;
use App\Model\DefaultStatusCodes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function indexAction(): Response
    {
        $statusCodes = DefaultStatusCodes::$statusCodes + CloudflareStatusCodes::$statusCodes;

        return $this->render('index.html.twig', [
            'statusCodes' => $statusCodes,
        ]);
    }

}
