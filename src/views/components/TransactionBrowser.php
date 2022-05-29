<?php

namespace views\components;

use models\Item;
use repositories\TransactionRepository;
use utils\Session;

class TransactionBrowser extends ItemBrowser
{
  protected static function getCount(): bool|int
  {
    $user = Session::getUser();
    // Get the count of all items that is in a transaction that the user owns
    return TransactionRepository::getVendorTransactionsCount($user->getId());
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset): array
  {
    $user = Session::getUser();
    return TransactionRepository::getVendorTransactions(
      $user->getId(),
      $itemsToShow,
      $offset
    );
  }
}
