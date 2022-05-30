<?php

namespace utils;

use models\User;
use repositories\UserRepository;

class Session
{
  public static function init(): void
  {
    session_name("ScamX-Session");
    session_start();
    self::setCSRF();
    self::resetSessionId();
    if (self::getUser() === null) {
      Security::logFromCookie();
    }

    $uri = $_SERVER["REQUEST_URI"];
    Session::setBack($uri);
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

  public static function logout(bool $redirect = true): void
  {
    if (self::isLogged()) {
      $user = self::getUser();
      UserRepository::resetAuthToken($user);

      unset($_SESSION);
      session_destroy();

      Message::info("Successfully logged out.");
      Log::info("Logged out.");
    }
    if ($redirect) {
      Redirect::to("/");
    }
  }

  public static function getBack()
  {
    // Initiate our array if it doesn't exist
    if (!isset($_SESSION["back"])) {
      $_SESSION["back"] = [];
    }

    // Get the before last element of the stack
    array_pop($_SESSION["back"]);
    $back = array_pop($_SESSION["back"]);

    // If the stack is empty, redirect to the home page
    if (!isset($back)) {
      return "/";
    }

    // Otherwise, return the last element of the stack
    return $back;
  }

  public static function setBack(string $back)
  {
    // Initiate our array if it doesn't exist
    if (!isset($_SESSION["back"])) {
      $_SESSION["back"] = [];
    }

    // If the top of the stack is the same as the new back, do nothing
    if (end($_SESSION["back"]) === $back) {
      return;
    }
    // Push the current page to the stack
    $_SESSION["back"][] = $back;

    // If the stack is longer than 10, pop the first element
    // This is to prevent the stack from growing too large
    if (count($_SESSION["back"]) > 10) {
      array_shift($_SESSION["back"]);
    }
  }
}
