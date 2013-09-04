<?php

namespace BddSBP\ReaderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReaderType extends AbstractType
{

    public function getName()
    {
        return 'reader';
    }
   
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BddSBP\ReaderBundle\Entity\Reader',
            'validation_groups' => array('default', 'register')
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email',  array('label' => 'Email', 'attr' => array(
                'placeholder' => 'Email'
            )))->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The passwords doesn\'t match',
                'first_options'  => array('label' => 'Password','attr' => array('placeholder' => 'Password')),
                'second_options' => array('label' => 'Password Confirmation', 'attr' => array('placeholder' => 'Password Confirmation')),
                'required' => false
            ))->add('register', 'submit'
                    );

    }
}