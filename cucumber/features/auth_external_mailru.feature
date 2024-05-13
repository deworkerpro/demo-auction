Feature: Login external via MailRu

  Scenario: Guest without feature
    Given I am a guest user
    And I have "!OAUTH_EXTERNAL, !OAUTH_EXTERNAL_MAILRU" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I do not see "auth-external" element
    And I do not see "auth-external-mailru" element

  Scenario: Guest without provider feature
    Given I am a guest user
    And I have "OAUTH_EXTERNAL, !OAUTH_EXTERNAL_MAILRU" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I do not see "auth-external-mailru" element

  Scenario: Guest
    Given I am a guest user
    And I have "OAUTH_EXTERNAL, OAUTH_EXTERNAL_MAILRU" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I see "auth-external-mailru" element
