Feature: Registration page
  In order to register on Clickadu
  As an user
  I need to be able to create new user

  @javascript
  @api
  Scenario: Create Individual account
    Given I am on "#/app/auth/signUp"
    And I wait "1" seconds
    When I choose account type "Individual"
    And I fill in the following:
      | firstName       | Margarita             |
      | lastName        | Denisenko             |
      | _value          | Russian Federation    |
      | city            | Pskov                 |
      | address         | ul. Gogolya, d.8a     |
      | phoneNumber     | 9992112803            |
      | nickname        | mdenisenko            |
    And I generate random email
    And I set generated email
    And I select "ICQ" from "messengerInputType"
    And I click the button "signupSubmit"
    Then I should see "Planned test budget"
    And I fill in the following:
      | companyBudget   | 150                    |
      | Start typing... | Ukraine, Tanzania      |
      | website         | https://phpprofi.ru/   |
      | companyDesc     | This is a testing text |
      | bonusCode       | 67653                  |
    And I check the "Mainstream" box
    And I check the "Banners" box
    And I check the "I agree to receive special offers..." box
    And I check the "I agree with my data processing..." box
    And I click the button "signupAction"
    And I wait "3" seconds
    Then I should see "Please activate your account"
    #Check e-mail inbox and following verification link
    And I am on "https://mailgutter.com/"
    And I check mailbox at mailgutter and follow link
    And I fill in "newPasswordPassword" with "A123456#"
    And I fill in "newPasswordConfirmation" with "A123456#"
    And I press "Finish and log in"
    And I wait "2" seconds
    Then I should see "Add Funds"