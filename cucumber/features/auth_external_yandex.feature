Feature: Login external via Yandex

  Scenario: Guest without feature
    Given I am a guest user
    And I have "!OAUTH_EXTERNAL, !OAUTH_EXTERNAL_YANDEX" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I do not see "auth-external" element
    And I do not see "auth-external-yandex" element

  Scenario: Guest without provider feature
    Given I am a guest user
    And I have "OAUTH_EXTERNAL, !OAUTH_EXTERNAL_YANDEX" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I do not see "auth-external-yandex" element

  Scenario: Guest Login
    Given I am a guest user
    And I have "OAUTH_EXTERNAL, OAUTH_EXTERNAL_YANDEX" authorize features
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I see "auth-external-yandex" element
    When I click "auth-external-yandex" element
    Then I see "Auth with Yandex"
