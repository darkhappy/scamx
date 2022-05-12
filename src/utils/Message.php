<?php

namespace utils;

class Message
{

  public static function error($message): void
  {
    self::set($message, MessageType::Error);
  }

  public static function success($message): void
  {
    self::set($message, MessageType::Success);
  }

  public static function info($message): void
  {
    self::set($message, MessageType::Info);
  }

  public static function warning($message): void
  {
    self::set($message, MessageType::Warning);
  }

  private static function set(string $message, MessageType $type): void
  {
    $_SESSION["message"] = ["message" => $message, "type" => $type];
  }

  public static function get(): ?array
  {
    if (isset($_SESSION["message"])) {
      $message = $_SESSION["message"];
      unset($_SESSION["message"]);
      return $message;
    }
    return null;
  }
}
