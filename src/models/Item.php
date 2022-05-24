<?php

namespace models;

class Item
{
  private string $id;
  private string $name;
  private string $description;
  private float $price;
  private string $image;
  private string $creationDate;

  /**
   * @return string
   */
  public function getCreationDate(): string
  {
    return $this->creationDate;
  }

  /**
   * @param string $creationDate
   */
  public function setCreationDate(string $creationDate): void
  {
    $this->creationDate = $creationDate;
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @param string $id
   */
  public function setId(string $id): void
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * @param string $description
   */
  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  /**
   * @return float
   */
  public function getPrice(): float
  {
    return $this->price;
  }

  /**
   * @param float $price
   */
  public function setPrice(float $price): void
  {
    $this->price = $price;
  }

  /**
   * @return string
   */
  public function getImage(): string
  {
    return $this->image;
  }

  /**
   * @param string $image
   */
  public function setImage(string $image): void
  {
    $this->image = $image;
  }
}