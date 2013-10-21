Feature: Reader Registration

   To register in application reader goes to register form, which consists only from email, password and confirm password firlds and a button. After registration reader is logged in and ready to work with application.

#  @javascript
  Scenario: Reader registers successfully via register form
      Given I am a guest
      When I do not follow redirects
      And I fill the register form with valid data
      Then I should be registered in application
      And I should receive welcome email
      And I should be redirected to "/"
      And I should be logged in
       

   Scenario: Reader tries to register with invalid data
      Given I am a guest
      When I fill the register form with invalid data
      Then I should see the register form again
      And I should not be registered in application