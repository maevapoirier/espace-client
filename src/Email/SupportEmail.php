<?php

namespace App\Email;

use Azuracom\MailerBundle\Email\AbstractEmail;
use Azuracom\MailerBundle\Email\EmailInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class SupportEmail extends AbstractEmail
{
    private $mailAdmin;
    private $dir;

    public function __construct(KernelInterface $kernel,$mailAdmin)
    {
        //call parent construct !
        parent::__construct();
        $this->mailAdmin = $mailAdmin;
        $this->dir = $kernel->getProjectDir().'/public/uploads';
    }

    public function configure(): EmailInterface
    {
        $this->subject("[Espace client] Demande de support: ".$this->getData()['subject']);
        $this->to($this->mailAdmin);
        $this->cc($this->getData()['user_email']);

        $this->htmlTemplate("email/support.html.twig");
        
        //attachment
        if ($this->getData()['attached_file']){

            $this->attachFromPath(
                $this->getData()['attached_file']->getPathname(),
                $this->getData()['attached_file']->getClientOriginalName()
        );
        }
        
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) :void
    {
        //validate data
        $metadata->addPropertyConstraint('data', new Assert\Collection([
            'allowExtraFields' => true,
            'fields'=>[
                'subject' => new Assert\Type('string'),
                'message' => new Assert\Type('string'),
                'user_email' => new Assert\Email(),
            ]
        ]));
    }
}

