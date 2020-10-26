<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberPlaces', IntegerType::class, [
                'data' => 1,
                'label' => 'Number of places',
                'constraints' => [
                    new NotNull(['message' => 'Enter a valid number of places.']),
                    new NotBlank(['message' => 'Enter a valid number of places.']),
                    new Positive(['message' => 'Enter a valid number of places.']),
                    new Type(['message' => 'Enter a valid number of places.', 'type' => 'int']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}