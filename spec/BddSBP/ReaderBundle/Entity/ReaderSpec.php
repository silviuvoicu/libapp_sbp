<?php

namespace spec\BddSBP\ReaderBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Entity\Reader');
    }
    
     function it_should_exist()
    {
        $this->shouldNotBe(null);
    }
     function it_should_have_primary_key_which_is_null()
    {
        $this->getId()->shouldReturn(null);
    }
    
    function its_mail_is_mutable()
    {
        $this->setEmail('email@email.com');
        $this->getEmail()->shouldReturn('email@email.com');
    }
    
        
    function its_password_is_mutable()
    {
        $this->setPassword('pass');
        $this->getPassword()->shouldReturn('pass');
    }
    
//    function its_salt_is_mutable()
//    {
//        $this->setSalt('salt');
//        $this->getSalt()->shouldReturn('salt');
//    }
}
