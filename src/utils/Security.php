<?php

namespace utils;

use models\User;

class Security
{
  public static function redirectIfNotAuthenticated(
    string $message = "Please sign in."
  ): void {
    if (!isset($_SESSION["user"])) {
      Message::set($message, MessageType::Error);
      header("Location: /user/login");
      exit();
    }
  }

  public static function generateVerifyToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->verifyToken = $token;
    $user->timeout = $timeout;
  }

  public static function generateResetToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->authToken = $token;
    $user->timeout = $timeout;
  }

  public static function isValidUsername(string $username): bool
  {
    // Names can only contain letters, numbers, dots, hyphens and underscores
    // Additionally, it must be at least 3 characters, and cannot be longer than 32
    // It must start and end with either a number or a letter
    $regex = "/^[a-zA-Z\d][a-zA-Z\d\-_.]{2,32}[a-zA-Z\d]$/";

    return preg_match($regex, $username);
  }
}
