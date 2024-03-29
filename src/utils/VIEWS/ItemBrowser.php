<?php

namespace utils\views;

use JetBrains\PhpStorm\ArrayShape;
use models\Item;
use repositories\ItemRepository;

class ItemBrowser
{
  #[
    ArrayShape([
      "items" => "\models\Item[]",
      "page" => "float|int",
      "itemsPerPage" => "int",
      "itemsToShow" => "mixed",
      "offset" => "float|int",
      "count" => "bool|int",
    ])
  ]
  public static function showList(string $getParam, int $itemsPerPage): array {
    $count = static::getCount();
    $page = intval($_GET[$getParam] ?? 1);

    if ($count === 0) {
      return [
        "items" => [],
        "page" => 0,
        "itemsPerPage" => $itemsPerPage,
        "itemsToShow" => 0,
        "offset" => 0,
        "count" => 0,
      ];
    }

    // Prevent negative page numbers
    if ($page < 1) {
      $page = 1;
    }
    // Prevent page numbers greater than the number of pages
    elseif ($page * $itemsPerPage > $count) {
      $page = ceil($count / $itemsPerPage);
    }

    // Calculate the offset for the current page
    $offset = ($page - 1) * $itemsPerPage;

    // Calculate the number of items to show on the current page
    $itemsToShow = min($itemsPerPage, $count - $offset);

    // Get the items for the current page
    $items = static::getItems($itemsToShow, $offset);
    return [
      "items" => $items,
      "page" => $page,
      "itemsPerPage" => $itemsPerPage,
      "itemsToShow" => $itemsToShow,
      "offset" => $offset,
      "count" => $count,
    ];
  }

  protected static function getCount(ItemRepository $repo = new ItemRepository()): bool|int
  {
    return $repo->getItemCount();
  }

  /**
   * @return Item[]
   */
  protected static function getItems(int $itemsToShow, int $offset, ItemRepository $repo = new ItemRepository()): array
  {
    return $repo->getItems($itemsToShow, $offset);
  }
}
