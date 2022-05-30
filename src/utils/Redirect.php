<?php

namespace utils;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{
  public static function ifNotAuthenticated(): void
  {
    if (!Session::isLogged()) {
      Message::error("Please login to access this page.");
      self::to("/user/login");
    }
  }

  #[NoReturn]
  public static function to($url): void {
    // Remove the first slash if it exists
    if (str_starts_with($url, "/")) {
      $url = substr($url, 1);
    }
    header("Location: " . HOME_PATH . $url);
    exit();
  }

  public static function ifAuthenticated(): void
  {
    if (Session::isLogged()) {
      Message::error("You are already logged in.");
      self::back();
    }
  }

  #[NoReturn]
  public static function back(): void
  {
    $back = Session::getBack();
    if (isset($back)) {
      self::to($back);
    } elseif (isset($_SERVER["HTTP_REFERER"])) {
      self::to($_SERVER["HTTP_REFERER"]);
    } else {
      self::to("/");
    }
  }
}
