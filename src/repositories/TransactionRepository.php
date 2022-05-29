<?php

namespace repositories;

use models\Transaction;
use PDO;

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

  public static function getById(string $itemId)
  {
    $query = DATABASE->prepare("SELECT * FROM transactions WHERE id = ?");
    $query->bindValue(1, $itemId, PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_CLASS, Transaction::class);
    return $query->fetch();
  }

  public static function getVendorTransactionsCount(string $getId)
  {
    $query = DATABASE->prepare(
      "SELECT COUNT(*) FROM transactions WHERE vendor_id = ?"
    );
    $query->bindValue(1, $getId);
    $query->execute();
    return $query->fetchColumn();
  }

  public static function getVendorTransactions(
    string $getId,
    int $itemsToShow,
    int $offset
  ): bool|array {
    $query = DATABASE->prepare(
      "SELECT * FROM transactions WHERE vendor_id = ? LIMIT ? OFFSET ?"
    );
    $query->bindValue(1, $getId, PDO::PARAM_INT);
    $query->bindValue(2, $itemsToShow, PDO::PARAM_INT);
    $query->bindValue(3, $offset, PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_CLASS, Transaction::class);
    return $query->fetchAll();
  }

  public static function getClientTransactionsCount(string $getId)
  {
    $query = DATABASE->prepare(
      "SELECT COUNT(*) FROM transactions WHERE client_id = ?"
    );
    $query->bindValue(1, $getId);
    $query->execute();
    return $query->fetchColumn();
  }

  public static function getClientTransactions(
    string $getId,
    int $itemsToShow,
    int $offset
  ) {
    $query = DATABASE->prepare(
      "SELECT * FROM transactions WHERE client_id = ? LIMIT ? OFFSET ?"
    );
    $query->bindValue(1, $getId, PDO::PARAM_INT);
    $query->bindValue(2, $itemsToShow, PDO::PARAM_INT);
    $query->bindValue(3, $offset, PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_CLASS, Transaction::class);
    return $query->fetchAll();
  }

  public static function updateStatus(Transaction $transaction)
  {
    $query = DATABASE->prepare(
      "UPDATE transactions SET status = ? WHERE id = ?"
    );
    $query->bindValue(1, $transaction->getStatus());
    $query->bindValue(2, $transaction->getId());
    $query->execute();
  }
}
