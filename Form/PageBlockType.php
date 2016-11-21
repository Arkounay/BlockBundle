<?php

namespace Arkounay\BlockBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageBlockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'disabled' => !$options['editable']
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arkounay\BlockBundle\Entity\PageBlock',
            'editable' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arkounay_pageblock';
    }
}
