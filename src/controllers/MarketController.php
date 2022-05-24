<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;
use models\Item;
use repositories\ItemRepository;
use utils\Image;
use utils\Log;
use utils\Message;
use utils\Security;
use utils\Session;

class MarketController extends Controller
{
  #[NoReturn]
  public function index(): void
  {
    $this->redirect("/market/dashboard");
  }

  public function dashboard(): void
  {
    Security::redirectIfNotAuthenticated();

    $data = ["title" => "yo its the dashbord", "pagetitle" => "ScamX", "pagesub" => "lets manage our shit",];
    $this->render("dashboard", data: $data);
  }

  public function add(): void
  {
    Security::redirectIfNotAuthenticated();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleAdd();
    }

    $data = ["title" => "", "pagetitle" => "ScamX", "pagesub" => "Welcome to ScamX bbg",];
    $this->render("add", data: $data);
  }

  private function handleAdd(): void
  {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image = $_FILES["image"];
    $csrf = $_POST["csrf"];

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "add")) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (empty($name) || empty($description) || empty($price) || empty($image)) {
      Message::error("Please fill in all fields.");
      Log::info("Empty login attempt");
      return;
    }

    // Verify if the image is a valid image
    if (!Security::isValidImage($image)) {
      Message::error("Please upload a valid image.");
      Log::info("Invalid image upload attempt");
      return;
    }

    // Format the price
    $price = Security::formatPrice($price);
    if (!$price) {
      Message::error("Please enter a valid price.");
      Log::info("Invalid price attempt");
      return;
    }

    // Add the item to the database
    $item = new Item();
    $item->setName($name);
    $item->setDescription($description);
    $item->setPrice($price);
    $item->setCreationDate(date('Y-m-d H:i:s'));

    $user = Session::getUser();
    $item->setVendorId($user->getId());

    // Upload the image
    $path = Image::upload($image);
    $item->setImage($path);


    ItemRepository::insert($item);
    Message::success("Item added successfully.");
    Log::info("Item '$name' was added by " . $user->getUsername() . " successfully.");
    $this->redirect("/");
  }
}