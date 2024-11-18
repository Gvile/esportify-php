<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    "label" => "Titre",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner un titre"]),
                        new Length(["max" => 50, "maxMessage" => "Le titre doit comporter moins de 50 charactères"]),
                    ]
                ]
            )
            ->add(
                'message',
                TextType::class,
                [
                    "label" => "Message",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner un titre"]),
                        new Length(["max" => 1000, "maxMessage" => "Le message doit comporter moins de 1000 charactères"]),
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
            'data_class' => Contact::class,
        ]);
    }
}
