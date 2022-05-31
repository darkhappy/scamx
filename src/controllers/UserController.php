<?php

namespace controllers;

use JetBrains\PhpStorm\NoReturn;
use models\User;
use repositories\UserRepository;
use utils\Log;
use utils\Mail;
use utils\Message;
use utils\Redirect;
use utils\Security;
use utils\Session;

class UserController extends Controller
{
  private UserRepository $userRepo;

  public function __construct(UserRepository $repo = new UserRepository())
  {
    $this->userRepo = $repo;
  }

  #[NoReturn]
  public function index(): void
  {
    Redirect::to("/user/profile");
  }

  public function profile(): void
  {
    Redirect::ifNotAuthenticated();
    $data = ["title" => "Profile", "pagetitle" => "Profile", "pagesub" => "Bon matin, beauté"];
    $this->render("profile", $data);
  }

  public function login(): void
  {
    Redirect::ifAuthenticated();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleLogin();
    }

    $data = ["title" => "Login", "pagetitle" => "Login", "pagesub" => "Êtes-vous prêt à scammer ?"];
    $this->render("login", data: $data);
  }

  private function handleLogin(): void
  {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $csrf = $_POST["csrf"] ?? "";

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "login")) {
      Message::error("Il y a eu un problème avec votre requête. Veuillez réessayer.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (empty($email) || empty($password)) {
      Message::error("Veuillez remplir tous les champs.");
      Log::info("Empty login attempt");
      return;
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::error("Votre adresse email n'est pas valide.");
      Log::info("Invalid email attempt");
      return;
    }

    // Verify if user exists
    $user = $this->userRepo->getByEmail($email);

    if (!$user || !password_verify($password, $user->getPassword())) {
      Message::error("Le courriel ou le mot de passe est incorrect.");
      Log::debug("Invalid login attempt");
      return;
    }

    // Verify if the user is verified
    if (!empty($user->getVerifyToken())) {
      Message::error("Vous devez d'abord confirmer votre compte.");
      Log::debug("Connecting to an unverified account.");
      return;
    }

    // Login the user
    Session::setUser($user);
    $username = Security::sanitize($user->getUsername());
    Message::success("Bonjour " . $username . " !");
    Log::info("User " . $user->getId() . " logged in.");

    // Verify for remember me
    if (isset($_POST["remember"])) {
      $this->handleRememberMe($user);
    }

    Redirect::back();
  }

  private function handleRememberMe(User $user): void
  {
    // Generate a token for the user
    Security::generateAuthToken($user);
    // Send the remember me token to the database
    $this->userRepo->setAuthToken($user);
    // Add a cookie to the user's browser
    Security::addAuthCookie($user);
  }

  public function register(): void
  {
    Redirect::ifAuthenticated();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleRegister();
    }

    $data = ["title" => "Register", "pagetitle" => "Register", "pagesub" => "Rejoindre le hood"];

    $this->render("register", $data);
  }

  private function handleRegister(): void
  {
    $username = $_POST["username"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm"] ?? "";
    $csrf = $_POST["csrf"] ?? "";

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "register")) {
      Message::error("Il y a eu un problème avec votre requête. Veuillez réessayer.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if all fields are filled
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
      Message::error("Veuillez remplir tous les champs.");
      Log::debug("Registered with empty fields.");
      return;
    }

    // Verify if the passwords match
    if ($password !== $confirm) {
      Message::error("Les mots de passe ne correspondent pas.");
      Log::debug("Registered with mismatched passwords.");
      return;
    }

    // Verify if the username is valid
    if (!Security::isValidUsername($username)) {
      Message::error("Le nom d'utilisateur est invalide.");
      Log::debug("Registered with invalid username.");
      return;
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::error("L'adresse email est invalide.");
      Log::debug("Registered with invalid email.");
      return;
    }

    // Verify if the username is already taken
    if ($this->userRepo->getByUsername($username)) {
      Message::error("Le nom d'utilisateur est déjà pris.");
      Log::debug("Registered with taken username.");
      return;
    }

    // Verify if the email is already taken
    $user = $this->userRepo->getByEmail($email);
    if (!$user) {
      // User is safe to create
      $password = password_hash($password, PASSWORD_BCRYPT);

      $user = new User();
      $user->setUsername($username);
      $user->setEmail($email);
      $user->setPassword($password);

      // Create a token for the user
      Security::generateVerifyToken($user);

      // Save the user
      $this->userRepo->insert($user);
      Log::info("User " . $user->getUsername() . " registered with email $email.");
    }
    // We still allow the user to create an account, however we won't actually create a new user. Instead, we will email
    // the email address provided.
    else {
      Log::debug("Registered with taken email.");
    }

    $email = Security::sanitize($email);

    Message::info("Un email à $email vous a été envoyé pour confirmer votre compte.");
    Mail::sendVerifyEmail($user);
    Redirect::back();
  }

  public function forgot(): void
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $this->handleForgot();
    }
    $data = [
      "title" => "Oublie de mot de passe",
      "pagetitle" => "Oublie de mot de passe",
      "pagesub" => "bruh seriously",
    ];
    $this->render("forgot", $data);
  }

  private function handleForgot(): void
  {
    $email = $_POST["email"] ?? "";
    $csrf = $_POST["csrf"] ?? "";

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "forgot")) {
      Message::error("Il y a eu un problème avec votre requête. Veuillez réessayer.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify if field is filled
    if (empty($email)) {
      Message::error("Veuillez remplir tous les champs.");
      Log::debug("Submitted forgot form with empty fields.");
      return;
    }

    // Verify if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      Message::error("L'adresse email est invalide.");
      Log::debug("Submitted forgot form with invalid email.");
      return;
    }

    $email = Security::sanitize($email);

    Message::info("Un email à $email vous a été envoyé pour réinitialiser votre mot de passe.");

    $user = $this->userRepo->getByEmail($email);
    if ($user) {
      Security::generateResetToken($user);
      $this->userRepo->setResetToken($user);
      Mail::sendResetEmail($user);
      Log::info("User " . $user->getId() . " submitted forgot form to email $email.");
    } else {
      Log::info("User submitted forgot form with a non-existent email.");
    }

    Redirect::back();
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
      "title" => "Renouvellement de mot de passe",
      "pagetitle" => "Renouveler votre mot de passe",
      "pagesub" => "faites juste mettre un nouveau mot de passe",
    ];
    $this->render($view, $data);
  }

  private function handleReset()
  {
    $new = $_POST["new"] ?? "";
    $confirm = $_POST["confirm"] ?? "";
    $csrf = $_POST["csrf"] ?? "";

    // Verify CSRF
    if (!Security::verifyCSRF($csrf, "reset") && !Security::verifyCSRF($csrf, "forgot_reset")) {
      Message::error("Il y a eu un problème avec votre requête. Veuillez réessayer.");
      Log::severe("Possible CSRF attempt");
      return;
    }

    // Verify fields
    if (empty($new) || empty($confirm)) {
      Message::error("Veuillez remplir tous les champs.");
      Log::debug("Submitted reset form with empty fields.");
      return;
    }

    // If the user is logged in, verify the old password
    if (Session::isLogged()) {
      $user = Session::getUser();
      $old = $_POST["old"];

      if (empty($old)) {
        Message::error("Veuillez remplir tous les champs.");
        Log::debug("Submitted reset form with empty fields.");
        return;
      }

      if (!password_verify($old, $user->getPassword())) {
        Message::error("Le mot de passe actuel est incorrect.");
        Log::debug("Submitted reset form with incorrect old password.");
        return;
      }
    } else {
      // Otherwise, get the user via the token
      $user = $this->verifyReset();
    }

    // Verify if both passwords are the same
    if ($new !== $confirm) {
      Message::error("Les mots de passe ne correspondent pas.");
      Log::debug("Submitted reset form with mismatched passwords.");
      return;
    }

    // Set the new password
    $new = password_hash($new, PASSWORD_BCRYPT);
    $user->setPassword($new);
    $this->userRepo->changePassword($user);

    // Logout the user
    Session::logout(false);
    Message::info("Votre mot de passe a été changé avec succès. Veuillez vous reconnecter.");
    Log::info("User " . $user->getUsername() . " changed password.");

    Redirect::to("/user/login");
  }

  private function verifyReset(): User
  {
    if (!isset($_GET["token"])) {
      Message::error("Veuillez utiliser le lien qui vous a été envoyé par email.");
      Log::warning("User submitted forgot form without a token.");
      Redirect::back();
    }

    $token = $_GET["token"] ?? "";
    // Get the user from the database
    $user = $this->userRepo->getByResetToken($token);

    if (!$user || $user->getResetToken() !== $token) {
      Message::error("Le lien de renouvellement de mot de passe est invalide.");
      Log::debug("Verification with invalid token.");
      Redirect::back();
    }
    if ($user->getTimeout() < time()) {
      Message::error("Le lien de renouvellement de mot de passe a expiré. Veuillez réessayer.");
      Log::debug("Verification with expired token.");
      Redirect::back();
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
      Message::error("Veuillez utiliser le lien qui vous a été envoyé par email.");
      Log::warning("User submitted forgot form without a token.");
      Redirect::to("/user/login");
    }

    $token = $_GET["token"] ?? "";
    // Get the user from the database
    $user = $this->userRepo->getByVerifyToken($token);
    if (!$user || $user->getVerifyToken() !== $token) {
      Message::error("Le lien de vérification est invalide.");
      Log::debug("Verification with invalid token.");
      Redirect::to("/user/login");
    }
    if ($user->getTimeout() < time()) {
      Message::error("Le lien de vérification a expiré. Veuillez réessayer.");
      Log::debug("Verification with expired token.");
      Redirect::to("/user/login");
    }

    // Verify the user
    $this->userRepo->setVerified($user);
    Message::info("Votre compte a été vérifié avec succès.");
    Log::info("User " . $user->getUsername() . " verified.");
    Redirect::to("/user/login");
  }
}
