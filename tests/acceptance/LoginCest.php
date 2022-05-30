<?php

namespace acceptance;

use AcceptanceTester;

class LoginCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/login");
  }

  // Login with valid credentials
  public function loginWithValidCredentials(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "admin");
    $I->fillField("password", "admin");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Welcome back, admin!");
  }

  // Login with invalid credentials
  public function loginWithInvalidCredentials(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "admin");
    $I->fillField("password", "wrong");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Invalid username or password.");
  }

  // Login with incorrect username
  public function loginWithIncorrectUsername(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "wrong");
    $I->fillField("password", "admin");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Invalid username or password.");
  }

  // Login with empty fields
  public function loginWithEmptyFields(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "");
    $I->fillField("password", "");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Please fill in all fields.");
  }
}
