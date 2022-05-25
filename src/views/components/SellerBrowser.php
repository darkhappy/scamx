<?php

namespace views\components;

use models\Item;
use repositories\ItemRepository;
use utils\Session;

class SellerBrowser extends ItemBrowser
{
  protected static function getCount(): bool|int
  {
    $user = Session::getUser();
    return ItemRepository::getItemCountFromVendor($user->getId());
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset): array
  {
    $user = Session::getUser();
    return ItemRepository::getItemsFromVendor(
      $user->getId(),
      $itemsToShow,
      $offset
    );
  }
}
