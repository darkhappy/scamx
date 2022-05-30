<?php

namespace views\components;

use models\Item;
use repositories\TransactionRepository;
use utils\Session;

class BoughtItemsBrowser extends ItemBrowser
{
  // Repo is only there since it's in the parent class
  // please don't delete it
  protected static function getCount($repo = null): bool|int
  {
    $user = Session::getUser();
    return TransactionRepository::getClientTransactionsCount($user->getId());
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset, $repo = null): array
  {
    $user = Session::getUser();
    return TransactionRepository::getClientTransactions($user->getId(), $itemsToShow, $offset);
  }
}
