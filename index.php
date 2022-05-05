<?php

use controllers\HomeController;

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

header("Content-Security-Policy: frame-ancestors none");
spl_autoload_register(function ($className) {
  $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
  include_once __DIR__ . "/src/" . $className . ".php";
});

session_start();

/* Database things
$config = parse_ini_file(__DIR__ . "/config/config.ini");
$user = $config["user"];
$pass = $config["pass"];
$name = $config["name"];
$host = $config["host"];
$port = $config["port"];

define(
  "DATABASE",
  new PDO("mysql:host=$host;dbname=$name;port=$port", $user, $pass)
);

define("HOME_PATH", $config["root"]);

// Migrations
require __DIR__ . "/migrations/Migration.php";

*/

const HOME_PATH = "/";

$uri = $_SERVER["REQUEST_URI"];
$uri = substr($uri, strlen(HOME_PATH));
$parts = explode("/", $uri);

$controllerName = $parts[0] !== "" ? ucfirst($parts[0]) : "Home";

if (class_exists("\controllers\\" . $controllerName . "Controller")) {
  $controllerName = "\controllers\\" . $controllerName . "Controller";

  // Ensure that get still works
  $actionName = isset($parts[1]) ? explode("?", $parts[1])[0] : "index";

  $controller = new $controllerName();
  if (method_exists($controller, $actionName)) {
    $controller->$actionName();
  } else {
    $controller->index();
  }
} else {
  $controller = new HomeController();
  $controller->index();
}