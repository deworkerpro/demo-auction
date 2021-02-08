Feature: View join page

  @wip
  Scenario: View join page without feature
    Given I am a guest user
    And I do not have "JOIN_TO_US" feature
    When I open "/join" page
    Then I see "Page is not found"

  @wip
  Scenario: View join page
    Given I am a guest user
    And I have "JOIN_TO_US" feature
    When I open "/join" page
    Then I see "Join to Us" header
