<?php

namespace utils;

use models\User;

class Security
{
  public static function redirectIfNotAuthenticated(string $message = "Please sign in."): void
  {
    if (!Session::isLogged()) {
      Message::error($message);
      header("Location: /user/login");
      exit();
    }
  }

  public static function redirectIfAuthenticated(string $message = ""): void
  {
    if (Session::isLogged()) {
      header("Location: /user/profile");
      exit();
    }
  }

  public static function generateVerifyToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->setVerifyToken($token);
    $user->setTimeout($timeout);
  }

  public static function generateResetToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->setResetToken($token);
    $user->setTimeout($timeout);
  }

  public static function isValidUsername(string $username): bool
  {
    // Names can only contain letters, numbers, dots, hyphens and underscores
    // Additionally, it must be at least 3 characters, and cannot be longer than 32
    // It must start and end with either a number or a letter
    $regex = "/^[a-zA-Z\d][a-zA-Z\d\-_.]{2,32}[a-zA-Z\d]$/";

    // Trim the username
    $username = trim($username);

    return preg_match($regex, $username);
  }

  private static function sendEmail(User $user, string $subject, string $message): void
  {
    $username = $user->getUsername();
    $email = $user->getEmail();
    $headers = ["MIME-Version" => "1.0", "Content-type" => "text/html; charset=UTF-8", "To" => "$username <$email>", "From" => "ScamX <test@localhost>",];

    if (!mail($email, $subject, $message, implode("\r\n", $headers))) {
      if (DEBUG) {
        Message::info($message);
      } else {
        Message::error("An error occurred while sending the email.");
      }
    }
  }

  public static function sendVerifyEmail(User $user): void
  {
    $url = $_SERVER["HTTP_HOST"];
    $token = $user->getVerifyToken();
    $username = $user->getUsername();

    $subject = "ScamX - Verify your account";
    $body = "
      <h1>Hello, $username!</h1>
      <p>
        To verify your account, please click on the following link:
        <a href='http://$url:80/user/verify?token=$token'>
          http://$url:80/user/verify?token=$token
        </a>
      </p>
      <p>
        If you did not request this email, feel free to ignore it.
      </p>
    ";

    self::sendEmail($user, $subject, $body);
  }

  public static function generateCSRFToken(string $form): string
  {
    // Get the user token
    $token = Session::getCSRF();

    // If the token is not set, return an empty token (this should never happen)
    if (!$token) {
      return "";
    }

    // Concatenate the token
    $token = $token . $form;

    // Hash and return the token
    return hash("sha256", $token);
  }

  public static function verifyCSRF(string $token, string $form): bool
  {
    return self::generateCSRFToken($form) === $token;
  }

  public static function redirectToHTTPS(): void
  {
    // Do not redirect if the user is on localhost, or if we are debugging
    if ($_SERVER["HTTP_HOST"] === "localhost" || DEBUG) {
      return;
    }

    if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
    }
  }

  public static function sanitize(string $input): string
  {
    return htmlspecialchars($input, ENT_QUOTES, "UTF-8");
  }

  public static function sendResetEmail(User $user): void
  {
    $url = $_SERVER["HTTP_HOST"];
    $token = $user->getResetToken();
    $username = $user->getUsername();

    $subject = "ScamX - Reset your password";
    $body = "
      <h1>Hello, $username!</h1>
      <p>
        To reset your password, please click on the following link:
        <a href='http://$url:80/user/reset?token=$token'>
          http://$url:80/user/reset?token=$token
        </a>
      </p>
      <p>
        If you did not request this email, feel free to ignore it.
      </p>
    ";

    self::sendEmail($user, $subject, $body);
  }

  public static function isValidImage(array $image): bool
  {
    // Get the MIME type
    $mime = $image["type"];

    // Check if the MIME type is valid
    if (!in_array($mime, ["image/jpeg", "image/png"])) {
      return false;
    }

    // TODO: probably other checks?:
    // Check file size
    if ($image["size"] > 5000000) {
      return false;
    }

    return true;
  }

  public static function formatPrice(string $input): float|bool
  {
    // Convert to float
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  }
}
