<?php

namespace controllers;

class UserController extends Controller
{
  public function index()
  {
    header("Location: /user/login");
  }

  public function login()
  {
    $data = [
      "title" => "Login",
      "pagetitle" => "Login",
      "pagesub" => "Lets scam some people",
    ];
    $this->render("login", data: $data);
  }

  public function register()
  {
    $data = [
      "title" => "Register",
      "pagetitle" => "Register",
      "pagesub" => "Join the hood",
    ];
    $this->render("register", data: $data);
  }
}
