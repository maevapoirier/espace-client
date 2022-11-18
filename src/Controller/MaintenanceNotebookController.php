<?php

namespace App\Controller;

use App\Context\ClientContext;
use Azuracom\ApiSdkBundle\ApiClient\MaintenanceNotebookApi;
use Azuracom\ApiSdkBundle\ApiClient\WorkingSessionApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceNotebookController extends AbstractController
{
    #[Route('/anciensCarnets', name: 'app_maintenance_notebook')]
    public function index(MaintenanceNotebookApi $maintenanceNotebookApi, ClientContext $clientContext, WorkingSessionApi $workingSessionApi): Response
    {
        $maintenanceNotebooks = $maintenanceNotebookApi->getListItems(
            null,
            ['query'=>[
                'page' => 1,
                'itemsPerPage' => 30,
                "client.id"=>$clientContext->getClientId(),
                "order[dateEnd]" => "desc"
                ]]
        );

        array_shift($maintenanceNotebooks); // old maintenance notebooks

        foreach ($maintenanceNotebooks as $mn) { //for each maintenance notebook we get working sessions

            $workingSessions = $workingSessionApi->getListItems(
                null,
                ['query'=>[
                    'maintenanceNotebook.id' => $mn['id'],
                    'page' => 1,
                    'itemsPerPage' => 30]]
            );

            $ws[$mn['id']] = $workingSessions;
        }

        return $this->render('maintenance_notebook/index.html.twig', [
            'maintenanceNotebooks' => $maintenanceNotebooks,
            'ws' => $ws
        ]);
    }
}
