<?php

namespace utils;

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
    $output = self::watermark($image);

    imagejpeg($output, $target_file);

    if (file_exists($target_file)) {
      return $name . ".jpeg";
    } else {
      return false;
    }
  }

  public static function watermark(array $image): GdImage
  {
    // Check mime type
    if ($image["type"] == "image/png") {
      $img = imagecreatefrompng($image["tmp_name"]);
    } else {
      $img = imagecreatefromjpeg($image["tmp_name"]);
    }

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

    $newImage = imagecrop($img, [
      "x" => $srcX,
      "y" => $srcY,
      "width" => $width,
      "height" => $height,
    ]);

    // Scale the image to 500x500
    $newImage = imagescale($newImage, $size, $size);

    // Add text to the center of the image
    // Also it has a stroke of 3 that's black to make it more visible
    $textColor = imagecolorallocate($newImage, 255, 255, 255);
    $text = "stockx.com";
    $fontsize = 44;
    $font = __DIR__ . "/comic.ttf";
    $stroke = 5;
    $strokeColor = imagecolorallocate($newImage, 0, 0, 0);

    // Put the text to the center of the image
    $textBox = imagettfbbox($fontsize, 0, $font, $text);
    $textWidth = $textBox[2] - $textBox[0];
    $textHeight = $textBox[1] - $textBox[7];
    $textX = (int) (round($size - $textWidth) / 2);
    $textY = (int) (($size - $textHeight) / 2);

    // Add the text
    imagettftext(
      $newImage,
      $fontsize,
      0,
      $textX,
      $textY,
      $textColor,
      $font,
      $text
    );

    // Add the stroke
    imagettftext(
      $newImage,
      $fontsize,
      0,
      $textX - $stroke,
      $textY - $stroke,
      $strokeColor,
      $font,
      $text
    );

    return $newImage;
  }

  public static function delete(string $oldImage): void
  {
    $target = __DIR__ . "/../assets/uploads/" . $oldImage;
    if (file_exists($target)) {
      unlink($target);
    }
  }
}
