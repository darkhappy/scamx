<?php

namespace utils;

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\StripeClient;

class Market
{
  public static function calculateTotal(float $subtotal): float
  {
    return round(
      $subtotal +
        self::calculateTPS($subtotal) +
        self::calculateTVQ($subtotal) +
        self::calculateShipping($subtotal),
      2
    );
  }

  public static function calculateTPS(float $subtotal): float
  {
    return round($subtotal * 0.05, 2);
  }

  public static function calculateTVQ(float $subtotal): float
  {
    return round($subtotal * 0.0975, 2);
  }

  public static function calculateShipping(float $subtotal): float
  {
    return 20 + round($subtotal * 0.05, 2);
  }

  public static function calculateProfit(float $subtotal): float
  {
    return round($subtotal * 0.95, 2);
  }

  public static function buy(
    float $price,
    string $cardNumber,
    string $month,
    string $year,
    string $cvc
  ): PaymentIntent|bool {
    $stripe = new StripeClient(STRIPE_SECRET_KEY);

    if (
      !is_numeric($cardNumber) ||
      !is_numeric($month) ||
      !is_numeric($year) ||
      !is_numeric($cvc) ||
      strlen($cardNumber) != 16 ||
      strlen($month) != 2 ||
      strlen($year) != 4 ||
      strlen($cvc) != 3
    ) {
      Message::error(
        "Please ensure that all credit card fields are filled in correctly."
      );
      return false;
    }

    try {
      $paymentMethod = $stripe->paymentMethods->create([
        "type" => "card",
        "card" => [
          "number" => $cardNumber,
          "exp_month" => $month,
          "exp_year" => $year,
          "cvc" => $cvc,
        ],
      ]);

      $intent = $stripe->paymentIntents->create([
        "amount" => $price * 100,
        "currency" => "cad",
        "payment_method" => $paymentMethod->id,
        "confirm" => true,
      ]);
    } catch (ApiErrorException $e) {
      Message::error($e->getMessage());
      return false;
    }

    return $intent;
  }

  public static function refund($getStripeIntentId): bool|Refund
  {
    $stripe = new StripeClient(STRIPE_SECRET_KEY);

    try {
      return $stripe->refunds->create(["payment_intent" => $getStripeIntentId]);
    } catch (ApiErrorException $e) {
      Message::error($e->getMessage());
      return false;
    }
  }
}
