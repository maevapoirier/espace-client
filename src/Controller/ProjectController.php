<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\ProjectApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/projets', name: 'app_project')]
    public function index(ProjectApi $projectApi, ClientContext $clientContext): Response
    {
        $projects = $projectApi->getListItems(null, ['query'=>["client.id"=>$clientContext->getClientId()]]);

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
