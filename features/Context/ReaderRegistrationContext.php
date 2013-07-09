gi<?php
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
        throw new PendingException();
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
