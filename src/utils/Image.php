<?php

namespace utils;

class Image
{
  public static function upload(array $image): string|bool
  {
    $target_dir = __DIR__ . "/../assets/uploads/";
    $imageFileType = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

    $name = uniqid();

    // Check if, for some reason, the name is already taken
    while (file_exists($target_dir . $name . "." . $imageFileType)) {
      $name = uniqid();
    }

    $target_file = $target_dir . $name . "." . $imageFileType;

    if (move_uploaded_file($image["tmp_name"], $target_file)) {
      return $name . "." . $imageFileType;
    } else {
      return false;
    }
  }

  public static function watermark(string $image): string
  {
    // TODO: Implement watermark() method.
    return "yuki.png";
  }

  public static function delete(string $oldImage): void
  {
    $target = __DIR__ . "/../assets/uploads/" . $oldImage;
    if (file_exists($target)) {
      unlink($target);
    }
  }
}
