<?php

namespace repositories;

use models\User;
use PDO;

class UserRepository
{
  public function insert(User $user): void
  {
    $query = DATABASE->prepare(
      "INSERT INTO users (username, email, password, verifyToken, timeout) VALUES (?, ?, ?, ?, ?)"
    );
    $query->bindValue(1, $user->getUsername());
    $query->bindValue(2, $user->getEmail());
    $query->bindValue(3, $user->getPassword());
    $query->bindValue(4, $user->getVerifyToken());
    $query->bindValue(5, $user->getTimeout(), PDO::PARAM_INT);
    $query->execute();
  }

  public function getByVerifyToken(string $token): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE verifyToken = ?");
    $query->bindValue(1, $token);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function getByUsername(string $username): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE username = ?");
    $query->bindValue(1, $username);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function getByEmail(string $email): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE email = ?");
    $query->bindValue(1, $email);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function getByResetToken(string $token)
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE resetToken = ?");
    $query->bindValue(1, $token);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function setResetToken(?User $user): void
  {
    $query = DATABASE->prepare("UPDATE users SET resetToken = ?, timeout = ? WHERE id = ?");
    $query->bindValue(1, $user->getResetToken());
    $query->bindValue(2, $user->getTimeout(), PDO::PARAM_INT);
    $query->bindValue(3, $user->getId(), PDO::PARAM_INT);
    $query->execute();
  }

  public function changePassword(User $user): void
  {
    $query = DATABASE->prepare("UPDATE users SET password = ?, resetToken = '', timeout = 0 WHERE id = ?");
    $query->bindValue(1, $user->getPassword());
    $query->bindValue(2, $user->getId(), PDO::PARAM_INT);
    $query->execute();
  }

  public function getById(int $getVendorId): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE id = ?");
    $query->bindValue(1, $getVendorId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function getByAuthToken(string $cookie): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE authToken = ?");
    $query->bindValue(1, $cookie);
    $query->execute();
    return $query->fetchObject(User::class);
  }

  public function setAuthToken(User $user): void
  {
    $query = DATABASE->prepare("UPDATE users SET authToken = ?, authTimeout = ? WHERE id = ?");
    $query->bindValue(1, $user->getAuthToken());
    $query->bindValue(2, $user->getAuthTimeout(), PDO::PARAM_INT);
    $query->bindValue(3, $user->getId(), PDO::PARAM_INT);
    $query->execute();
  }

  public function resetAuthToken(User $user): void
  {
    $query = DATABASE->prepare("UPDATE users SET authToken = '', authTimeout = 0 WHERE id = ?");
    $query->bindValue(1, $user->getId(), PDO::PARAM_INT);
    $query->execute();
  }

  public function setVerified(?User $user): void
  {
    $query = DATABASE->prepare("UPDATE users SET verifyToken = '', timeout = 0 WHERE id = ?");
    $query->bindValue(1, $user->getId(), PDO::PARAM_INT);
    $query->execute();
  }
}
