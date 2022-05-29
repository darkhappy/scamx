<?php

namespace repositories;

use models\Transaction;

class TransactionRepository
{
  public static function insert(Transaction $transaction)
  {
    $query = DATABASE->prepare(
      "INSERT INTO transactions (client_id, item_id, vendor_id, price, date, stripe_intent_id) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $query->bindValue(1, $transaction->getClientId());
    $query->bindValue(2, $transaction->getItemId());
    $query->bindValue(3, $transaction->getVendorId());
    $query->bindValue(4, $transaction->getPrice());
    $query->bindValue(5, $transaction->getDate());
    $query->bindValue(6, $transaction->getStripeIntentId());
    $query->execute();
  }
}
