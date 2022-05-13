<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use controllers\HomeController;
use utils\Security;
use utils\Session;

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

header("Content-Security-Policy: frame-ancestors none");
header("Content-Security-Policy: default-src 'self'");

if (!file_exists(__DIR__ . "/config/config.ini")) {
  die("Config file not found");
}

$config = parse_ini_file(__DIR__ . "/config/config.ini");
$user = $config["user"];
$pass = $config["pass"];
$name = $config["name"];
$host = $config["host"];
$port = $config["port"];
$debug = $config["debug"];
if ($debug) {
  include_once __DIR__ . "/c3.php";
}
$stripe_public_key = $config["stripe_public_key"];
$stripe_secret_key = $config["stripe_secret_key"];

spl_autoload_register(function ($className) {
  $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
  include_once __DIR__ . "/src/$className.php";
});

Session::init();
Security::redirectToHTTPS();
define("DATABASE", new PDO("mysql:host=$host;dbname=$name;port=$port", $user, $pass));
define("HOME_PATH", $config["root"]);
define("DEBUG", $debug);

// Migrations
require __DIR__ . "/migrations/Migration.php";

$uri = $_SERVER["REQUEST_URI"];
$uri = substr($uri, strlen(HOME_PATH));
$parts = explode("/", $uri);

$controllerName = $parts[0] !== "" ? ucfirst($parts[0]) : "Home";

if (class_exists("\controllers\\" . $controllerName . "Controller")) {
  $controllerName = "\controllers\\" . $controllerName . "Controller";
  $controller = new $controllerName();
} else {
  $controller = new HomeController();
}

// Get the action name
// We also have to keep the get parameters
$actionName = isset($parts[1]) ? explode("?", $parts[1])[0] : "index";

if (method_exists($controller, $actionName)) {
  $controller->$actionName();
} else {
  $controller->index();
}
