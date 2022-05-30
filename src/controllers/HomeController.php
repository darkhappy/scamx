<?php

namespace controllers;

class HomeController extends Controller
{
  public function index(): void
  {
    $data = [
      "title" => "",
      "pagetitle" => "ScamX",
      "pagesub" => "Le meilleur site que vous allez avoir les meilleurs prooduits",
    ];
    $this->render(data: $data);
  }
}
