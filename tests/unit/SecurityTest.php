<?php

namespace unit;

use Codeception\Test\Unit;
use models\Item;
use models\User;
use repositories\ItemRepository;
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

  public function validUsernameProvivder(): array
  {
    return [["aaa"], ["hello.there"], ["name-with-dash"]];
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

  public function testOwnsItem()
  {
    // Arrange
    $user = new User();
    $user->setId(1);

    $_SESSION["user"] = $user;

    $item = new Item();
    $item->setVendorId(1);

    $repo = $this->make(ItemRepository::class, ["getById" => $item]);

    // Act
    $result = Security::ownsItem(1, $repo);

    // Assert
    $this->assertTrue($result);
  }
}
