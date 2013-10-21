<?php

namespace BddSBP\ReaderBundle\Event;
use Symfony\Component\EventDispatcher\Event;
use BddSBP\ReaderBundle\Entity\Reader;
class ReaderEmailRegistrationEvent extends Event
{
    private $reader;
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function getReader()
    {
      return  $this->reader;
    }
}
