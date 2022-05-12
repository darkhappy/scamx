<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;
use models\User;
use repositories\UserRepository;
use utils\Log;
use utils\Message;
use utils\Security;
use utils\Session;

class UserController extends Controller
{
  #[NoReturn]
  public function index(): void
  {
    $this->redirect("/user/login");
  }

  public function login(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleLogin();
    }

    $data = ["title" => "Login", "pagetitle" => "Login", "pagesub" => "Lets scam some people",];
    $this->render("login", data: $data);
  }

  private function handleLogin(): void
  {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $csrf = $_POST["csrf"];

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "login")) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (empty($username) || empty($password)) {
      Message::error("Please fill in all fields.");
      Log::info("Empty login attempt");
      return;
    }

    // Verify if user exists
    $user = UserRepository::getByUsername($username);

    if (!$user || !password_verify($password, $user->getPassword())) {
      Message::error("Invalid username or password.");
      Log::debug("Invalid login attempt");
      return;
    }

    // Verify if the user is verified
    if (!empty($user->getVerifyToken())) {
      Message::error("Your account is not verified. Please check your email.");
      Log::debug("Connecting to an unverified account.");
      return;
    }

    // Login the user
    Session::setUser($user);
    Log::info("User $username logged in.");
    Message::success("Welcome back, $username!");
    $this->redirect("/");
  }

  private function handleRegister(): void
  {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];
    $csrf = $_POST["csrf"];

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "register")) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
      Message::error("Please fill in all fields.");
      Log::debug("Registered with empty fields.");
      return;
    }

    // Verify if the passwords match
    if ($password !== $confirm) {
      Message::error("Passwords do not match.");
      Log::debug("Registered with mismatched passwords.");
      return;
    }

    // Verify if the username is valid
    if (!Security::isValidUsername($username)) {
      Message::error("Names may only contain alphanumerical characters, as well as hyphens and dots.");
      Log::debug("Registered with invalid username.");
      return;
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::error("Please enter a valid email.");
      Log::debug("Registered with invalid email.");
      return;
    }

    // Verify if the username is already taken
    if (UserRepository::getByUsername($username)) {
      Message::error("Username is already taken.");
      Log::debug("Registered with taken username.");
      return;
    }

    // Verify if the email is already taken
    if (UserRepository::getByEmail($email)) {
      Message::error("Email is already taken.");
      Log::debug("Registered with taken email.");
      return;
    }

    // User is safe to create
    $user = new User();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);

    // Create a token for the user
    Security::generateVerifyToken($user);

    // Save the user
    UserRepository::insert($user);

    Message::info("We've sent an email to $email. Please click the link inside your email to verify your account.");
    Log::info("User $username registered with email $email.");
    Security::sendVerifyEmail($user);
    $this->redirect("/user/login");
  }

  public function register(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleRegister();
    }

    $data = ["title" => "Register", "pagetitle" => "Register", "pagesub" => "Join the hood",];

    $this->render("register", $data);
  }

  #[NoReturn]
  public function logout(): void
  {
    Session::destroy();
    $this->redirect("/");
  }

  #[NoReturn]
  public function verify(): void
  {
    if (!isset($_GET["token"])) {
      Message::error("Please use the URL provided in the email.");
      $this->redirect("/user/login");
    }

    $token = $_GET["token"];
    // Get the user from the database
    $user = UserRepository::getByVerifyToken($token);
    if (!$user || $user->getVerifyToken() !== $token) {
      Message::error("Invalid token. Please verify the link you have sent, or register again.");
      Log::debug("Verification with invalid token.");
      $this->redirect("/user/login");
    }
    if ($user->getTimeout() < time()) {
      Message::error("Token expired. Please register again.");
      Log::debug("Verification with expired token.");
      $this->redirect("/user/login");
    }

    // Verify the user
    UserRepository::setVerified($user);
    Message::success("Account verified. You can now login.");
    Log::info("User " . $user->getUsername() . " verified.");
    $this->redirect("/user/login");
  }
}
