<?php

namespace models;

class User
{
  public string $id;
  public string $username;
  public string $email;
  public string $password;
  public string $verifyToken;
  public int $timeout;
  public string $authToken;
}
