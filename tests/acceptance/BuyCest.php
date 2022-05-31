<?php

namespace acceptance;

use AcceptanceTester;

class BuyCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/login");
    $I->fillField("username", "vim@darkh.app");
    $I->fillField("password", "vim-my-beloved");
    $I->click("button");
  }

  public function buyingAProduct(AcceptanceTester $I)
  {
    // Arrange
    $I->amOnPage("/market/buy?id=2");
    $I->fillField("name", "John");
    $I->fillField("address", "123 Main St");
    $I->fillField("city", "Anytown");
    $I->fillfield("postal", "H0H0H0");
    $I->fillField("ccname", "John Doe");
    $I->fillField("cardnumber", "4242424242424242");
    $I->fillField("expmonth", "12");
    $I->fillField("expyear", "2024");
    $I->fillField("cvc2", "123");

    // Act
    $I->click("acheter");

    // Assert
    $I->see("Transaction complete");
  }

  public function buyingANonExistantProduct(AcceptanceTester $I)
  {
    // Act
    $I->amOnPage("/market/buy?id=0");

    // Assert
    $I->see("Cet objet n'existe pas");
  }

  public function buyingAProductThatIsMine(AcceptanceTester $I)
  {
    // Act
    $I->amOnPage("/market/buy?id=1");

    // Assert
    $I->see("Cet objet n'est plus disponible");
  }
}
