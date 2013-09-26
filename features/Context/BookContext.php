<?php
namespace Context;

use Behat\Behat\Exception\PendingException;
use BddSBP\ReaderBundle\Entity\Book;


require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
 
class BookContext extends BaseContext
{
    
    private $book;
    
    /**
     * @When /^I fill the new book form with valid data$/
     */
    public function iFillTheNewBookFormWithValidData()
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_new'));
        $this->getMainContext()->getSubcontext('mink')->fillField("book_title", "The Hamlet");
        $this->getMainContext()->getSubcontext('mink')->fillField("book_pages", "150");
        $this->getMainContext()->getSubcontext('mink')->fillField("book_author", "William Shakespeare");
        $this->getMainContext()->getSubcontext('mink')->fillField("book_description", "Excellent play!");
        $this->getMainContext()->getSubcontext('mink')->pressButton("Create");
    }

    /**
     * @Then /^the book should be added to database$/
     */
    public function theBookShouldBeAddedToDatabase()
    {
       $em= $this->getEntityManager();
       $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle("The Hamlet");
       assertNotNull($book,"Book wasn't added into your library ");
    }

    /**
     * @Given /^I should see it on library page$/
     */
    public function iShouldSeeItOnLibraryPage()
    {
        $this->getMainContext()->getSubcontext('mink')->assertPageContainsText("The Hamlet");
    }

    /**
     * @When /^I fill the new book form with empty title$/
     */
    public function iFillTheNewBookFormWithEmptyTitle()
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_new'));
        $this->getMainContext()->getSubcontext('mink')->pressButton("Create");
    }
    

    /**
     * @Then /^the book should not be added to database$/
     */
    public function theBookShouldNotBeAddedToDatabase()
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle("");
        assertNull($book,"Book is still in database");
    }

    /**
     * @Given /^I should see the new book form with error message$/
     */
    public function iShouldSeeTheNewBookFormWithErrorMessage()
    {
         $this->getMainContext()->getSubcontext('mink')->assertElementOnPage('form#book_new');
          $this->getMainContext()->getSubcontext('mink')->assertElementOnPage('.alert-error');
    }

    /**
     * @Given /^book "([^"]*)" exists$/
     */
    public function bookExists($title)
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setPages(150);
        $book->setAuthor('William Shakespeare');
        $book->setDescription('Excellent play!');
        $em= $this->getEntityManager();
        $em->persist($book);
        $em->flush();
        $this->book = $book;
        
    }

    /**
     * @When /^I go to "([^"]*)" book page$/
     */
    public function iGoToBookPage($title)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title);
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_show',array('id'=>$book->getId())));
    }

    /**
     * @Then /^I should see "([^"]*)" book details$/
     */
    public function iShouldSeeBookDetails($title)
    {
        $this->getMainContext()->getSubcontext('mink')->assertPageContainsText($title);
    }

    /**
     * @Given /^I should see "([^"]*)" book edit link$/
     */
    public function iShouldSeeBookEditLink($title)
    {
         assertNotNull($this->getMainContext()->getSubcontext('mink')->getMink()->getSession()->getPage()->findLink("Edit"),"Edit link not found") ;
    }

    /**
     * @When /^I go to nonexistent book page$/
     */
    public function iGoToNonexistentBookPage()
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_show',array('id'=>0)));
    }

    /**
     * @Then /^I should be redirected to library page$/
     */
    public function iShouldBeRedirectedToLibraryPage()
    {
        $this->getMainContext()->getSubcontext('mink')->assertHomepage();
    }

    /**
     * @Given /^I should see "([^"]*)" error message$/
     */
    public function iShouldSeeErrorMessage($message)
    {
        $this->getMainContext()->getSubcontext('mink')->assertPageContainsText($message);
    }

    /**
     * @When /^I change book title to "([^"]*)"$/
     */
    public function iChangeBookTitleTo($title)
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_edit',array('id'=>$this->book->getId())));
        $this->getMainContext()->getSubcontext('mink')->fillField("book_title", $title);
        $this->getMainContext()->getSubcontext('mink')->pressButton("Update Book");        
    }

    /**
     * @Then /^book "([^"]*)" should not exist in database$/
     */
    public function bookShouldNotExistInDatabase($title)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title);
        assertNull($book,"Book is still in database");
    }

    /**
     * @Given /^book "([^"]*)" should exist in database$/
     */
    public function bookShouldExistInDatabase($title)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title);
        assertNotNull($book,"Book is not in the database");
    }

    /**
     * @Given /^I should see "([^"]*)" book on library page$/
     */
    public function iShouldSeeBookOnLibraryPage($title)
    {
        $this->getMainContext()->getSubcontext('mink')->assertPageContainsText($title);
    }

    /**
     * @When /^I delete it$/
     */
    public function iDeleteIt()
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_edit',array('id'=>$this->book->getId())));
        $this->getMainContext()->getSubcontext('mink')->pressButton("Delete"); 
    }

    /**
     * @Given /^I should not see "([^"]*)" book on library page$/
     */
    public function iShouldNotSeeBookOnLibraryPage($title)
    {
         $this->getMainContext()->getSubcontext('mink')->assertPageNotContainsText($title);
    }

}
