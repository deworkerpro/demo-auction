Feature: View join page

  Scenario: View join page
    Given I am a guest user
    When I open "/join" page
    Then I see "Join to Us" header
    And I see "join-form" element

  Scenario: Success join
    Given I am a guest user
    And I am on "/join" page
    When I fill "email" field with "join-new@app.test"
    And I fill "password" field with "new-password"
    And I check "agree" checkbox
    And I click submit button
    Then I see success "Confirm join by link in email."

  Scenario: Existing join
    Given I am a guest user
    And I am on "/join" page
    When I fill "email" field with "join-existing@app.test"
    And I fill "password" field with "new-password"
    And I check "agree" checkbox
    And I click submit button
    Then I see error "User already exists."

  Scenario: Not valid join
    Given I am a guest user
    And I am on "/join" page
    When I fill "email" field with "join-not-valid@app.test"
    And I fill "password" field with "new"
    And I check "agree" checkbox
    And I click submit button
    Then I see validation error "This value is too short"
