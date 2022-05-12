<?php

namespace utils;

class Log
{

  public static function severe($message): void
  {
    self::log($message, LogType::SEVERE);
  }

  private static function log(string $message, LogType $type): void
  {
    $logFile = __DIR__ . '/../../logs/' . date('Y-m-d') . '.log';
    $ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s,v');
    $log = "$date [$ip] $type->name - $message" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND);
  }

  public static function warning($message): void
  {
    self::log($message, LogType::WARN);
  }

  public static function info($message): void
  {
    self::log($message, LogType::INFO);
  }

  public static function debug($message): void
  {
    self::log($message, LogType::DEBUG);
  }

  public static function error($message): void
  {
    self::log($message, LogType::ERROR);
  }
}