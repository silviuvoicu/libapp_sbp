<?php

namespace spec\BddSBP\ReaderBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Swift_Mailer as Mailer;

class ReaderEmailRegistrationListnerSpec extends ObjectBehavior
{
    
    function let(Mailer $mailer)
    {
        $this->beConstructedWith($mailer);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\EventListener\ReaderEmailRegistrationListner');
    }
    
    
}
