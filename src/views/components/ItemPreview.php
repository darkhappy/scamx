<?php
/**
 * @var $item Item
 */

use models\Item;
use utils\Security;

$id = $item->getId();
$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();
$image = $item->getImage();

$name = Security::sanitize($name);
$description = Security::sanitize($description);

$color = "bg-blue-600";
if (Security::ownsItem($id)) {
  $color = "bg-teal-600";
}
?>
<div id="<?= $id ?>" class="<?= $color ?> p-4">
  <h1 class="font-bold text-2xl text-white"><?= $name ?></h1>
  <p class="text-slate-300">CAD$<span class="text-white font-medium"><?= $price ?></span></p>
  <img src="<?= HOME_PATH .
    "src/assets/uploads/" .
    $image ?>" alt="<?= $name ?>" class="my-4">
  <div class="flex flex-col">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">View details</a>
    <?php if (Security::ownsItem($id)) { ?>
      <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Edit</a>
      <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Delete</a>
    <?php } ?>
  </div>
</div>