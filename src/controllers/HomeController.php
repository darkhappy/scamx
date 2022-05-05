<?php

namespace controllers;

class HomeController extends Controller
{
  public function index()
  {
    $data = ['title' => '', 'pagetitle' => 'ScamX', 'pagesub' => "Welcome to ScamX bbg"];
    $this->render(data: $data);
  }
}