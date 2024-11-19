<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterEventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    "label" => "Titre",
                    "required" => false
                ]
            )
            ->add(
                'maxUser',
                NumberType::class,
                [
                    "label" => "Maximum de participants",
                    "required" => false
                ]
            )
            ->add(
                'startDate',
                DateTimeType::class,
                [
                    "label" => "Date de début",
                    "widget" => "single_text",
                    "html5" => true,
                ]
            )
            ->add(
                'endDate',
                DateTimeType::class,
                [
                    "label" => "Date de début",
                    "widget" => "single_text",
                    "html5" => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            "method" => "POST"
        ]);
    }
}
