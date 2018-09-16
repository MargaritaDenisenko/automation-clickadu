Feature: Registration page
  In order to check country from blacklist
  As an user
  I need to be able to try to create new user using country from blacklist

  @javascript
  @api
  Scenario: Try to create new account using country from blacklist
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    When I choose account type "Individual"
    And I fill in the following:
      | firstName       | Kim                   |
      | lastName        | Jong-un               |
      | _value          | North Korea           |
      | city            | Pyongyang             |
      | address         | Sanwon St, 4          |
      | phoneNumber     | 85023817980           |
      | nickname        | presidentofnorthkorea |
    And I generate random email
    And I set generated email
    And I click the button "signupSubmit"
    And I click on information icon in the red field
    Then I should see text matching "Unfortunately our service isn't available in your country"