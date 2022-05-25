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
    if (Session::isLogged()) {
      $this->redirect("/user/profile");
    } else {
      $this->redirect("/user/login");
    }
  }

  public function profile(): void
  {
    Security::redirectIfNotAuthenticated();
    $data = [
      "title" => "Profile",
      "pagetitle" => "Profile",
      "pagesub" => "wekcom bak",
    ];
    $this->render("profile", $data);
  }

  public function login(): void
  {
    Security::redirectIfAuthenticated();
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
    if (
      empty($username) ||
      empty($email) ||
      empty($password) ||
      empty($confirm)
    ) {
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
      Message::error(
        "Names may only contain alphanumerical characters, as well as hyphens and dots."
      );
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

    Message::info(
      "We've sent an email to $email. Please click the link inside your email to verify your account."
    );
    Log::info("User $username registered with email $email.");
    Security::sendVerifyEmail($user);
    $this->redirect("/user/login");
  }

  public function forgot(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleForgot();
    }
    $data = [
      "title" => "Forgot Password",
      "pagetitle" => "Forgot Password",
      "pagesub" => "bruh seriously",
    ];
    $this->render("forgot", $data);
  }

  private function handleForgot(): void
  {
    $email = $_POST["email"];
    $csrf = $_POST["csrf"];

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "forgot")) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if field is filled
    if (empty($email)) {
      Message::error("Please fill in all fields.");
      Log::debug("Submitted forgot form with empty fields.");
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::error("Please enter a valid email.");
      Log::debug("Submitted forgot form with invalid email.");
      return;
    }

    Message::info(
      "We've sent an email to $email. Please click the link inside your email to reset your password."
    );

    $user = UserRepository::getByEmail($email);
    if ($user) {
      Security::generateResetToken($user);
      UserRepository::setResetToken($user);
      Security::sendResetEmail($user);
      Log::info("User submitted forgot form to email $email.");
    } else {
      Log::warning("User submitted forgot form with a non-existent email.");
    }

    $this->redirect("/user/login");
    //
  }

  public function reset(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleReset();
    }

    if (Session::isLogged()) {
      $view = "reset";
    } else {
      $this->verifyReset();
      $view = "forgot_reset";
    }

    $data = [
      "title" => "Reset Password",
      "pagetitle" => "reset pass",
      "pagesub" => "just type ur new password",
    ];
    $this->render($view, $data);
  }

  private function handleReset()
  {
    $new = $_POST["new"];
    $confirm = $_POST["confirm"];
    $csrf = $_POST["csrf"];

    // Verify CSRF
    if (
      !Security::verifyCSRF($csrf, "reset") &&
      !Security::verifyCSRF($csrf, "forgot_reset")
    ) {
      Message::error("There was a problem, please try again.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify fields
    if (empty($new) || empty($confirm)) {
      Message::error("Please fill in all fields.");
      Log::debug("Submitted reset form with empty fields.");
      return;
    }

    // If the user is logged in, verify the old password
    if (Session::isLogged()) {
      $user = Session::getUser();
      $old = $_POST["old"];

      if (empty($old)) {
        Message::error("Please fill in all fields.");
        Log::debug("Submitted reset form with empty fields.");
        return;
      }

      if (!password_verify($old, $user->getPassword())) {
        Message::error("Please ensure that your old password is correct.");
        Log::debug("Submitted reset form with incorrect old password.");
        return;
      }
    } else {
      // Otherwise, get the user via the token
      $user = $this->verifyReset();
    }

    // Verify if both passwords are the same
    if ($new !== $confirm) {
      Message::error("Passwords do not match.");
      Log::debug("Submitted reset form with mismatched passwords.");
      return;
    }

    // Set the new password
    $user->setPassword($new);
    UserRepository::changePassword($user);

    // Logout the user
    Session::logout(false);
    // TODO: Show message that password has been changed
    // This isn't working due to the session being destroyed
    Message::info("Password changed. You can now login.");
    Log::info("User " . $user->getUsername() . " changed password.");
    $this->redirect("/user/login");
  }

  private function verifyReset(): User
  {
    if (!isset($_GET["token"])) {
      Message::error("Please use the URL provided in the email.");
      $this->redirect("/user/login");
    }

    $token = $_GET["token"];
    // Get the user from the database
    $user = UserRepository::getByResetToken($token);

    if (!$user || $user->getResetToken() !== $token) {
      Message::error(
        "Invalid token. Please verify the link you have sent, or register again."
      );
      Log::debug("Verification with invalid token.");
      $this->redirect("/user/login");
    }
    if ($user->getTimeout() < time()) {
      Message::error("Token expired. Please register again.");
      Log::debug("Verification with expired token.");
      $this->redirect("/user/login");
    }
    return $user;
  }

  #[NoReturn]
  public function logout(): void
  {
    Session::logout();
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
      Message::error(
        "Invalid token. Please verify the link you have sent, or register again."
      );
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
