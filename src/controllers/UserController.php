<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;
use models\User;
use repositories\UserRepository;
use utils\Message;
use utils\MessageType;
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

    $data = [
      "title" => "Login",
      "pagetitle" => "Login",
      "pagesub" => "Lets scam some people",
    ];
    $this->render("login", data: $data);
  }

  private function handleLogin(): void
  {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verify if all fields are filled
    if (empty($username) || empty($password)) {
      Message::set("Please fill in all fields.", MessageType::Error);
      return;
    }

    // Verify if user exists
    $user = UserRepository::getByUsername($username);
    if (!$user || password_verify($password, $user->password)) {
      Message::set("Invalid username or password.", MessageType::Error);
      return;
    }

    // Verify if the user is verified
    if (isset($user->verifyToken)) {
      Message::set(
        "Your account is not verified. Please check your email.",
        MessageType::Error
      );
      return;
    }

    // Login the user
    Session::setUser($user);
    Message::set("Welcome back, $username", MessageType::Success);
    $this->redirect("/");
  }

  private function handleRegister(): void
  {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Verify if all fields are filled
    if (
      empty($username) ||
      empty($email) ||
      empty($password) ||
      empty($confirm)
    ) {
      Message::set("Please fill in all fields.", MessageType::Error);
      return;
    }

    // Verify if the passwords match
    if ($password !== $confirm) {
      Message::set("Passwords do not match.", MessageType::Error);
      return;
    }

    // Verify if the username is valid
    if (!Security::isValidUsername($username)) {
      Message::set(
        "Names may only contain alphanumerical characters, as well as hyphens and dots.",
        MessageType::Error
      );
      return;
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::set("Please enter a valid email.", MessageType::Error);
      return;
    }

    $user = new User();
    $user->username = $username;
    $user->email = $email;
    $user->password = password_hash($password, PASSWORD_BCRYPT);

    // Create a token for the user
    Security::generateVerifyToken($user);

    // Save the user
    UserRepository::insert($user);

    Message::set(
      "Registration successful. Please verify your account in your email.",
      MessageType::Success
    );
    Session::setUser($user);
    $this->redirect("/user/login");
  }

  public function register(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleRegister();
    }

    $data = [
      "title" => "Register",
      "pagetitle" => "Register",
      "pagesub" => "Join the hood",
    ];

    $this->render("register", $data);
  }

  #[NoReturn]
  public function logout(): void
  {
    session_destroy();
    $this->redirect("/");
  }

  #[NoReturn]
  public function verify(): void
  {
    if (!isset($_GET["token"])) {
      Message::set(
        "Please use the URL provided in the email",
        MessageType::Error
      );
      $this->redirect("/user/login");
    }

    $token = $_GET["token"];
    // Get the user from the database
    $user = UserRepository::findWithVerifyToken($token);
    if (!$user || $user->verifyToken !== $token) {
      Message::set(
        "Invalid token. Please verify the link you have sent, or register again.",
        MessageType::Error
      );
      $this->redirect("/user/login");
    }
    if ($user->timeout < time()) {
      Message::set("Token expired. Please register again.", MessageType::Error);
      $this->redirect("/user/login");
    }

    // Verify the user
    UserRepository::setVerified($user);
    Message::set("Account verified. You can now login.", MessageType::Success);
    $this->redirect("/user/login");
  }
}
