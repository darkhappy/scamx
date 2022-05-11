<?php

namespace utils;

use models\User;

class Security
{
  public static function redirectIfNotAuthenticated(
    string $message = "Please sign in."
  ): void {
    if (!isset($_SESSION["user"])) {
      Message::set($message, MessageType::Error);
      header("Location: /user/login");
      exit();
    }
  }

  public static function generateVerifyToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->verifyToken = $token;
    $user->timeout = $timeout;
  }

  public static function generateResetToken(User $user): void
  {
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $timeout = time() + 60 * 15;
    $user->authToken = $token;
    $user->timeout = $timeout;
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
    $token = $user->verifyToken;
    $email = $user->email;
    $username = $user->username;

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

    $headers = [
      "MIME-Version" => "1.0",
      "Content-type" => "text/html; charset=UTF-8",
      "To" => "$username <$email>",
      "From" => "ScamX <test@localhost>",
    ];

    if (!mail($email, $subject, $body, implode("\r\n", $headers))) {
      if ($url == "localhost") {
        // Since we are on localhost, we can't send emails, so we just print the email
        // TODO: NOT SECURE!!!!!!!! (the way im checking anyways)
        Message::set($body);
      } else {
        Message::set(
          "Verification email could not be sent.",
          MessageType::Error
        );
      }
    }
  }
}
