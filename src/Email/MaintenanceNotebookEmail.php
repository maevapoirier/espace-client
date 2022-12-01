<?php

namespace App\Email;

use Azuracom\MailerBundle\Email\AbstractEmail;
use Azuracom\MailerBundle\Email\EmailInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class MaintenanceNotebookEmail extends AbstractEmail
{
    private $mailJeanMarie;
    private $dir;

    public function __construct(KernelInterface $kernel, $mailJeanMarie)
    {
        //call parent construct !
        parent::__construct();
        $this->mailJeanMarie = $mailJeanMarie;
        $this->dir = $kernel->getProjectDir().'/public/uploads';
    }

    public function configure(): EmailInterface
    {
        $this->subject("[Espace client] Demande de nouveau carnet : ");
        $this->to($this->mailJeanMarie);
        $this->htmlTemplate("email/maintenanceNotebook.html.twig");
        
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) :void
    {
        //validate data
        $metadata->addPropertyConstraint('data', new Assert\Collection([
            'allowExtraFields' => true,
            'fields'=>[
                'user_email' => new Assert\Email(),
            ]
        ]));
    }
}

