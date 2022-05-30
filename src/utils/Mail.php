<?php

namespace utils;

use models\User;

class Mail
{
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

  private static function sendEmail(User $user, string $subject, string $message): void
  {
    $username = $user->getUsername();
    $email = $user->getEmail();
    $headers = [
      "MIME-Version" => "1.0",
      "Content-type" => "text/html; charset=UTF-8",
      "To" => "$username <$email>",
      "From" => "ScamX <test@localhost>",
    ];

    if (!mail($email, $subject, $message, implode("\r\n", $headers))) {
      if (DEBUG) {
        Message::info($message);
      } else {
        Message::error("An error occurred while sending the email.");
      }
    }
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
}
