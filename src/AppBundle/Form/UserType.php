<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['facebook']) {
            $builder->add('username', TextType::class, ['label' => 'user.label.username']);
        }

        $builder->add('name', TextType::class, ['label' => 'user.label.name']);

        if (!$options['facebook']) {
            $builder->add('email', EmailType::class, ['label' => 'user.label.email']);
        }

        $builder
            ->add('phone', TelType::class, ['label' => 'user.label.phone'])
            ->add('location', null, ['label' => 'user.label.location'])
            ->add('locationsToMatch', null, ['label' => 'user.label.preferred']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'facebook' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }
}
