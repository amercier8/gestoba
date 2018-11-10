<?php

namespace OC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('email', TextType::class, array (
                'label' => 'Mail',
            ))
            ->add('apiKey', TextType::class, array(
                'required' => false,
                'label' => 'Clé API',
            ))
            ->add('enabled', CheckboxType::class, array(
                'required' => false,
                'label' => 'Activé',
                ))
            ->add('roles', ChoiceType::class, array (
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Vendeur' => 'ROLE_VENDOR'
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => false
            ))
        ;
            
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_userbundle_user';
    }


}
