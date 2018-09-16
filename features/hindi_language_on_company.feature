Feature: Registration page
  In order to check important fields
  As an user
  I need to be able to try enter hindi symbols in the fields

  @javascript
  @api
  Scenario: Try to enter hindi symbols in the fields
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    When I choose account type "Company"
    And I fill in the following:
      | companyName     | यात्रा का                |
      | firstName       | मार्गरेट                  |
      | lastName        | गरेट                   |
      | _value          | Nepal                 |
      | city            | काठमाडौं                |
      | address         | टिळक नगर बास्केट बॉल ग्राउंड |
      | email           | नगर                   |
      | nickname        | माडौं                   |
    And I click the button "signupSubmit"
    Then I see error in "companyName" field
    And I see error in "firstName" field
    And I see error in "lastName" field
    And I see error in "city" field
    And I see error in "address" field
    And I see error in "email" field
    And I see error in "nickname" field
