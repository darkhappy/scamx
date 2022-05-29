<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;
use models\Item;
use models\Transaction;
use repositories\ItemRepository;
use repositories\TransactionRepository;
use utils\Image;
use utils\Log;
use utils\Market;
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

    $data = [
      "title" => "yo its the dashbord",
      "pagetitle" => "ScamX",
      "pagesub" => "lets manage our shit",
    ];
    $this->render("dashboard", data: $data);
  }

  public function add(): void
  {
    Security::redirectIfNotAuthenticated();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleAdd();
    }

    $data = [
      "title" => "",
      "pagetitle" => "ScamX",
      "pagesub" => "Welcome to ScamX bbg",
    ];
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

    if (empty($name) || empty($description) || empty($price) || empty($image)) {
      // Verify if all fields are filled
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
    $item->setCreationDate(date("Y-m-d H:i:s"));

    $user = Session::getUser();
    $item->setVendorId($user->getId());

    // Upload the image
    $path = Image::upload($image);
    $item->setImage($path);

    ItemRepository::insert($item);
    Message::success("Item added successfully.");
    Log::info(
      "Item '$name' was added by " . $user->getUsername() . " successfully."
    );
    $this->redirect("/");
  }

  public function info(): void
  {
    $item = ItemRepository::getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      $this->redirect("/");
    }

    $data = [
      "title" => "",
      "pagetitle" => "buying",
      "pagesub" => "this is a very cool item you should buy it",
      "item" => $item,
    ];
    $this->render("info", data: $data);
  }

  public function edit(): void
  {
    Security::redirectIfNotAuthenticated();

    $item = ItemRepository::getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      $this->redirect("/");
    }

    if ($item->getVendorId() != Session::getUser()->getId()) {
      Message::error("You are not allowed to edit this item.");
      Log::info("Trying to edit item that is not owned by user.");
      $this->redirect("/");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleEdit($item);
    }

    $data = [
      "title" => "",
      "pagetitle" => "ScamX",
      "pagesub" => "Welcome to ScamX bbg",
      "item" => $item,
    ];
    $this->render("edit", data: $data);
  }

  private function handleEdit(Item $item)
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
    if (empty($name) || empty($description) || empty($price)) {
      Message::error("Please fill in all fields.");
      Log::info("Empty login attempt");
      return;
    }

    // Check if the user is changing the image
    if (!empty($image["name"])) {
      // Verify if the image is a valid image
      if (!Security::isValidImage($image)) {
        Message::error("Please upload a valid image.");
        Log::info("Invalid image upload attempt");
        return;
      }

      // Delete the old image
      $oldImage = $item->getImage();
      Image::delete($oldImage);

      // Upload the image
      $path = Image::upload($image);
      $item->setImage($path);
    }

    // Format the price
    $price = Security::formatPrice($price);
    if (!$price) {
      Message::error("Please enter a valid price.");
      Log::info("Invalid price attempt");
      return;
    }

    $item->setName($name);
    $item->setDescription($description);
    $item->setPrice($price);

    $user = Session::getUser();

    ItemRepository::edit($item);
    Message::success("Item edited successfully.");
    Log::info(
      "Item '$name' was edited by " . $user->getUsername() . " successfully."
    );
    $this->redirect("/");
  }

  public function buy(): void
  {
    Security::redirectIfNotAuthenticated();

    $item = ItemRepository::getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      $this->redirect("/");
    }

    if ($item->getVendorId() == Session::getUser()->getId()) {
      Message::error("You can't buy your own item.");
      Log::info("Trying to buy item that is owned by user.");
      $this->redirect("/");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleBuy($item);
    }

    $data = [
      "title" => "",
      "pagetitle" => "ScamX",
      "pagesub" => "Welcome to ScamX bbg",
      "item" => $item,
    ];
    $this->render("buy", data: $data);
  }

  private function handleBuy(Item $item)
  {
    $csrf = $_POST["csrf"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $postal = $_POST["postal"];
    $province = $_POST["province"];
    $ccname = $_POST["ccname"];
    $cardnumber = $_POST["cardnumber"];
    $expmonth = $_POST["expmonth"];
    $expyear = $_POST["expyear"];
    $cvc = $_POST["cvc2"];

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "buy")) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (
      empty($name) ||
      empty($address) ||
      empty($city) ||
      empty($postal) ||
      empty($province) ||
      empty($ccname) ||
      empty($cardnumber) ||
      empty($expmonth) ||
      empty($expyear) ||
      empty($cvc)
    ) {
      Message::error("Please fill in all fields.");
      Log::info("Empty buy attempt");
      return;
    }

    // Verify if they are from Quebec
    if ($province !== "QC") {
      Message::error("tokébec icit");
      Log::info("Invalid province attempt");
      return;
    }

    $price = Market::calculateTotal($item->getPrice());

    // Ask Stripe to verify the card
    $intent = Market::buy($price, $cardnumber, $expmonth, $expyear, $cvc);
    if (!$intent) {
      // There was an error, however the function will have already sent the error message
      Log::severe("Stripe error");
      return;
    }

    // Create a new transaction
    $transaction = new Transaction();

    $transaction->setClientId(Session::getUser()->getId());
    $transaction->setVendorId($item->getVendorId());
    $transaction->setItemId($item->getId());
    $transaction->setPrice($price);
    $transaction->setStripeIntentId($intent->id);
    $transaction->setDate(date("Y-m-d H:i:s"));

    TransactionRepository::insert($transaction);

    Message::success("Transaction completed successfully.");
    Log::info("Transaction completed successfully.");
    $this->redirect("/");
  }

  #[NoReturn]
  public function delete(): void
  {
    Security::redirectIfNotAuthenticated();

    $item = ItemRepository::getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      $this->redirect("/");
    }

    if ($item->getVendorId() != Session::getUser()->getId()) {
      Message::error("You are not allowed to edit this item.");
      Log::info("Trying to edit item that is not owned by user.");
      $this->redirect("/");
    }

    ItemRepository::delete($item);
    Message::success("Item deleted successfully.");
    Log::info(
      "Item '" .
        $item->getName() .
        "' was deleted by " .
        Session::getUser()->getUsername() .
        " successfully."
    );
    $this->redirect("/market/dashboard");
  }
}
