<?php

namespace utils;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{
  public static function ifNotAuthenticated(): void
  {
    if (!Session::isLogged()) {
      Message::error("Please login to access this page.");
      self::to("/login");
    }
  }

  #[NoReturn]
  public static function to($url): void {
    header("Location: $url");
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
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
  }
}
