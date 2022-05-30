<?php

namespace utils\views;

use models\Item;
use repositories\TransactionRepository;
use utils\Session;

class TransactionBrowser extends ItemBrowser
{
  // has repo because it is used in the parent class, even though it is not used here
  protected static function getCount($repo = null): bool|int
  {
    $user = Session::getUser();
    // Get the count of all items that is in a transaction that the user owns
    return TransactionRepository::getVendorTransactionsCount($user->getId());
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset, $repo = null): array
  {
    $user = Session::getUser();
    return TransactionRepository::getVendorTransactions($user->getId(), $itemsToShow, $offset);
  }
}
