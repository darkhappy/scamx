<?php

namespace models;

class Transaction
{
  private int $id;
  private int $client_id;
  private int $item_id;
  private int $vendor_id;
  private float $price;
  private string $status;
  private string $date;
  private string $stripe_intent_id;

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }

  /**
   * @return int
   */
  public function getClientId(): int
  {
    return $this->client_id;
  }

  /**
   * @param int $client_id
   */
  public function setClientId(int $client_id): void
  {
    $this->client_id = $client_id;
  }

  /**
   * @return int
   */
  public function getItemId(): int
  {
    return $this->item_id;
  }

  /**
   * @param int $item_id
   */
  public function setItemId(int $item_id): void
  {
    $this->item_id = $item_id;
  }

  /**
   * @return int
   */
  public function getVendorId(): int
  {
    return $this->vendor_id;
  }

  /**
   * @param int $vendor_id
   */
  public function setVendorId(int $vendor_id): void
  {
    $this->vendor_id = $vendor_id;
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
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * @param string $status
   */
  public function setStatus(string $status): void
  {
    $this->status = $status;
  }

  /**
   * @return string
   */
  public function getDate(): string
  {
    return $this->date;
  }

  /**
   * @param string $date
   */
  public function setDate(string $date): void
  {
    $this->date = $date;
  }

  /**
   * @return string
   */
  public function getStripeIntentId(): string
  {
    return $this->stripe_intent_id;
  }

  /**
   * @param string $stripe_intent_id
   */
  public function setStripeIntentId(string $stripe_intent_id): void
  {
    $this->stripe_intent_id = $stripe_intent_id;
  }
}
