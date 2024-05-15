Feature: Login external via MailRu

  Scenario: Guest Login
    Given I am a guest user
    And I am on "/" page
    When I click "login-button" element
    Then I see "authorize-page" element
    And I see "auth-external" element
    And I see "auth-external-mailru" element
    When I click "auth-external-mailru" element
    Then I see "Auth with MailRu"
    When I click "oauth-new" element
    Then I see "logout-button" element
