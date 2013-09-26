<?php

namespace spec\BddSBP\ReaderBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BookSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BddSBP\ReaderBundle\Entity\Book');
    }
    function it_should_exist()
    {
        $this->shouldNotBe(null);
    }
    function it_should_have_primary_key_which_is_null()
    {
        $this->getId()->shouldReturn(null);
    }
    function its_title_is_mutable()
    {
        $this->setTitle("title");
        $this->getTitle()->shouldReturn("title");
    }
    function its_pages_is_mutable()
    {
        $this->setPages(123);
        $this->getPages()->shouldReturn(123);
    }
    function its_description_is_mutable()
    {
        $this->setDescription("description");
        $this->getDescription()->shouldReturn("description");
    }
    
    function its_author_is_mutable()
    {
        $this->setAuthor("author");
        $this->getAuthor()->shouldReturn("author");
    }
    
}
