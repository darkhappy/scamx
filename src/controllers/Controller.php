<?php

namespace controllers;

abstract class Controller
{
  abstract public function index();

  protected function render($view = "index", $data = []): void
  {
    $controller = str_replace("controllers\\", "", static::class);

    $controller = str_replace("Controller", "", $controller);
    $path = __DIR__ . "/../views/" . $controller . "/" . $view . ".php";
    if (!file_exists($path)) {
      die("View not found");
    }

    extract($data);
    $content = $path;
    require __DIR__ . "/../views/layout.php";
  }
}
