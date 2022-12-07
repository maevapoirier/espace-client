<?php

namespace App\Form;

use Azuracom\ApiSdkBundle\ApiClient\SecurityApi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContext;

class EditPasswordType extends AbstractType
{
    private $security;
    private $securityApi;

    public function __construct(Security $security, SecurityApi $securityApi)
    {
        $this->security= $security;
        $this->securityApi= $securityApi;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            'label' => 'Votre ancien mot de passe', 
            'constraints' => [
                new Callback(function($value, ExecutionContext $context) {
                    if ($this->securityApi->loginCheck('app_client', $this->security->getUser()->getUserIdentifier(), $value) == false) {
                        $context->buildViolation('L\'ancien mot de passe est incorrect.')->atPath('oldPassword')->addViolation();
                    }
                })
            ],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'constraints' => [
                new Length(min:8, minMessage:"Le mot de passe doit comporter au minimum 8 caractères")
            ],
            'invalid_message' => 'Les 2 mots de passe doivent être identiques.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Confirmation du nouveau mot de passe'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
