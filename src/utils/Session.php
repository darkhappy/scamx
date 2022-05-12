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

  public static function isLogged(): bool
  {
    return isset($_SESSION["user"]);
  }

  public static function getCSRF(): string
  {
    if (isset($_SESSION["csrfToken"])) {
      return $_SESSION["csrfToken"];
    }
    return "";
  }

  public static function setCSRF(string $csrfToken): void
  {
    $_SESSION["csrfToken"] = $csrfToken;
  }

  public static function getMessage(): ?array
  {
    if (isset($_SESSION["message"])) {
      return $_SESSION["message"];
    }
    return null;
  }

  public static function unsetMessage(): void
  {
    unset($_SESSION["message"]);
  }

  public static function setMessage(array $message): void
  {
    $_SESSION["message"] = $message;
  }

  public static function getTimeout(): int
  {
    if (isset($_SESSION["timeout"])) {
      return $_SESSION["timeout"];
    }
    return 0;
  }

  public static function setTimeout(int $timeout): void
  {
    $_SESSION["timeout"] = $timeout;
  }

  public static function destroy(): void
  {
    session_destroy();
  }

}
