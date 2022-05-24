<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller
{
  abstract public function index(): void;

  protected function render($view = "index", $data = []): void
  {
    $controller = str_replace("controllers\\", "", static::class);

    $controller = str_replace("Controller", "", $controller);
    $path = __DIR__ . "/../views/" . $controller . "/" . $view . ".php";
    if (!file_exists($path)) {
      die("View not found at $path");
    }

    extract($data);
    $content = $path;
    require __DIR__ . "/../views/layout.php";
  }

  #[NoReturn]
  protected function redirect($url): void {
    header("Location: " . $url);
    exit();
  }
}
