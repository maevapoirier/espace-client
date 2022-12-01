<?php

namespace App\Controller;

use App\Zoho\ZohoInvoicesApiManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentsController extends AbstractController
{
    #[Route('/documents', name: 'app_documents')]
    public function index(ZohoInvoicesApiManager $zohoInvoicesApiManager): Response
    {
        try {
            $invoices = $zohoInvoicesApiManager->getInvoices(337262000000039121);
        } catch (Exception $e){
            dump($e);
        }
        
        return $this->render('documents/index.html.twig', [
            'invoices' => $invoices,
        ]);
    }

    #[Route('/documents/{id}', name: 'documents_pdf')]
    public function getDocumentPdf(int $id, ZohoInvoicesApiManager $zohoInvoicesApiManager): Response
    {
        try {
            $response = $zohoInvoicesApiManager->getInvoicePdf($id);
            //dd($response);
        } catch (Exception $e){
            dump($e);
        }
        
        return new Response($response, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="file.pdf"'
]);
    }
}
