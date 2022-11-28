<?php

namespace App\Controller;

use App\Context\ClientContext;
use App\Email\MaintenanceNotebookEmail;
use Azuracom\ApiSdkBundle\ApiClient\MaintenanceNotebookApi;
use Azuracom\ApiSdkBundle\ApiClient\WorkingSessionApi;
use Azuracom\MailerBundle\Sender\SenderInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/renouveler-mon-carnet', name: 'maintenance_notebook_new')]
    public function newMaintenanceNotebook(Request $request, SenderInterface $sender): Response
    {
        $form =$this->createFormBuilder()
        ->add('subject',TextType::class)
        ->add('message',TextareaType::class, [
            'attr' => ['rows' => 10]
            ])
        ->add('hours', NumberType::class)
        ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            try {
                $sender->send(
                    MaintenanceNotebookEmail::getCode(),
                    $form->getData()+['user_email' => $this->getUser()->getUserIdentifier()]
                );

                $this->addFlash(
                    'success',
                    'Votre demande a bien été prise en compte. Nous reviendrons vers vous dans les meilleurs délais.'
                );

            } catch (Exception $e) {

                $this->addFlash(
                    'danger',
                    'Votre demande n\'a pas pu aboutir. Merci de contacter l\'administrateur.');
            }
                 
            return $this->redirectToRoute('app_home', []);
        }

        return $this->render('maintenance_notebook/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
