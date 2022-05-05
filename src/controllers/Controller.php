<?php

namespace controllers;

abstract class Controller
{
  public abstract function index();

  protected function render($view = "index", $data = []): void
  {
    $controller = str_replace('controllers\\', '', static::class);
    $controller = str_replace('Controller', '', $controller);
    $path = __DIR__ . '/../views/' . $controller . "/" . $view . '.php';
    if (file_exists($path)) {
      extract($data);
      $content = $path;
      require __DIR__ . '/../views/layout.php';
    }
  }
}