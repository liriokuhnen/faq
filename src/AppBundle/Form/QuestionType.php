<?php

// src/AppBundle/Form/QuestionType.php
namespace AppBundle\Form;

use AppBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questionName', TextareaType::class, 
                array('attr' => array('class' => 'form-control', 'placeholder' => 'Question'), 'label' => 'Question')
            )
            ->add('name', TextType::class,
                array('attr' => array('class' => 'form-control', 'placeholder' => 'Name'))
            )
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control', 'placeholder' => 'Email'))
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Question::class,
        ));
    }
}