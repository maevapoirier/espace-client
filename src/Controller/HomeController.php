<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\MaintenanceNotebookApi;
use Azuracom\ApiSdkBundle\ApiClient\ProjectApi;
use Azuracom\ApiSdkBundle\ApiClient\WorkingSessionApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(
        ProjectApi $projectApi,
        ClientContext $clientContext,
        MaintenanceNotebookApi $maintenanceNotebookApi,
        WorkingSessionApi $workingSessionApi,
        Stopwatch $debugstopwatch
        ): Response
    {

        //$debugstopwatch->start('HomeController');

        $projects = $projectApi->getListItems(null, ['query'=>["client.id"=>$clientContext->getClientId()]]);

        //$debugstopwatch->lap('HomeController');

        $maintenanceNotebook = $maintenanceNotebookApi->getListItems(
            null,
            ['query'=>[
                'page' => 1,
                'itemsPerPage' => 30,
                "client.id"=>$clientContext->getClientId(),
                "order[dateEnd]" => "desc"
                ]]
        );

        //$debugstopwatch->lap('HomeController');

        $workingSessions = $workingSessionApi->getListItems(
            null,
            ['query'=>[
                'id' => $maintenanceNotebook[0]['id'],
                'page' => 1,
                'itemsPerPage' => 100]]
        );

        //$event = $debugstopwatch->stop('HomeController'); 

        //dd($event);
        
        return $this->render('home/index.html.twig', [
            'clientId' => $clientContext->getClientId(),
            'projects' => $projects,
            'maintenanceNotebook' => $maintenanceNotebook[0],
            'workingSessions' => $workingSessions
        ]);

        
    }
}
