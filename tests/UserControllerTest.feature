Feature: Login
  In order to view content
  As a registered user
  I need to login

  Scenario: Valid Credentials
    Given I am on the login page
    And I fill in the login form with the following:
      | username | admin |
      | password | admin |
    When I press the login button
    Then I should see the message "Welcome back"

  Scenario: Wrong Username
    Given I am on the login page
    And I fill in the login form with the following:
      | username | wrong |
      | password | admin |
    When I press the login button
    Then I should see the message "Invalid username or password"

  Scenario: Wrong Password
    Given I am on the login page
    And I fill in the login form with the following:
      | username | admin |
      | password | wrong |
    When I press the login button
    Then I should see the message "Invalid username or password"

  Scenario: Empty Username
    Given I am on the login page
    And I fill in the login form with the following:
      | username |       |
      | password | admin |
    When I press the login button
    Then I should see the message "Fill in all fields"

  Scenario: Empty Password
    Given I am on the login page
    And I fill in the login form with the following:
      | username | admin |
      | password |       |
    When I press the login button
    Then I should see the message "Fill in all fields"

  Scenario: Unverified User
    Given I am on the login page
    And I fill in the login form with the following:
      | username | user |
      | password | user |
    When I press the login button
    Then I should see the message "not verified"

