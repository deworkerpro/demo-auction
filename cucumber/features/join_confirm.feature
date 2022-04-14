Feature: Join Confirm

  Background:
    Given I am a guest user

  Scenario: Success confirm
    When I open "/join/confirm?token=00000000-0000-0000-0000-200000000001" page
    Then I see "Success" header
    And I see "You are successfully joined!"

  Scenario: Expired confirm
    When I open "/join/confirm?token=00000000-0000-0000-0000-200000000002" page
    Then I see error "Token is expired."

  Scenario: Not valid confirm
    When I open "/join/confirm?token=00000000-0000-0000-0000-200000000003" page
    Then I see error "Incorrect token."
