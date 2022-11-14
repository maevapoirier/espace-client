<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\MaintenanceNotebookApi;
use Azuracom\ApiSdkBundle\ApiClient\ProjectApi;
use Azuracom\ApiSdkBundle\ApiClient\WorkingSessionApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    

    #[Route('/', name: 'app_home')]
    public function index(
        ProjectApi $projectApi,
        ClientContext $clientContext,
        MaintenanceNotebookApi $maintenanceNotebookApi,
        WorkingSessionApi $workingSessionApi,
        ): Response
    {

        
        $projects = $projectApi->getListItems(null, ['query'=>["client.id"=>$clientContext->getClientId()]]);
        $currentMaintenanceNotebook = $maintenanceNotebookApi->getListItems(
            null,
            ['query'=>[
                'page' => 1,
                'itemsPerPage' => 30,
                "client.id"=>$clientContext->getClientId(),
                "enabled" => true]]
        );

        $workingSessions = $workingSessionApi->getListItems(
            null,
            ['query'=>[
                'id' => $currentMaintenanceNotebook[0]['id'],
                'page' => 1,
                'itemsPerPage' => 100]]
        );

        //dd($projects);
        //dd($currentMaintenanceNotebook[0]['id']);

    
        //dd($clientApi->get($this->getUser()->getConfig()["client"]));
        //dd($maintenanceNotebookApi->getListItems(null, ['query'=>["client.id"=>4]]))
        
        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'maintenanceNotebook' => $currentMaintenanceNotebook[0],
            'workingSessions' => $workingSessions
        ]);
    }
}
