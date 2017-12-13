<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Count;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('value')
            ->add('description')
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'title',
                'placeholder' => '- Choose a category -',
            ))
            ->add('categoriesToMatch', EntityType::class, array(
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'title',
                'multiple' => true,
                'constraints' => [new Count(['max' => 3])],
            ))
            ->add('file', VichImageType::class, array(
                'download_uri' => false,
                'image_uri' => true,
            ));
//            ->add('images', CollectionType::class, array(
//                'entry_type' => ImageType::class,
//                'allow_add' => true,
//                'by_reference' => false,
//                'entry_options' => array(
//                    'label' => false,
//                ),
//            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Item'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_item';
    }
}
