<?php

namespace acceptance;

use AcceptanceTester;

class EditCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/login");
    $I->fillField("username", "vim@darkh.app");
    $I->fillField("password", "vim-my-beloved");
    $I->click("button");
  }

  public function editingProductWithoutChangingImage(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/edit?id=1");
    $I->fillField("name", "Test Product");
    $I->fillField("description", "Test Description");
    $I->fillField("price", "10.00");

    // Act
    $I->click("button");

    // Assert
    $I->see("Modifications enregistrées");
  }

  public function editingProductThatIsNotMine(AcceptanceTester $I)
  {
    // Act
    $I->amOnPage("/market/edit?id=2");

    // Assert
    $I->see("Vous n'avez pas");
  }

  public function editingProductWithEmptyFields(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/edit?id=1");
    $I->fillField("name", "");
    $I->fillField("description", "");
    $I->fillField("price", "");

    // Act
    $I->click("button");

    // Assert
    $I->see("Veuillez remplir tous les champs");
  }
}
