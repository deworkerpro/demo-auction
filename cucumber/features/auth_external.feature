Feature: Login external

  Scenario: Guest without feature
    Given I am a guest user
    And I do not have "OAUTH_EXTERNAL" authorize feature
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I do not see "auth-external" element

  Scenario: Guest
    Given I am a guest user
    And I have "OAUTH_EXTERNAL" authorize feature
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
