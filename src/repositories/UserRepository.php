<?php

namespace repositories;

use models\User;
use PDO;

class UserRepository extends Repository
{
  function getByName(string $username): User|bool
  {
    $query = DATABASE->prepare("SELECT * FROM users WHERE username = ?");
    $query->bindValue(1, $username);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_CLASS, "\models\User");
    return $query->fetch();
  }

  function insert(User $user): void
  {
    $query = DATABASE->prepare(
      "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );
    $query->bindValue(1, $user->username);
    $query->bindValue(2, $user->email);
    $query->bindValue(3, password_hash($user->password, PASSWORD_BCRYPT));
    $query->execute();
  }
}
