<?php

namespace models;

class User
{
  private string $id;
  private string $username;
  private string $email;
  private string $password;
  private string $verifyToken;
  private int $timeout;
  private string $authToken;

  public function getId(): string
  {
    return $this->id;
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function setUsername(string $username): void
  {
    $this->username = $username;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  public function getVerifyToken(): string
  {
    return $this->verifyToken;
  }

  public function setVerifyToken(string $verifyToken): void
  {
    $this->verifyToken = $verifyToken;
  }

  public function getTimeout(): int
  {
    return $this->timeout;
  }

  public function setTimeout(int $timeout): void
  {
    $this->timeout = $timeout;
  }

  public function getAuthToken(): string
  {
    return $this->authToken;
  }

  public function setAuthToken(string $authToken): void
  {
    $this->authToken = $authToken;
  }
}
