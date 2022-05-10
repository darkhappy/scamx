<?php

namespace utils;

use models\User;

class Session
{
  public static function getUser(): User|null
  {
    if (isset($_SESSION["user"])) {
      return $_SESSION["user"];
    }
    return null;
  }

  public static function setUser(User $user): void
  {
    $_SESSION["user"] = $user;
  }
}
