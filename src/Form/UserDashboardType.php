<?php

namespace App\Form;

use App\Entity\UserProfile;
use App\Entity\UserRoles;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserDashboardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichFileType::class, ['label' => 'Avatar', 'required' => false, 'allow_delete' => false, 'download_uri' => false])
            ->add('lastname', TextType::class, ['label' => 'Lastname', 'required' => false])
            ->add('firstname', TextType::class, ['label' => 'Firstname', 'required' => false])
            ->add('pseudo', TextType::class, ['label' => 'Pseudo', 'required' => true])
            ->add('signature', TextType::class, ['label' => 'Signature', 'required' => false])
            ->add('date_of_birth', DateType::class, ['label' => 'Date of birth', 'help' => 'dd/mm/YYYY', 'format' => 'dd/MM/yyyy', 'widget' => 'text', 'required' => false])
            ->add('country', TextType::class, ['label' => 'Country', 'required' => false])
            ->add('likes', TextType::class, ['label' => 'Likes', 'required' => false])
            ->add('dislikes', TextType::class, ['label' => 'Dislikes', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Email', 'mapped' => false, 'required' => true])
            ->add('role', EntityType::class, ['class' => UserRoles::class, 'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('r')->orderBy('r.id', 'DESC');},
                                                            'choice_label' => 'Role', 'mapped' => false])
            ->add('submit', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
