Feature: View home page
  In order to check home page content
  As a guest user
  I want to be able to view home page

  @smoke
  Scenario: View home page content
    Given I am a guest user
    And I do not have "WE_ARE_HERE" feature
    When I open "/" page
    Then I see "Auction" header
    And I see "We will be here soon"
    And I do not see "We are here"

  Scenario: View new home page content
    Given I am a guest user
    And I have "WE_ARE_HERE" feature
    When I open "/" page
    Then I see "Auction" header
    And I do not see "We will be here soon"
    And I see "We are here"
