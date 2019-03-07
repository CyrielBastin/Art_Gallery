<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, ['label' => 'Image', 'required' => false])
            ->add('lastname', TextType::class, ['label' => 'Lastname'])
            ->add('firstname', TextType::class, ['label' => 'Firstname'])
            ->add('country', TextType::class, ['label' => 'Country'])
            ->add('commentary', TextareaType::class, ['label' => 'Commentary on the artist', 'attr' => ['style' => 'height: 150px;']])
            ->add('date_of_birth', DateType::class, ['label' => 'Date of birth', 'help' => 'dd/mm/yyyy', 'format' => 'dd/MM/yyyy', 'widget' => 'text', 'required' => false, 'attr' => ['style' => 'margin-top: 1px;']])
            ->add('date_of_death', DateType::class, ['label' => 'Date of death', 'help' => 'dd/mm/yyyy', 'format' => 'dd/MM/yyyy', 'widget' => 'text', 'required' => false, 'attr' => ['style' => 'margin-top: 1px;']])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-lg btn-submit-custom', 'style' => 'font-size: 1.8em;']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
