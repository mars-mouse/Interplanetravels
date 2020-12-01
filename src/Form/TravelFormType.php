<?php

namespace App\Form;

use App\Entity\DepartFrom;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

class TravelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('maxPlaces', IntegerType::class, [
            //     'label' => 'Maximum number of places',
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotNull(['message' => 'Enter a valid number of places.']),
            //         new NotBlank(['message' => 'Enter a valid number of places.']),
            //         new PositiveOrZero(['message' => 'Enter a valid number of places.']),
            //         new Type(['message' => 'Enter a valid number of places.', 'type' => 'int']),
            //     ]
            // ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    new NotNull(['message' => 'Please enter a name.']),
                    new NotBlank(['message' => 'Please enter a name.'])
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Price per place',
                'constraints' => [
                    new NotNull(['message' => 'Enter a valid price.']),
                    new NotBlank(['message' => 'Enter a valid price.']),
                    new Positive(['message' => 'Enter a valid price.']),
                    new Type(['message' => 'Enter a valid price.', 'type' => 'int']),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('departFrom', EntityType::class, [
                'label' => 'Depart from',
                'class' => DepartFrom::class,
                'choice_label' => 'name'
            ])
            ->add('itineraries', CollectionType::class, [
                'entry_type' => ItineraryFormType::class,
                'entry_options' => [
                    // 'label' => false,
                    'attr' => ['class' => 'form-row col-10'],
                ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}