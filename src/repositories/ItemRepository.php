<?php

namespace repositories;

use models\Item;
use PDO;

class ItemRepository
{
  public function getById(int $id): Item|bool
  {
    $query = DATABASE->prepare("SELECT * FROM items WHERE id = ?");
    $query->bindValue(1, $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchObject(Item::class);
  }

  public function getItems(int $amount, int $offset): array|false
  {
    $query = DATABASE->prepare("SELECT * FROM items WHERE hidden = 0 LIMIT ? OFFSET ?");
    $query->bindValue(1, $amount, PDO::PARAM_INT);
    $query->bindValue(2, $offset, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_CLASS, Item::class);
  }

  public function getItemCount(): int|bool
  {
    $query = DATABASE->prepare("SELECT COUNT(*) FROM items WHERE hidden = 0");
    $query->execute();
    return $query->fetchColumn();
  }

  public function getItemsFromVendor(int $vendorId, int $amount, int $offset): array
  {
    $query = DATABASE->prepare("SELECT * FROM items WHERE vendorId = ? AND hidden = 0 LIMIT ? OFFSET ?");
    $query->bindValue(1, $vendorId, PDO::PARAM_INT);
    $query->bindValue(2, $amount, PDO::PARAM_INT);
    $query->bindValue(3, $offset, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_CLASS, Item::class);
  }

  public function getItemCountFromVendor(int $vendorId): int|bool
  {
    $query = DATABASE->prepare("SELECT COUNT(*) FROM items WHERE vendorId = ? AND hidden = 0");
    $query->bindValue(1, $vendorId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchColumn();
  }

  public function insert(Item $item): void
  {
    $query = DATABASE->prepare(
      "INSERT INTO items (name, description, image, price, creationDate, vendorId) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $query->bindValue(1, $item->getName());
    $query->bindValue(2, $item->getDescription());
    $query->bindValue(3, $item->getImage());
    $query->bindValue(4, $item->getPrice());
    $query->bindValue(5, $item->getCreationDate());
    $query->bindValue(6, $item->getVendorId());
    $query->execute();
  }

  public function edit(Item $item): bool
  {
    $query = DATABASE->prepare("UPDATE items SET name = ?, description = ?, image = ?, price = ? WHERE id = ?");
    $query->bindValue(1, $item->getName());
    $query->bindValue(2, $item->getDescription());
    $query->bindValue(3, $item->getImage());
    $query->bindValue(4, $item->getPrice());
    $query->bindValue(5, $item->getId());
    return $query->execute();
  }

  public function delete(Item $item): void
  {
    // Check if the item is in use in a transaction
    if (TransactionRepository::getByItemId($item->getId()) !== false) {
      $query = DATABASE->prepare("UPDATE items SET hidden = 1 WHERE id = ?");
    } else {
      $query = DATABASE->prepare("DELETE FROM items WHERE id = ?");
    }

    $query->bindValue(1, $item->getId());
    $query->execute();
  }
}
