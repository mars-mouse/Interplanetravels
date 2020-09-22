<?php

namespace App\Form;

use App\Entity\Travel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class TravelCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [new NotBlank(['message' => 'Missing name.'])]])
            ->add('maxPlaces', IntegerType::class, [
                'empty_data' => 0,
                'constraints' => [new PositiveOrZero(['message' => 'Invalid number of places.'])]
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
                'constraints' => [new NotBlank(['message' => 'Missing price.'])]
            ])
            ->add('departFrom', ChoiceType::class, ['choice_value' => 'departFrom'])
            ->add('description', TextareaType::class);
    }

    // ->add('email', EmailType::class, [
    //     'help' => 'Votre adresse email',
    //     'empty_data' => '',
    //     'constraints' => [
    //         new NotBlank(['message' => 'Adresse e-mail manquante.']),
    //         new Email(['message' => 'Adresse e-mail non valide.'])
    //     ]

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}
