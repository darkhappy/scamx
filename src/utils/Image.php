<?php

namespace utils;

use Exception;
use GdImage;

class Image
{
  public static function upload(array $image): string|bool
  {
    $target_dir = __DIR__ . "/../assets/uploads/";

    $name = uniqid();

    // Check if, for some reason, the name is already taken
    while (file_exists($target_dir . $name . ".jpeg")) {
      $name = uniqid();
    }

    $target_file = $target_dir . $name . ".jpeg";

    // Apply watermark
    try {
      $output = self::watermark($image);
    } catch (Exception) {
      return false;
    }

    imagejpeg($output, $target_file);

    if (file_exists($target_file)) {
      return $name . ".jpeg";
    } else {
      return false;
    }
  }

  /**
   * @throws Exception
   */
  public static function watermark(array $image): GdImage
  {
    $img = imagecreatefromstring(file_get_contents($image["tmp_name"]));

    // Set image size to 500x500
    $size = 500;
    $width = imagesx($img);
    $height = imagesy($img);

    $srcX = 0;
    $srcY = 0;

    // Crop the image to a square (based on center)
    if ($width > $height) {
      $srcX = ($width - $height) / 2;
      $width = $height;
    } elseif ($height > $width) {
      $srcY = ($height - $width) / 2;
      $height = $width;
    }

    $newImage = imagecrop($img, ["x" => $srcX, "y" => $srcY, "width" => $width, "height" => $height]);

    // Scale the image to 500x500
    $newImage = imagescale($newImage, $size, $size);

    // Add text to the center of the image
    // Also it has a stroke of 3 that's black to make it more visible
    $textColor = imagecolorallocate($newImage, 255, 255, 255);
    $text = "stockx.com";
    $fontsize = 44;
    $font = __DIR__ . "/fonts/comic.ttf";
    $stroke = 5;
    $strokeColor = imagecolorallocate($newImage, 0, 0, 0);

    // Put the text to the center of the image
    $textBox = imagettfbbox($fontsize, 0, $font, $text);
    $textWidth = $textBox[2] - $textBox[0];
    $textHeight = $textBox[1] - $textBox[7];
    $textX = (int) (round($size - $textWidth) / 2);
    $textY = (int) (($size - $textHeight) / 2);

    // Add the text and stroke
    imagettftext($newImage, $fontsize, 0, $textX, $textY, $textColor, $font, $text);
    imagettftext($newImage, $fontsize, 0, $textX - $stroke, $textY - $stroke, $strokeColor, $font, $text);

    return $newImage;
  }

  public static function delete(string $oldImage): void
  {
    $target = __DIR__ . "/../assets/uploads/" . $oldImage;
    if (file_exists($target)) {
      unlink($target);
    }
  }

  public static function isValidImage(array $image): bool
  {
    // Get the MIME type
    $mime = $image["type"];

    // Check if the MIME type is valid
    if (
      !in_array($mime, [
        "image/apng",
        "image/avif",
        "image/gif",
        "image/heic",
        "image/heif",
        "image/jpeg",
        "image/png",
        "image/webp",
      ])
    ) {
      return false;
    }

    // Check file size
    if ($image["size"] > 5000000) {
      return false;
    }

    return true;
  }
}
