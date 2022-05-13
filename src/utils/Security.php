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
    $user->setAuthToken($token);
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

  public static function sendVerifyEmail(User $user): void
  {
    // get url of the website
    $url = $_SERVER["HTTP_HOST"];
    $token = $user->getVerifyToken();
    $email = $user->getEmail();
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

    $headers = ["MIME-Version" => "1.0", "Content-type" => "text/html; charset=UTF-8", "To" => "$username <$email>", "From" => "ScamX <test@localhost>",];

    if (!mail($email, $subject, $body, implode("\r\n", $headers))) {
      if (DEBUG) {
        Message::info($body);
      } else {
        Message::error("Verification email could not be sent.");
      }
    }
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
}
