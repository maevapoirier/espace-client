<?php

namespace App\Controller;

use App\Context\ClientContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/profil', name: 'app_client')]
    public function index(ClientContext $clientContext): Response
    {

        return $this->render('client/index.html.twig', [
            
        ]);
    }
}
