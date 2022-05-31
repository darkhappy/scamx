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
    $I->see("Le nom d'utilisateur est déjà pris");
  }

  public function registerWithEmptyFields(AcceptanceTester $I)
  {
    // Arrange

    // Act
    $I->click("button");

    // Assert
    $I->see("Veuillez remplir tous les champs");
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
    $I->see("L'adresse email est invalide");
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
    $I->see("Les mots de passe ne correspondent pas");
  }
}
