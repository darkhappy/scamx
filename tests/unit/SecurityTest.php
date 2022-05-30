<?php

namespace unit;

use Codeception\Test\Unit;
use utils\Security;

class SecurityTest extends Unit
{
  /**
   * @dataProvider validUsernameProvivder
   */
  public function testIsValidUsername($username)
  {
    // Act
    $result = Security::isValidUsername($username);

    // Assert
    $this->assertTrue($result);
  }

  /**
   * @dataProvider invalidUsernameProvider
   */
  public function testInvalidUsername($username)
  {
    // Act
    $result = Security::isValidUsername($username);

    // Assert
    $this->assertFalse($result);
  }

  public function validUsernameProvivder(): array
  {
    return [["aaa"], ["hello.there"], ["name-with-dash"]];
  }

  public function invalidUsernameProvider(): array
  {
    return [
      [""],
      ["-aaa-"],
      ["a"],
      ["aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"],
      ["💀"],
      ["test@test"],
      ["test username"],
    ];
  }
}
