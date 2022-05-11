Feature: Register
  In order to register
  As a visitor
  I need to register

  Background:
    Given I am on the register page
    And the test user does not exist

  Scenario: Valid Registration
    Given I fill in the registration form with the following:
      | username | testuser         |
      | password | password         |
      | confirm  | password         |
      | email    | test@example.com |
    When I submit the form
    Then I should see the message "Registration successful"

  Scenario Outline: Empty fields
    Given I fill in the registration form with the following:
      | username | <username> |
      | password | <password> |
      | confirm  | <confirm>  |
      | email    | <email>    |
    When I submit the form
    Then I should see the message "Fill in all fields"
    Examples:
      | username | password | confirm | email            |
      |          | password | confirm | test@example.com |
      | testUser |          | confirm | test@example.com |
      | testUser | password |         | test@example.com |
      | testUser | password | confirm |                  |

  Scenario: Password Mismatch
    Given I fill in the registration form with the following:
      | username | testUser         |
      | password | password         |
      | confirm  | wrong            |
      | email    | test@example.com |
    When I submit the form
    Then I should see the message "Passwords do not match"

  Scenario: Invalid Username
    Given I fill in the registration form with the following:
      | username | a                |
      | password | password         |
      | confirm  | password         |
      | email    | test@example.com |
    When I submit the form
    Then I should see the message "Username is invalid"

  Scenario: Invalid Email
    Given I fill in the registration form with the following:
      | username | testUser     |
      | password | password     |
      | confirm  | password     |
      | email    | test@example |
    When I submit the form
    Then I should see the message "Email is invalid"
