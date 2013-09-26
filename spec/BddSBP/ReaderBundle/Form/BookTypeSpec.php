<?php

namespace spec\BddSBP\ReaderBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Form\BookType');
    }
    function it_is_form()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_has_reader_name()
    {
        $this->getName()->shouldReturn('book');
    }
    
    function it_is_setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'BddSBP\ReaderBundle\Entity\Book')

                )->shouldBeCalled();
        $this->setDefaultOptions($resolver);
    }
    
     function it_is_build_form_for_new_book(FormBuilder $builder)
    {
        $builder->add('title', 'text', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('author', 'text', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('pages', 'integer', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('description', 'textarea', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $this->buildForm($builder, []);
    }
}
