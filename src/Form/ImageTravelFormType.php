<?php

namespace App\Form;

use App\Entity\ImageTravel;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageTravelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('travel', EntityType::class, [
                'label' => 'Travel',
                'class' => Travel::class,
                'choice_label' => 'name'
            ])
            ->add('filename', FileType::class, [
                'label' => 'Image file',
                // Ce n'est pas la propriété $source directement
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG/PNG/WEBP image.',
                    ])
                ],
            ])
            ->add('caption', TextareaType::class, [
                'label' => 'Caption',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageTravel::class,
        ]);
    }
}