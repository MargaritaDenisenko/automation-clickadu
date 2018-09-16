Feature: Registration page
  In order to check important fields
  As an user
  I need to be able to try enter chinese symbols in the fields

  @javascript
  @api
  Scenario: Try to enter chinese symbols in the fields
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    When I choose account type "Individual"
    And I fill in the following:
      | firstName       | 玛格丽特                   |
      | lastName        | 麗塔                      |
      | _value          | Taiwan Province Of China  |
      | city            | 北京                      |
      | address         | 北京市西城区金融街          |
      | email           | 区金融街                   |
      | nickname        | 西苑饭店                   |
    And I click the button "signupSubmit"
    Then I see error in "firstName" field
    And I see error in "lastName" field
    And I see error in "city" field
    And I see error in "address" field
    And I see error in "email" field
    And I see error in "nickname" field
