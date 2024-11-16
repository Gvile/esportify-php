<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    "label" => "Email",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner votre adresse mail"])
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    "label" => "Mot de passe",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner un mot de passe"])
                    ]
                ]
            )
            ->add(
                'pseudo',
                TextType::class,
                [
                    "label" => "Pseudo",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner un pseudo"])
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    "label" => "Valider",
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
