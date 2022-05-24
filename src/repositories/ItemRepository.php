<?php

namespace repositories;

use models\Item;
use PDO;

class ItemRepository
{

  public static function getById(int $id): Item|bool
  {
    $query = DATABASE->prepare("SELECT * FROM items WHERE id = ?");
    $query->bindValue(1, $id);
    $query->execute();
    return $query->fetchObject(Item::class);
  }

  /**
   * @return array<Item>
   */
  public static function getAll(): array
  {
    $query = DATABASE->prepare("SELECT * FROM items");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_CLASS, Item::class);
  }

  public static function insert(Item $item): bool
  {
    $query = DATABASE->prepare("INSERT INTO items (name, description, price, image, creationDate, vendor) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bindValue(1, $item->getName());
    $query->bindValue(2, $item->getDescription());
    $query->bindValue(3, $item->getImage());
    $query->bindValue(4, $item->getPrice());
    return $query->execute();
  }


}