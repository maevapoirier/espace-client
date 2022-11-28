<?php

namespace App\Controller;

use App\Email\SupportEmail;
use Azuracom\MailerBundle\Sender\SenderInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType as TypeFileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupportController extends AbstractController
{
    #[Route('/support', name: 'app_support')]
    public function index(Request $request, SenderInterface $sender): Response
    {
        $form =$this->createFormBuilder()
        ->add('subject',TextType::class)
        ->add('message',TextareaType::class)
        ->add('attached_file',TypeFileType::class, [
            'required' => false
        ])
        ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            try {
                $sender->send(
                    SupportEmail::getCode(),
                    $form->getData()+['user_email' => $this->getUser()->getUserIdentifier()]
                );

                $this->addFlash(
                    'success',
                    'Votre demande a bien été prise en compte. Nous reviendrons vers vous dans les meilleurs délais.'
                );

            } catch (Exception $e) {

                $this->addFlash(
                    'danger',
                    'La fonction d\'envoi des tickets est indisponible
                     pour le moment. Merci de contacter l\'administrateur.');
            }
                 
            return $this->redirectToRoute('app_home', []);
        }

        return $this->render('support/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}