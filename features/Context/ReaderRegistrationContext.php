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
           $this->getSession()->visit($this->getMinkParameter("base_url").$this->generateUrl('register'));
           $this->fillField("reader_email", "reader01@email.com");
           $this->fillField("reader_password", "pass");
           $this->fillField("reader_password_confirmation", "pass");
           $this->pressButton("Register");

      
    }

    /**
     * @Then /^I should be registered in application$/
     */
    public function iShouldBeRegisteredInApplication()
    {
        throw new PendingException();
    }

    /**
     * @Given /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        throw new PendingException();
    }


    
}

?>
