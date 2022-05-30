<?php

namespace acceptance;

use AcceptanceTester;

class RegisterCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/register");
  }

  public function registerWithAlreadyTakenUsername(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "admin");
    $I->fillField("email", "test@test.ca");
    $I->fillField("password", "password");
    $I->fillField("confirm", "password");

    // Act
    $I->click("button");

    // Assert
    $I->see("Username is already taken");
  }

  public function registerWithEmptyFields(AcceptanceTester $I)
  {
    // Arrange

    // Act
    $I->click("button");

    // Assert
    $I->see("Fill in all fields");
  }

  public function registerWithInvalidEmail(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "test");
    $I->fillField("email", "test");
    $I->fillField("password", "password");
    $I->fillField("confirm", "password");

    // Act
    $I->click("button");

    // Assert
    $I->see("Please enter a valid email");
  }

  public function registerWithInvalidPassword(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "test");
    $I->fillField("email", "test@test.test");
    $I->fillField("password", "pass");
    $I->fillField("confirm", "password");

    // Act
    $I->click("button");

    // Assert
    $I->see("Passwords do not match");
  }
}
