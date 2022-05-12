<?php

namespace utils;

class Message
{

  public static function error($message): void
  {
    self::set($message, MessageType::ERROR);
  }

  public static function success($message): void
  {
    self::set($message, MessageType::SUCCESS);
  }

  public static function info($message): void
  {
    self::set($message, MessageType::INFO);
  }

  public static function warning($message): void
  {
    self::set($message, MessageType::WARNING);
  }

  private static function set(string $message, MessageType $type): void
  {
    Session::setMessage(['message' => $message, 'type' => $type]);
  }

  public static function get(): ?array
  {
    $message = Session::getMessage();
    Session::unsetMessage();
    return $message;
  }
}
