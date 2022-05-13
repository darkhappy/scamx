<?php

namespace utils;

use models\User;

class Session
{
  public static function init(): void
  {
    session_name('ScamX-Session');
    session_start();
    self::setCSRF();
    self::resetSessionId();
  }

  public static function resetSessionId(): void
  {
    $time = self::getTimeout();

    if ($time < time()) {
      session_regenerate_id();
      self::setTimeout(time() + 60 * 5);
    }
  }

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
    if (!isset($_SESSION["csrfToken"])) {
      Session::setCSRF();
    }
    return $_SESSION["csrfToken"];
  }

  // On session start, set a new CSRF token
  public static function setCSRF(): void
  {
    if (!isset($_SESSION["csrfToken"])) {
      $_SESSION["csrfToken"] = bin2hex(openssl_random_pseudo_bytes(32));
    }
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
