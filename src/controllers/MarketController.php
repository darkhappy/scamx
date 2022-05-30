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
use utils\Redirect;
use utils\Security;
use utils\Session;

class MarketController extends Controller
{
  private ItemRepository $itemRepo;

  public function __construct($repo = new ItemRepository())
  {
    $this->itemRepo = $repo;
  }

  #[NoReturn]
  public function index(): void
  {
    Redirect::to("/market/dashboard");
  }

  public function dashboard(): void
  {
    Redirect::ifNotAuthenticated();

    $data = [
      "title" => "Tableau de bord",
      "pagetitle" => "Tableau de bord",
      "pagesub" => "Gérer votre inventaire et vos transactions",
    ];
    $this->render("dashboard", data: $data);
  }

  public function add(): void
  {
    Redirect::ifNotAuthenticated();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleAdd();
    }

    $data = ["title" => "Ajouter", "pagetitle" => "Ajouter", "pagesub" => "Ajouter un nouvel objet à la vente"];
    $this->render("add", data: $data);
  }

  private function handleAdd(): void
  {
    $name = $_POST["name"] ?? "";
    $description = $_POST["description"] ?? "";
    $price = $_POST["price"] ?? "";
    $image = $_FILES["image"] ?? [];
    $csrf = $_POST["csrf"] ?? "";

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "add")) {
      Message::error("Il y a eu un problème avec votre requête. Veuillez réessayer.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    if (empty($name) || empty($description) || empty($price) || empty($image)) {
      // Verify if all fields are filled
      Message::error("Veuillez remplir tous les champs.");
      Log::info("Empty login attempt");
      return;
    }

    // Format the price
    $price = Market::convertPriceToFloat($price);
    if (!$price) {
      Message::error("Veuillez entrer un prix valide.");
      Log::info("Invalid price attempt");
      return;
    }

    // Verify if the image is a valid image
    if (!Image::isValidImage($image)) {
      Message::error("Veuillez entrer une image valide.");
      Log::info("Invalid image upload attempt");
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
    if (!$path) {
      Message::error("Une erreur est survenue lors du téléversement de l'image.");
      Log::severe("Image upload failed");
      return;
    }
    $item->setImage($path);

    $this->itemRepo->insert($item);
    Message::success("Produit ajouté avec succès.");
    Log::info("Item '$name' was added by " . $user->getUsername() . " successfully.");
    Redirect::back();
  }

  public function info(): void
  {
    $item = $this->itemRepo->getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      Redirect::back();
    }

    $itemName = $item->getName();
    $itemName = Security::sanitize($itemName);

    $data = [
      "title" => "Info - $itemName",
      "pagetitle" => "Viewing $itemName",
      "pagesub" => "this is a very cool item you should buy it",
      "item" => $item,
    ];
    $this->render("info", data: $data);
  }

  public function edit(): void
  {
    Redirect::ifNotAuthenticated();

    $item = $this->itemRepo->getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      Redirect::back();
    }

    if ($item->getVendorId() != Session::getUser()->getId()) {
      Message::error("You are not allowed to edit this item.");
      Log::info("Trying to edit item that is not owned by user.");
      Redirect::back();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleEdit($item);
    }

    $itemName = $item->getName();
    $itemName = Security::sanitize($itemName);

    $data = [
      "title" => "Editing $itemName",
      "pagetitle" => "Editing $itemName",
      "pagesub" => "put the real things this time",
      "item" => $item,
    ];
    $this->render("edit", data: $data);
  }

  private function handleEdit(Item $item)
  {
    $name = $_POST["name"] ?? "";
    $description = $_POST["description"] ?? "";
    $price = $_POST["price"] ?? "";
    $image = $_FILES["image"] ?? [];
    $csrf = $_POST["csrf"] ?? "";

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

    // Format the price
    $price = Market::convertPriceToFloat($price);
    if (!$price) {
      Message::error("Please enter a valid price.");
      Log::info("Invalid price attempt");
      return;
    }

    // Check if the user is changing the image
    if (!empty($image["name"])) {
      // Verify if the image is a valid image
      if (!Image::isValidImage($image)) {
        Message::error("Please upload a valid image.");
        Log::info("Invalid image upload attempt");
        return;
      }

      // Delete the old image
      $oldImage = $item->getImage();
      Image::delete($oldImage);

      // Upload the image
      $path = Image::upload($image);
      if (!$path) {
        Message::error("There was a problem uploading the image.");
        Log::info("Image upload attempt failed");
        return;
      }
      $item->setImage($path);
    }

    $item->setName($name);
    $item->setDescription($description);
    $item->setPrice($price);

    $user = Session::getUser();

    $this->itemRepo->edit($item);
    Message::success("Item edited successfully.");
    Log::info("Item '$name' was edited by " . $user->getUsername() . " successfully.");
    Redirect::back();
  }

  public function buy(): void
  {
    Redirect::ifNotAuthenticated();

    $item = $this->itemRepo->getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      Redirect::back();
    }

    if ($item->getHidden()) {
      Message::error("You can no longer buy this item.");
      Log::info("Trying to buy hidden item.");
      Redirect::back();
    }

    if ($item->getVendorId() == Session::getUser()->getId()) {
      Message::error("You can't buy your own item.");
      Log::info("Trying to buy item that is owned by user.");
      Redirect::back();
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
    $csrf = $_POST["csrf"] ?? "";
    $name = $_POST["name"] ?? "";
    $address = $_POST["address"] ?? "";
    $city = $_POST["city"] ?? "";
    $postal = $_POST["postal"] ?? "";
    $province = $_POST["province"] ?? "";
    $ccname = $_POST["ccname"] ?? "";
    $cardnumber = $_POST["cardnumber"] ?? "";
    $expmonth = $_POST["expmonth"] ?? "";
    $expyear = $_POST["expyear"] ?? "";
    $cvc = $_POST["cvc2"] ?? "";

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

    // Verify card details
    // Verify fields
    if (
      !is_numeric($cardnumber) ||
      !is_numeric($expmonth) ||
      !is_numeric($expyear) ||
      !is_numeric($cvc) ||
      strlen($cardnumber) != 16 ||
      strlen($expmonth) != 2 ||
      strlen($expyear) != 4 ||
      strlen($cvc) != 3
    ) {
      Message::error("Please make sure that all payment details are correct.");
      Log::info("Invalid card number attempt");
      return;
    }

    // Ask Stripe to verify the card
    $intent = Market::buy($price, $cardnumber, $expmonth, $expyear, $cvc);
    if (!$intent) {
      Message::error("There was a problem, please verify your card details.");
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
    Redirect::back();
  }

  #[NoReturn]
  public function delete(): void
  {
    Redirect::ifNotAuthenticated();

    $item = $this->itemRepo->getById($_GET["id"]);
    if (!$item) {
      Message::error("Item not found.");
      Log::info("Trying to view non-existing item.");
      Redirect::back();
    }

    if ($item->getVendorId() != Session::getUser()->getId()) {
      Message::error("You are not allowed to delete this item.");
      Log::info("Trying to edit item that is not owned by user.");
      Redirect::back();
    }

    $this->itemRepo->delete($item);
    Image::delete($item->getImage());
    Message::success("Item deleted successfully.");
    Log::info("Item '" . $item->getName() . "' was deleted by " . Session::getUser()->getUsername() . " successfully.");
    Redirect::back();
  }

  #[NoReturn]
  public function confirm(): void
  {
    Redirect::ifNotAuthenticated();
    $transaction = TransactionRepository::getById($_GET["id"]);
    if (!$transaction) {
      Message::error("Transaction not found.");
      Log::info("Trying to view non-existing transaction.");
      Redirect::back();
    }

    // Check if we are the vendor
    if ($transaction->getVendorId() != Session::getUser()->getId()) {
      Message::error("You are not allowed to confirm this transaction.");
      Log::info("Trying to refund transaction that is not owned by user.");
      Redirect::back();
    }

    $status = $transaction->getStatus();

    if ($status == "refunded") {
      Message::error("This transaction has already been refunded.");
      Log::info("Trying to refund transaction that has already been refunded.");
      Redirect::back();
    } elseif ($status == "confirmed") {
      Message::error("This transaction has already been confirmed.");
      Log::info("Trying to refund transaction that has already been confirmed.");
      Redirect::back();
    }

    $transaction->setStatus("confirmed");
    TransactionRepository::updateStatus($transaction);
    Message::success("Transaction confirmed successfully.");
    Log::info("Transaction confirmed successfully.");
    Redirect::back();
  }

  #[NoReturn]
  public function refund(): void
  {
    Redirect::ifNotAuthenticated();
    $transaction = TransactionRepository::getById($_GET["id"]);
    if (!$transaction) {
      Message::error("Transaction not found.");
      Log::info("Trying to view non-existing transaction.");
      Redirect::back();
    }

    // Check if we are either the vendor or the client
    if (
      $transaction->getClientId() != Session::getUser()->getId() &&
      $transaction->getVendorId() != Session::getUser()->getId()
    ) {
      Message::error("You are not allowed to refund this transaction.");
      Log::info("Trying to refund transaction that is not owned by user.");
      Redirect::back();
    }

    $status = $transaction->getStatus();

    if ($status == "refunded") {
      Message::error("This transaction has already been refunded.");
      Log::info("Trying to refund transaction that has already been refunded.");
      Redirect::back();
    }

    if ($status == "confirmed") {
      Message::error("This transaction has already been confirmed.");
      Log::info("Trying to refund transaction that has already been confirmed.");
      Redirect::back();
    }

    $intent = Market::refund($transaction->getStripeIntentId());

    if (!$intent) {
      Message::error("There was a problem issuing a refund, please try again later.");
      Log::severe("Stripe error");
      Redirect::back();
    }

    $transaction->setStatus("refunded");
    TransactionRepository::updateStatus($transaction);
    Message::success("Transaction refunded successfully.");
    Log::info("Transaction refunded successfully.");
    Redirect::back();
  }
}
