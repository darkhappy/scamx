<?php

namespace utils;

use models\User;
use repositories\ItemRepository;
use repositories\UserRepository;

class Security
{
  public static function generateVerifyToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->setVerifyToken($token);
    $user->setTimeout($timeout);
  }

  public static function generateResetToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->setResetToken($token);
    $user->setTimeout($timeout);
  }

  public static function isValidUsername(string $username): bool
  {
    // Names can only contain letters, numbers, dots, hyphens and underscores
    // Additionally, it must be at least 3 characters, and cannot be longer than 32
    // It must start and end with either a number or a letter
    $regex = "/^[a-zA-Z\d][a-zA-Z\d\-_.]{1,32}[a-zA-Z\d]$/";

    // Trim the username
    $username = trim($username);

    return preg_match($regex, $username);
  }

  public static function generateCSRFToken(string $form): string
  {
    // Get the user token
    $token = Session::getCSRF();

    // If the token is not set, return an empty token (this should never happen)
    if (!$token) {
      return "";
    }

    // Concatenate the token
    $token = $token . $form;

    // Hash and return the token
    return hash("sha256", $token);
  }

  public static function verifyCSRF(string $token, string $form): bool
  {
    return self::generateCSRFToken($form) === $token;
  }

  public static function redirectToHTTPS(): void
  {
    // Do not redirect if the user is on localhost, or if we are debugging
    if ($_SERVER["HTTP_HOST"] === "localhost" || DEBUG) {
      return;
    }

    if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
    }
  }

  public static function sanitize(string $input): string
  {
    return htmlspecialchars($input, ENT_QUOTES, "UTF-8");
  }

  public static function ownsItem(string $id): bool
  {
    // Get the user
    $user = Session::getUser();
    if (!$user) {
      return false;
    }

    $userID = $user->getID();

    // Get the item
    $item = ItemRepository::getById($id);

    // Check if the item exists
    if (!$item) {
      return false;
    }

    // Check if the user owns the item
    if ($item->getVendorId() != $userID) {
      return false;
    }

    return true;
  }

  public static function generateAuthToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 60 * 24 * 30;
    $user->setAuthToken($token);
    $user->setAuthTimeout($timeout);
  }

  public static function logFromCookie(): void
  {
    // Check if there is a remember me cookie
    if (!isset($_COOKIE["authToken"])) {
      return;
    }

    // Get the remember me cookie
    $cookie = $_COOKIE["authToken"];

    // Find a user with the remember me cookie
    $user = UserRepository::getByAuthToken($cookie);

    // Check if the user exists
    if (!$user) {
      return;
    }

    // If the auth timeout is expired, remove the cookie
    if ($user->getAuthTimeout() < time()) {
      UserRepository::resetAuthToken($user);
      return;
    }

    // Set the user as logged in
    Session::setUser($user);
    Message::info("Welcome back, " . $user->getUsername() . "!");
  }

  public static function addAuthCookie(User $user): void
  {
    $token = $user->getAuthToken();
    $timeout = $user->getAuthTimeout();
    setcookie("authToken", $token, $timeout, "/");
  }
}
