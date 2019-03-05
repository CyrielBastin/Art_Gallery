<?php

namespace App\Form;

use App\Entity\PaintingStyle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaintingStyleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('imageFile', FileType::class, ['label' => 'image', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['style' => 'height: 180px;']])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-lg btn-submit-custom', 'style' => 'font-size: 1.8em;']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaintingStyle::class,
        ]);
    }
}
