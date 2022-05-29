<?php

namespace repositories;

use models\User;

class UserRepository
{
  public static function insert(User $user): void
  {
    $query = DATABASE->prepare(
      "INSERT INTO users (username, email, password, verifyToken, timeout) VALUES (?, ?, ?, ?, ?)"
    );
    $query->bindValue(1, $user->getUsername());
    $query->bindValue(2, $user->getEmail());
    $query->bindValue(3, password_hash($user->getPassword(), PASSWORD_BCRYPT));
    $query->bindValue(4, $user->getVerifyToken());
    $query->bindValue(5, $user->getTimeout());
    $query->execute();
  }

  public static function getByVerifyToken(string $token): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE verifyToken = ?");
    $query->bindValue(1, $token);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function setVerified(?User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET verifyToken = '', timeout = 0 WHERE id = ?"
    );
    $query->bindValue(1, $user->getId());
    $query->execute();
  }

  public static function getByUsername(string $username): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE username = ?");
    $query->bindValue(1, $username);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function getByEmail(string $email): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE email = ?");
    $query->bindValue(1, $email);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function getByResetToken(string $token)
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE resetToken = ?");
    $query->bindValue(1, $token);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function setResetToken(?User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET resetToken = ?, timeout = ? WHERE id = ?"
    );
    $query->bindValue(1, $user->getResetToken());
    $query->bindValue(2, $user->getTimeout());
    $query->bindValue(3, $user->getId());
    $query->execute();
  }

  public static function changePassword(User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET password = ?, resetToken = '', timeout = 0 WHERE id = ?"
    );
    $query->bindValue(1, password_hash($user->getPassword(), PASSWORD_BCRYPT));
    $query->bindValue(2, $user->getId());
    $query->execute();
  }

  public static function getById(int $getVendorId): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE id = ?");
    $query->bindValue(1, $getVendorId);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function getByAuthToken(mixed $cookie)
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE authToken = ?");
    $query->bindValue(1, $cookie);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function setAuthToken(User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET authToken = ?, authTimeout = ? WHERE id = ?"
    );
    $query->bindValue(1, $user->getAuthToken());
    $query->bindValue(2, $user->getAuthTimeout());
    $query->bindValue(3, $user->getId());
    $query->execute();
  }

  public static function resetAuthToken(User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET authToken = '', authTimeout = 0 WHERE id = ?"
    );
    $query->bindValue(1, $user->getId());
    $query->execute();
  }
}
