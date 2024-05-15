Feature: Join Auth via Yandex

  Scenario: Auth
    Given I am a guest user
    And I am on "/join" page
    When I click "auth-external-yandex" element
    Then I see "Auth with Yandex"
    When I click "oauth-new" element
    Then I see "logout-button" element
