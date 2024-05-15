Feature: Login external via Yandex

  Scenario: Guest Login
    Given I am a guest user
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I see "auth-external-yandex" element
    When I click "auth-external-yandex" element
    Then I see "Auth with Yandex"
    When I click "oauth-new" element
    Then I see "logout-button" element
