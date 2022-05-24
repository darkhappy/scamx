<?php
/**
 * @var $item Item
 */

use models\Item;

$id = $item->getId();
$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();
$image = $item->getImage();
?>
<div id="<?= $id ?>" class="bg-blue-600 p-4">
  <h1 class="font-bold text-2xl text-white"><?= $name ?></h1>
  <p class="text-slate-300"><span class="text-white font-medium"><?= $price ?></span> vbuks</p>
  <img src="<?= HOME_PATH . "src/assets/images/" . $image ?>" alt="<?= $name ?>" class="my-4">
  <a href="<?= HOME_PATH ?>market/info?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">View details</a>
</div>