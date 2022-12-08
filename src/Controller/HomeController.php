<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\AgencyApi;
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
        AgencyApi $agencyApi,
        ): Response
    {
        $projects = $projectApi->getListItems(null, ['query'=>[
            "client.id"=>$clientContext->getClientId(), 
            "order[dateEnd]" => "desc"
            ]]);

        $maintenanceNotebook = $maintenanceNotebookApi->getListItems(
            null,
            ['query'=>[
                'page' => 1,
                'itemsPerPage' => 30,
                "client.id"=>$clientContext->getClientId(),
                "order[dateEnd]" => "desc"
                ]]
        );

        $workingSessions = $workingSessionApi->getListItems(
            null,
            ['query'=>[
                'id' => $maintenanceNotebook[0]['id'],
                'page' => 1,
                'itemsPerPage' => 100]]
        );

        $agency = $agencyApi->get(1, []);

        //dd($projects);
        
        return $this->render('home/index.html.twig', [
            'clientId' => $clientContext->getClientId(),
            'projects' => $projects,
            'maintenanceNotebook' => $maintenanceNotebook[0],
            'workingSessions' => $workingSessions, 
            'agency' => $agency
        ]);

        
    }
}
