Feature: View home page
  In order to check home page content
  As a guest user
  I want to be able to view home page

  @smoke
  Scenario: View home page content
    Given I am a guest user
    And I do not have "JOIN_TO_US" feature
    When I open "/" page
    Then I see "Auction" header
    And I see "We will be here soon"
    And I do not see "We are here"

  Scenario: View new home page content
    Given I am a guest user
    And I have "JOIN_TO_US" feature
    When I open "/" page
    Then I see "Auction" header
    And I do not see "We will be here soon"
    And I see "We are here"
    And I see "join-link" element

  Scenario: Click to Join
    Given I am a guest user
    And I have "JOIN_TO_US" feature
    And I am on "/" page
    When I click "join-link" element
    Then I see "Join to Us" header
