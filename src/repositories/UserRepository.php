<?php

namespace repositories;

use models\User;

class UserRepository extends Repository
{
  public static function insert(User $user): void
  {
    $query = DATABASE->prepare(
      "INSERT INTO users (username, email, password, verifyToken, timeout) VALUES (?, ?, ?, ?, ?)"
    );
    $query->bindValue(1, $user->username);
    $query->bindValue(2, $user->email);
    $query->bindValue(3, password_hash($user->password, PASSWORD_BCRYPT));
    $query->bindValue(4, $user->verifyToken);
    $query->bindValue(5, $user->timeout);
    $query->execute();
  }

  public static function findWithVerifyToken(string $token): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE verifyToken = ?");
    $query->bindValue(1, $token);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public static function setVerified(?User $user): void
  {
    $query = DATABASE->prepare(
      "UPDATE users SET verifyToken = NULL, timeout = NULL WHERE id = ?"
    );
    $query->bindValue(1, $user->id);
    $query->execute();
  }

  public static function getByUsername(string $username): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE username = ?");
    $query->bindValue(1, $username);
    $query->execute();
    return $query->fetchObject(User::class);
  }
}
