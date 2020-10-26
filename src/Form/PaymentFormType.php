<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentYear = (int) date('Y');

        $builder
            ->add('addressBilling', TextType::class, [
                'label' => 'Billing Address',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a billing address.'])
                ]
            ])
            ->add('addressDelivery', TextType::class, [
                'label' => 'Delivery Address',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a delivery address.'])
                ]
            ])
            ->add('cardNumber', TextType::class, [
                'label' => 'Card Number',
                'help' => 'Please enter a FAKE card number.',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a card number.'])
                ]
            ])
            ->add('cardType', ChoiceType::class, [
                'label' => 'Card type',
                'choices' => [
                    'Visa' => 'Visa',
                    'Mastercard' => 'Mastercard',
                    'American Express' => 'American Express',
                ]
            ])
            ->add('crypto', TextType::class, [
                'label' => 'Security code',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a security code.'])
                ]
            ])
            ->add('dateExpiration', DateType::class, [
                'label' => 'Expiration date',
                'years' => range($currentYear, $currentYear + 20),
                'format' => 'ddMMMyyyy',
                'days' => [1],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an expiration date.'])
                ]
            ])
            ->add('fullName', TextType::class, [
                'label' => 'Full name',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your full name.'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
