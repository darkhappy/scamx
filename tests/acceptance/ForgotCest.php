<?php

namespace acceptance;

use AcceptanceTester;

class ForgotCest
{
  public function _before(AcceptanceTester $I)
  {
    $I->amOnPage("/user/forgot");
  }

  public function sendForgotEmail(AcceptanceTester $I)
  {
    // Arrange
    $I->fillField("email", "test@darkhappy.ca");

    // Act
    $I->click("Button");

    // Assert
    $I->see("We've sent an email");
  }
}
