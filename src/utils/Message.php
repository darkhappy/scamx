<?php

namespace utils;

class Message
{
  public static function set(
    string $message,
    MessageType $type = MessageType::Info
  ): void {
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
