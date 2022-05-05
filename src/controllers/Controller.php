<?php

namespace controllers;

abstract class Controller
{
  public abstract function index();

  protected function render($view = "index", $title = "ScamX", $data = []): void
  {
    $controller = str_replace('controllers\\', '', static::class);
    $controller = str_replace('Controller', '', $controller);
    $path = __DIR__ . '/../views/' . $controller  . "/" . $view . '.php';
    if (file_exists($path)) {
      extract([$data, $title]);
      $content = $path;
      require __DIR__ . '/../views/layout.php';
    }
  }
}