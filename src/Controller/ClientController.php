<?php

namespace App\Controller;

use App\Context\ClientContext;
use App\Form\ClientType;
use App\Form\FileType;
use App\Form\EditPasswordType;
use Azuracom\ApiSdkBundle\ApiClient\ClientApi;
use Azuracom\ApiSdkBundle\ApiClient\MediaObjectApi;
use Azuracom\ApiSdkBundle\ApiClient\SecurityApi;
use Azuracom\ApiSdkBundle\ApiClient\UserApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class ClientController extends AbstractController
{
    #[Route('/profil', name: 'app_client')]
    public function index(ClientContext $clientContext): Response
    {
        return $this->render('client/index.html.twig', [
        ]);
    }

    #[Route('/modifier-profil', name: 'app_client_edit')]
    public function edit(Request $request, UserApi $userApi, ClientApi $clientApi, ClientContext $clientContext): Response
    {
        $clientForm = $this->createForm(ClientType::class);
        $clientForm->handleRequest($request);
        

        if ($clientForm->isSubmitted() && $clientForm->isValid()) {
            $resultForm = $clientForm->getData();
            $userId = $this->getUser()->getId();

            $user = [
                'firstname' => $resultForm['firstname'],
                'lastname' => $resultForm['lastname'],
                'email' => $resultForm['login']
            ];
            $client = ['name' => $resultForm['client_name'], 'description' => $resultForm['address']];
            
            $resultClient = $clientApi->update($client, $clientContext->getClientId(), []);
            $resultUser = $userApi->update($user, $userId, []);
            
            if ($resultClient == true & $resultUser == true) {
                $this->addFlash(
                    'success',
                    'Vos informations personnelles ont bien été mises à jour.'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'Vos informations n\'ont pas pu être mises à jour. Veuillez contacter votre référent technique.'
                );
            }

            return $this->redirectToRoute('app_client', [
            ]);
        }

        return $this->render('client/edit.html.twig', [
            'clientForm' => $clientForm->createView()
        ]);
    }

    #[Route('/modifier-mot-de-passe', name: 'app_client_edit_password')]
    public function editPassword(Request $request, SecurityApi $securityApi, UserApi $userApi): Response
    {
        $passwordForm = $this->createForm(EditPasswordType::class);
        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) 
        {
            $resultForm = $passwordForm->getData();
            $result = $userApi->update(['password' => $resultForm['password']], $this->getUser()->getId(), []);
            if ($result = true) {
                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié.'
                );  
            } else {
                    $this->addFlash(
                        'danger',
                        'Echec de modification du mot de passe : veuillez contacter votre administrateur.'
                    );  
            }
            return $this->redirectToRoute('app_client', [
            ]);
        }
    
        return $this->render('client/editPassword.html.twig', [
            'passwordForm' => $passwordForm->createView()
        ]);

    }

    #[Route('/modifier-image-profil', name: 'app_edit_avatar')]
    public function editAvatar(Request $request, UserApi $userApi, MediaObjectApi $mediaObjectApi): Response
    {
        $form = $this->createForm(FileType::class);
        $form->handleRequest($request);
        $user = $userApi->get($this->getUser()->getId());

        if ($form->isSubmitted() && $form->isValid() && $userApi->postForm($form, $this->getUser()->getId(), [])) {
            $this->addFlash('success',"Votre image de profil a bien été modifiée.");
            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/editAvatar.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
}
