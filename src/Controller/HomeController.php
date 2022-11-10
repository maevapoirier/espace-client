<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\ProjectApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    

    #[Route('/', name: 'app_home')]
    public function index(
        ProjectApi $projectApi,
        ClientContext $clientContext
        ): Response
    {

        
        $projects = $projectApi->getListItems(null, ['query'=>["client.id"=>$clientContext->getClientId()]]);
        //$nameLastProject = $projects[0]['name'];
        
        
        //dd($clientApi->get($this->getUser()->getConfig()["client"]));
        //dd($maintenanceNotebookApi->getListItems(null, ['query'=>["client.id"=>4,"enabled"=>"true"]]))
        
        return $this->render('home/index.html.twig', [
            'projects' => $projects
        ]);
    }
}
