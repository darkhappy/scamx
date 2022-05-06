<?php

namespace repositories;

abstract class Repository
{
  protected static Repository $repository;

  public static function getInstance(): Repository
  {
    if (!isset(self::$repository)) {
      self::$repository = new static();
    }
    return self::$repository;
  }
}
