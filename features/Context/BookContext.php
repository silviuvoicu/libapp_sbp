<?php
namespace Context;

use Behat\Behat\Exception\PendingException;
use BddSBP\ReaderBundle\Entity\Book;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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
    
     /**
     * @When /^I go to new book page$/
     */
    public function iGoToNewBookPage()
    {
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_new'));
    }

    /**
     * @Then /^I should be redirected to access denied page$/
     */
    public function iShouldBeRedirectedToAccessDeniedPage()
    {
        $this->getMainContext()->getSubcontext('mink')->assertPageAddress('access_denied');
    }
    
     /**
     * @Given /^I am the owner of "([^"]*)" book$/
     */
    public function iAmTheOwnerOfBook($title)
    {
        $this->getMainContext()->getSubcontext('reader_registration')->iAmAReader("rem@email.com");
        $em= $this->getEntityManager();
        $reader = $em->getRepository("ReaderBundle:Reader")->findOneByEmail("rem@email.com");
        $book = new Book();
        $book->setTitle($title);
        $book->setPages(150);
        $book->setAuthor('William Shakespeare');
        $book->setDescription('Excellent play!');
        $book->setReader($reader);
        
       
        $em->persist($book);
        $em->flush();
        
         $aclProvider = $this->getService('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($book);
        $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            
        $securityIdentity = UserSecurityIdentity::fromAccount($reader);

            // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);
        
        
    }

    /**
     * @When /^I change "([^"]*)" book title to "([^"]*)"$/
     */
    public function iChangeBookTitle1ToTitle2($title1, $title2)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title1);
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_edit',array('id'=>$book->getId())));
        $this->getMainContext()->getSubcontext('mink')->fillField("book_title", $title2);
        $this->getMainContext()->getSubcontext('mink')->pressButton("Update Book"); 
    }

    /**
     * @When /^I delete "([^"]*)" book$/
     */
    public function iDeleteBook($title)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title);
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_edit',array('id'=>$book->getId())));
        $this->getMainContext()->getSubcontext('mink')->pressButton("Delete");
    }
    
     /**
     * @Given /^"([^"]*)" reader should be the owner of this book$/
     */
    public function readerShouldBeTheOwnerOfThisBook($email)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle("The Hamlet");
        $reader = $em->getRepository("ReaderBundle:Reader")->findOneByEmail($email);
        assertEquals($book->getReader(),$reader,"The reader $reader is not the owner of the book $book");
    }

   
     /**
     * @Given /^I am not the owner of "([^"]*)" book$/
     */
    public function iAmNotTheOwnerOfBook($title)
    {
        $this->getMainContext()->getSubcontext('reader_registration')->iAmAReader("rem@email.com");
        $bob = $this->getMainContext()->getSubcontext('reader_registration')->readerWithExists("bob@email.com");
        $em= $this->getEntityManager();
        $book = new Book();
        $book->setTitle($title);
        $book->setPages(150);
        $book->setAuthor('William Shakespeare');
        $book->setDescription('Excellent play!');
        $book->setReader($bob);
              
        $em->persist($book);
        $em->flush();
        
        $aclProvider = $this->getService('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($book);
        $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            
        $securityIdentity = UserSecurityIdentity::fromAccount($bob);

            // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);
        
    }

    /**
     * @When /^I go to edit "([^"]*)" book page$/
     */
    public function iGoToEditBookPage($title)
    {
        $em= $this->getEntityManager();
        $book = $em->getRepository("ReaderBundle:Book")->findOneByTitle($title);
        $this->getMainContext()->getSubcontext('mink')->visit($this->getMainContext()->getSubcontext('mink')->getMinkParameter("base_url").$this->generateUrl('book_edit',array('id'=>$book->getId())));
    }

}
