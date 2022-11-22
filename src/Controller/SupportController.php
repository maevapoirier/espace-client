<?php

namespace App\Controller;

use App\Form\SupportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    #[Route('/support', name: 'app_support')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $supportForm = $this->createForm(SupportType::class);
        $supportForm->handleRequest($request);
        

        if ($supportForm->isSubmitted() && $supportForm->isValid()) {
            $resultForm = $supportForm->getData();

            $mail = (new Email())
                ->from(new Address(
                    $this->getUser()->getUserIdentifier(),
                    $this->getUser()->getFirstname() . " " . $this->getUser()->getLastname()
                    ))
                ->to('support@azuracom.com')
                ->subject($resultForm['subject'])
                ->html('<p>'. $resultForm['details'].'</p>')
            ;

            $mailer->send($mail);

            //TODO : ajouter message flash

            
            return $this->redirectToRoute('app_home', [
            ]);
        }

       return $this->render('support/index.html.twig', [
            'supportForm' => $supportForm->createView()
        ]);
    }
}
