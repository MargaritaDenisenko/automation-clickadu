Feature: Registration page
  In order to register new user using EU country
  As an user
  I need to be able to see and fill out VAT ID field

  @javascript
  @api
  Scenario: Find and fill out VAT ID field
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    When I choose account type "Individual"
    And I fill in the following:
      | firstName       | Josef                           |
      | lastName        | Sveik                           |
      | _value          | Czech Republic                  |
      | city            | Prague                          |
      | address         | Ujezd 423/20, 118 00 Mala Strana|
      | phoneNumber     | 420257313244                    |
      | nickname        | good_soldier                    |
    And I generate random email
    And I set generated email
    And I should see "I have a VAT ID"
    And I check the "I have a VAT ID" box
    Then I should see "European VAT ID"
    When I fill in the following:
      | vatId           | CZ03177084                      |
    And I click the button "signupSubmit"
    And I wait "4" seconds
    Then I should see "Planned test budget"