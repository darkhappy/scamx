<?php

namespace views\components;

use models\Item;
use repositories\ItemRepository;
use utils\Session;

class SellerBrowser extends ItemBrowser
{
  protected static function getCount(ItemRepository $repo = new ItemRepository()): bool|int
  {
    $user = Session::getUser();
    return $repo->getItemCountFromVendor($user->getId());
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset, ItemRepository $repo = new ItemRepository()): array
  {
    $user = Session::getUser();
    return $repo->getItemsFromVendor($user->getId(), $itemsToShow, $offset);
  }
}
