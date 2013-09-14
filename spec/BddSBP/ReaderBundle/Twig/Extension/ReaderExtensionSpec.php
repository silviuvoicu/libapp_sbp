<?php

namespace spec\BddSBP\ReaderBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ReaderExtensionSpec extends ObjectBehavior
{
    function let(SecurityContext $securityContext, Router $router)
    {
        $this->beConstructedWith($securityContext,$router);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Twig\Extension\ReaderExtension');
    }
    
    function it_should_extend_twigExtension()
    {
        $this->shouldBeAnInstanceOf('\Twig_Extension');
    }
    
   function it_has_Register_link_if_reader_is_not_logged_in(SecurityContext $securityContext, TokenInterface $token)
   {
       $securityContext->getToken()->willReturn($token);
       $token->getUser()->willReturn('anon.');
       $this->topmenu()->shouldBeString('Register');
   } 
   
   function it_has_welcome_message_if_reader_is_logged_in(SecurityContext $securityContext, TokenInterface $token)
   {
       $reader = '';
       $securityContext->getToken()->willReturn($token);
       $token->getUser()->willReturn($reader);
       $this->topmenu()->shouldBeString("Welcome, $reader");
   }
}
