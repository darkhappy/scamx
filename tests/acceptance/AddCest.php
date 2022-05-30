<?php

namespace acceptance;

use AcceptanceTester;

class AddCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/login");
    $I->fillField("username", "admin");
    $I->fillField("password", "admin");
    $I->click("button");
  }

  public function addingAProductWithInvalidImage(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/add");
    $I->fillField("name", "Test Product");
    $I->fillField("description", "Test Description");
    $I->fillField("price", "10.00");
    $I->attachFile("image", "invalid.txt");

    // Act
    $I->click("button");

    // Assert
    $I->see("Please upload a valid image");
  }

  public function addingAProductWithoutAnyFields(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/add");

    // Act
    $I->click("button");

    // Assert
    $I->see("Please fill in all fields");
  }
}
