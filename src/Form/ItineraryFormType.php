<?php

namespace App\Form;

use App\Entity\Destination;
use App\Entity\Itinerary;
use App\Entity\Transport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class ItineraryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destination', EntityType::class, [
                'label' => 'Destination',
                'class' => Destination::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group col-4'],
            ])
            ->add('dayArrival', IntegerType::class, [
                'label' => 'Arrival day',
                'constraints' => [
                    new NotNull(['message' => 'Enter a valid day of arrival.']),
                    new NotBlank(['message' => 'Enter a valid day of arrival.']),
                    new PositiveOrZero(['message' => 'Enter a valid day of arrival.']),
                    new Type(['message' => 'Enter a valid day of arrival.', 'type' => 'int']),
                ],
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group col-2'],
            ])
            ->add('dayDeparture', IntegerType::class, [
                'label' => 'Departure day',
                'constraints' => [
                    new NotNull(['message' => 'Enter a valid day of departure.']),
                    new NotBlank(['message' => 'Enter a valid day of departure.']),
                    new PositiveOrZero(['message' => 'Enter a valid day of departure.']),
                    new Type(['message' => 'Enter a valid day of departure.', 'type' => 'int']),
                ],
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group col-2'],
            ])
            ->add('transport', EntityType::class, [
                'label' => 'Transport',
                'class' => Transport::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group col-4'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Itinerary::class,
        ]);
    }
}