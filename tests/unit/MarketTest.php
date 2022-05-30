<?php

namespace unit;

use Codeception\Test\Unit;
use utils\Market;

class MarketTest extends Unit
{
  public function testCalculateTotal()
  {
    // Arrange
    $price = 100;
    $expected = 139.75;

    // Act
    $actual = Market::calculateTotal($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }

  public function testCalculateProfit()
  {
    // Arrange
    $price = 100;
    $expected = 95;

    // Act
    $actual = Market::calculateProfit($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }

  public function testCalculateTVQ()
  {
    // Arrange
    $price = 100;
    $expected = 9.75;

    // Act
    $actual = Market::calculateTVQ($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }

  public function testCalculateTPS()
  {
    // Arrange
    $price = 100;
    $expected = 5;

    // Act
    $actual = Market::calculateTPS($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }

  public function testCalculateShipping()
  {
    // Arrange
    $price = 100;
    $expected = 25;

    // Act
    $actual = Market::calculateShipping($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }

  public function testConvertToCurrency()
  {
    // Arrange
    $price = "100.00";
    $expected = 100;

    // Act
    $actual = Market::convertPriceToFloat($price);

    // Assert
    $this->assertEquals($expected, $actual);
  }
}
