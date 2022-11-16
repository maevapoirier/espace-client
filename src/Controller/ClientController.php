<?php

namespace App\Controller;

use App\Context\ClientContext;
use App\Form\ClientType;
use App\Form\UpdatePasswordType;
use Azuracom\ApiSdkBundle\ApiClient\ClientApi;
use Azuracom\ApiSdkBundle\ApiClient\SecurityApi;
use Azuracom\ApiSdkBundle\ApiClient\UserApi;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ClientController extends AbstractController
{
    #[Route('/profil', name: 'app_client')]
    public function index(ClientContext $clientContext): Response
    {
        return $this->render('client/index.html.twig', [
        ]);
    }

    #[Route('/modifierProfil', name: 'app_client_update')]
    public function update(Request $request, UserApi $userApi, ClientApi $clientApi, ClientContext $clientContext): Response
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

        return $this->render('client/update.html.twig', [
            'clientForm' => $clientForm->createView()
        ]);
    }

    #[Route('/modifierMotDePasse', name: 'app_client_update_password')]
    public function updatePassword(Request $request, SecurityApi $securityApi): Response
    {
        $passwordForm = $this->createForm(UpdatePasswordType::class);
        $passwordForm->handleRequest($request);
        

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $resultForm = $passwordForm->getData();
            if ( $resultForm['newPassword'] == $resultForm['newPassword2']
                & $securityApi->loginCheck('app_client', $this->getUser()->getUserIdentifier(), $resultForm['oldPassword']) != false ) {

                    // enregistrer le nouveau mot de passe
                    $this->addFlash(
                        'success',
                        'Votre Mot de passe a bien été modifié.'
                    );

                        return $this->redirectToRoute('app_client', []);
                        } else {
                            if ($resultForm['newPassword'] == $resultForm['newPassword2']) {
                                $this->addFlash(
                                    'danger',
                                    'Votre ancien mot de passe est incorrect. Veuillez réessayer.'
                                );
                            } else {
                                $this->addFlash(
                                    'danger',
                                    'Les mots de passe ne sont pas identiques.'
                                );
                            }
                                
                            return $this->render('client/updatePassword.html.twig', [
                                'passwordForm' => $passwordForm->createView()
                            ]);
                            }
        }

        return $this->render('client/updatePassword.html.twig', [
            'passwordForm' => $passwordForm->createView()
        ]);
    }
}
