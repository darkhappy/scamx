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
    $I->fillField("username", "vim@darkh.app");
    $I->fillField("password", "vim-my-beloved");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Bonjour admin !");
  }

  // Login with invalid credentials
  public function loginWithInvalidCredentials(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "vim@darkh.app");
    $I->fillField("password", "vim-is-not-my-beloved");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Le nom d'utilisateur ou le mot de passe est incorrect.");
  }

  // Login with incorrect username
  public function loginWithIncorrectUsername(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("username", "vim");
    $I->fillField("password", "vim-my-beloved");

    // Act
    $I->click("Button");

    // Assert
    $I->see("Le nom d'utilisateur ou le mot de passe est incorrect.");
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
    $I->see("Veuillez remplir tous les champs.");
  }
}
