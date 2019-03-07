<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Painting;
use App\Entity\PaintingMedia;
use App\Entity\PaintingStyle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaintingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Title'])
            ->add('imageFile', FileType::class, ['label' => 'image', 'required' => false])
            ->add('dimensions', TextType::class, ['label' => 'Dimensions ( cm )', 'help' => 'example: 50 x 50'])
            ->add('year', TextType::class, ['label' => 'Year'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['style' => 'height: 180px;']])
            ->add('price', IntegerType::class, ['label' => 'Price'])
            ->add('artist', EntityType::class, ['class' => Artist::class,
                                                            'query_builder' => function (EntityRepository $er){return $er->createQueryBuilder('a')->orderBy('a.lastname', 'ASC');},
                                                            'choice_label' => 'Artist', 'placeholder' => '-- Choose an artist'])
            ->add('media', EntityType::class, ['class' => PaintingMedia::class,
                                                            'query_builder' => function (EntityRepository $er){return $er->createQueryBuilder('m')->orderBy('m.name', 'ASC');},
                                                            'choice_label' => 'Media', 'placeholder' => '-- Choose a media'])
            ->add('style', EntityType::class, ['class' => PaintingStyle::class,
                                                            'query_builder' => function (EntityRepository $er){return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');},
                                                            'choice_label' => 'Style', 'placeholder' => '-- Choose a style'])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-info btn-lg btn-submit-custom', 'style' => 'font-size: 1.8em;']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Painting::class,
        ]);
    }
}
