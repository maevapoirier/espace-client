<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\ProjectApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/projects-ajax-controller', name: 'projects_ajax_controller')]
    public function getProjects(ProjectApi $projectApi, ClientContext $clientContext): Response
    {

        $projects = $projectApi->getListItems(null, ['query'=>["client.id"=>$clientContext->getClientId()]]);

        return $this->render('ajax/index.html.twig', [
            'projects' => $projects
        ]);
    }
}
