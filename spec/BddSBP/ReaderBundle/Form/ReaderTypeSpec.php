<?php

namespace spec\BddSBP\ReaderBundle\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReaderTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Form\ReaderType');
    }
    function it_is_form()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_has_reader_name()
    {
        $this->getName()->shouldReturn('reader');
    }
    
    function it_is_setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => Argument::any(),
                      'validation_groups' => Argument::cetera()
                ));
    }
    
    function it_is_build_form_with_email_and_password(FormBuilder $builder)
    {
        $builder->add('email', 'email', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('password', 'repeated', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->add('register', 'submit', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $this->buildForm($builder, []);
    }
    
    
}
