<?php

namespace utils;

use Codeception\Test\Unit;

class SecurityTest extends Unit
{
  public function testValidUsername(): void
  {
    $username = "username";
    $this->assertTrue(Security::isValidUsername($username));
  }

  /**
   * @dataProvider invalidUsernameProvider
   */
  public function testInvalidUsername($username): void
  {
    $this->assertFalse(Security::isValidUsername($username));
  }

  private function invalidUsernameProvider(): array
  {
    return [
      ["a"],
      ["🤪"],
      ["afjgoijadfoigjoiadfjgioadfjgioadfjgioadfjgioadjg"],
      ["  "],
      ["OR 1=1"],
      ["1=1"],
      [""],
    ];
  }
}
