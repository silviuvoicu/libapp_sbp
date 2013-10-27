<?php

namespace BddSBP\ReaderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{

    public function getName()
    {
        return 'book';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BddSBP\ReaderBundle\Entity\Book'

        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title','text',array('label' => 'Title', 'attr' => array(
                'placeholder' => 'Title of the book'
            )))->add('author','text',array('label' => 'Author', 'attr' => array(
                'placeholder' => 'Author'
            )))->add('pages','integer',array('label' => 'Pages')
              )->add('image','file',array('label'=>'Cover','required' => false,'data_class' => null))
                ->add('description','textarea',array('label' => 'Description', 'attr' => array(
                'placeholder' => 'Book description','rows'=>20,'cols' => 40  
            )));
        //->add('create_book', 'submit',array('label' => 'Create Book','attr' => array('class' => 'btn btn-primary')));
    }        
}
