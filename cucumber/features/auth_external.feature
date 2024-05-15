Feature: Login external

  Scenario: Guest
    Given I am a guest user
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
