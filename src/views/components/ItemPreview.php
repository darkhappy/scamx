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
$price = number_format($price, 2);

$color = "bg-blue-600";
if (Security::ownsItem($id)) {
  $color = "bg-violet-600";
}
?>
<div id="<?= $id ?>" class="<?= $color ?> p-4 break-all rounded-3xl text-white">
  <div class="text-center">
    <h2 class="font-bold text-2xl"><?= $name ?></h2>
    <p class="text-slate-300 font-mono">CAD$<span class="text-white"><?= $price ?></span></p>
  </div>
  <img src="<?= HOME_PATH . "src/assets/uploads/" . $image ?>" alt="<?= $name ?>" class="my-4 rounded-3xl">
  <div class="flex flex-col font-medium text-black gap-3">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $id ?>"
       class="bg-white py-2 px-3 rounded-3xl hover:bg-blue-400 transition-colors">View details</a>
    <?php if (Security::ownsItem($id)) { ?>
      <div class="grid grid-cols-2 justify-between gap-3">
        <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>"
           class="bg-white py-2 px-3 rounded-3xl hover:bg-green-400 transition-colors">Edit</a>
        <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>"
           class="bg-white py-2 px-3 rounded-3xl hover:bg-red-400 transition-colors">Delete</a>
      </div>
    <?php } ?>
  </div>
</div>