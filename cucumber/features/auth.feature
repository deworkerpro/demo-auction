Feature: Login
  In order to check login
  As a guest user
  I want to be able to authenticate

  Scenario: View login button
    Given I am a guest user
    When I open "/" page
    Then I see "Auction" header
    And I see "join-link" element
    And I see "login-button" element
    And I do not see "logout-button" element

  Scenario: Login
    Given I am a guest user
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element

    When I fill "email" field with "user@app.test"
    And I fill "password" field with "password"
    And I click submit button
    Then I see "logout-button" element
    And I do not see "login-button" element
    And I do not see "join-link" element

  Scenario: Authenticated
    Given I am a user
    And I am on "/" page
    Then I see "logout-button" element
    And I do not see "login-button" element
    And I do not see "join-link" element

  Scenario: Logout
    Given I am a user
    And I am on "/" page
    When I click "logout-button" element
    Then I see "login-button" element
    And I do not see "logout-button" element
    And I see "join-link" element
