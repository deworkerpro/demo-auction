Feature: API

  @smoke
  Scenario: Open api proxy
    Given I open "/api" page
    Then I see "{}"
