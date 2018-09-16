Feature: Registration page
  In order to register new user on Clickadu
  As an user
  I need to be able to fill out all required fields

  @javascript
  @api
  Scenario: Try to create new account using country from blacklist
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    And I click the button "signupSubmit"
    Then I should see "This value is not valid."
    And I see error in "firstName" field
    And I see error in "lastName" field
    And I see error in "residenceCountry" field
    And I see error in "city" field
    And I see error in "address" field
    And I see error in "email" field
    And I see error in "nickname" field
