<?php

namespace App\Form;

use App\Entity\TravelDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class TravelDateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentYear = (int) date('Y');

        $builder
            ->add('dateDeparture', DateTimeType::class, [
                'label' => 'Date of Departure',
                'years' => range($currentYear, $currentYear + 10),
                'seconds' => [0],
                'minutes' => [0, 15, 30, 45],
                // 'attr' => ['class' => 'form-row'],
                'placeholder' => [
                    'year' => 'Year',
                    'month' => 'Month',
                    'day' => 'Day',
                    'hour' => 'Hours',
                    'minute' => 'Mins',
                ],
                //'format' => 'Y-m-d h:i:s',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Please enter a date.']
                    ),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Please enter a valid date.'
                    ]),
                ]
            ])
            ->add('dateReturn', DateTimeType::class, [
                'label' => 'Date of Arrival',
                'years' => range($currentYear, $currentYear + 10),
                'seconds' => [0],
                'minutes' => [0, 15, 30, 45],
                // 'attr' => ['class' => 'form-row'],
                'placeholder' => [
                    'year' => 'Year',
                    'month' => 'Month',
                    'day' => 'Day',
                    'hour' => 'Hours',
                    'minute' => 'Mins',
                ],
                //'format' => 'Y-m-d h:i:s',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Please enter a date.']
                    ),
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Please enter a valid date.'
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TravelDate::class,
        ]);
    }
}