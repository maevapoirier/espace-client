<?php

namespace App\Context;

use Azuracom\ApiSdkBundle\ApiClient\ClientApi;
use Azuracom\ApiSdkBundle\Security\User;
use Symfony\Component\Security\Core\Security;

class ClientContext 
{

    public function __construct(private ClientApi $clientApi, private Security $security)
    {

    }
    public function getData(): array
    {
        return $this->clientApi->get($this->getClientId());
    }

    public function getClientId(): int
    {
        /** @var User */
        $user = $this->security->getUser();
        return $user->getConfig()['client'];
    }
}