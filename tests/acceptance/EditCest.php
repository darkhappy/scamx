<?php

namespace acceptance;

use AcceptanceTester;

class EditCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/login");
    $I->fillField("username", "admin");
    $I->fillField("password", "admin");
    $I->click("button");
  }

  public function editingProductWithoutChangingImage(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/edit?id=22");
    $I->fillField("name", "Test Product");
    $I->fillField("description", "Test Description");
    $I->fillField("price", "10.00");

    // Act
    $I->click("button");

    // Assert
    $I->see("Item edited successfully");
  }

  public function editingProductThatIsNotMine(AcceptanceTester $I)
  {
    // Act
    $I->amOnPage("/market/edit?id=1");

    // Assert
    $I->see("You are not allowed");
  }

  public function editingProductWithEmptyFields(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/edit?id=22");
    $I->fillField("name", "");
    $I->fillField("description", "");
    $I->fillField("price", "");

    // Act
    $I->click("button");

    // Assert
    $I->see("Please fill in all fields");
  }
}
