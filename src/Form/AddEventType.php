<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddEventType extends AbstractType
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
                'description',
                TextType::class,
                [
                    "label" => "Description",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner une description"]),
                        new Length(["max" => 1000, "maxMessage" => "La description doit comporter moins de 1000 charactères"]),
                    ]
                ]
            )
            ->add(
                'maxUser',
                NumberType::class,
                [
                    "label" => "Participants max",
                    "required" => true,
                    "constraints" => [
                        new NotBlank(["message" => "Veuillez renseigner un nombre max de participants"]),
                        new GreaterThanOrEqual([
                            "value" => 4,
                            "message" => "Le nombre min de participants doit être supérieur ou égal à {{ compared_value }}",
                        ]),
                    ],
                ]
            )
            ->add('startDate', DateTimeType::class, [
                "widget" => "single_text",
                "html5" => true,
                "label" => "Date de début"
            ])
            ->add('endDate', null, [
                "widget" => "single_text",
                "html5" => true,
                "label" => "Date de fin"
            ])
            ->add('eventImages', CollectionType::class, [
                'entry_type' => EventImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => "Images",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
