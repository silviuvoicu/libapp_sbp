<?php
namespace Context;

use Behat\Behat\Exception\PendingException;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class ReaderRegistrationContext extends BaseContext
{
    
      /**
     * @Given /^I am a guest$/
     */
    public function iAmAGuest()
    {
       
    }

    /**
     * @When /^I fill the register form with valid data$/
     */
    public function iFillTheRegisterFormWithValidData()
    {
           $this->visit($this->getMinkParameter("base_url").$this->generateUrl('register'));
           $this->fillField("reader_email", "reader01@email.com");
           $this->fillField("reader_password_first", "password");
           $this->fillField("reader_password_second", "password");
           $this->pressButton("reader_register");

      
    }

    /**
     * @Then /^I should be registered in application$/
     */
    public function iShouldBeRegisteredInApplication()
    {
       $em= $this->getEntityManager();
       $reader = $em->getRepository("ReaderBundle:Reader")->findOneByEmail("reader01@email.com");
       assertNotNull($reader,"Reader was't register in ");
    }

    /**
     * @Given /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        $this->assertPageContainsText("Welcome, reader01@email.com");
    }

    /**
     * @When /^I fill the register form with invalid data$/
     */
    public function iFillTheRegisterFormWithInvalidData()
    {
           $this->visit($this->getMinkParameter("base_url").$this->generateUrl('register'));
           $this->fillField("reader_email", "");
           $this->fillField("reader_password_first", "passdhhdhdd");
           $this->fillField("reader_password_second", "pass");
           $this->pressButton("reader_register");
    }

    /**
     * @Then /^I should see the register form again$/
     */
    public function iShouldSeeTheRegisterFormAgain()
    {
        $this->assertElementOnPage('form#new_reader');
    }

    /**
     * @Given /^I should not be registered in application$/
     */
    public function iShouldNotBeRegisteredInApplication()
    {
        $em= $this->getEntityManager();
        $reader = $em->getRepository("ReaderBundle:Reader")->findOneByEmail("reader");
        assertNull($reader);
    }

     /**
     * @When /^I go to home page$/
     */
    public function iGoToHomePage()
    {
        $this->visit($this->getMinkParameter("base_url").$this->generateUrl('home'));
    }

    /**
     * @Then /^I should see guest menu$/
     */
    public function iShouldSeeGuestMenu()
    {
        $this->assertElementOnPage('#top-menu');
      
       assertNotNull($this->getMink()->getSession()->getPage()->findLink("Register"),"Register link not found") ;
    }

}

?>
