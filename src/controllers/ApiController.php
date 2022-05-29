<?php

namespace controllers;

use Stripe\StripeClient;

class ApiController
{
  public function stripe()
  {
    $price = $_POST["price"];
    $stripe = new StripeClient(STRIPE_SECRET_KEY);
    $intent = $stripe->paymentIntents->create([
      "amount" => $price * 100,
      "currency" => "cad",
    ]);
    echo $intent->client_secret;
  }
}
