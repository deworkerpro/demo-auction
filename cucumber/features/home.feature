Feature: View home page
  In order to check home page content
  As a guest user
  I want to be able to view home page

  @smoke
  Scenario: View home page content
    Given I am a guest user
    When I open "/" page
    Then I see "Auction" header
    And I see "We are here"
    And I see "join-link" element

  Scenario: Click to Join
    Given I am a guest user
    And I am on "/" page
    When I click "join-link" element
    Then I see "Join to Us" header
