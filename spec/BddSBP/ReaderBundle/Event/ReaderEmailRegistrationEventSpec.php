<?php

namespace spec\BddSBP\ReaderBundle\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use BddSBP\ReaderBundle\Entity\Reader;

class ReaderEmailRegistrationEventSpec extends ObjectBehavior
{
    private $reader;
    function let(Reader $reader) 
    {
        $this->beConstructedWith($reader);
        $this->reader = $reader;
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Event\ReaderEmailRegistrationEvent');
    }
    
    function it_should_be_an_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
    }
    
    function it_should_getReader()
    {
        $this->getReader()->shouldReturn($this->reader);
    }
}
