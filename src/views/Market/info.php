<?php
/**
 * @var $item Item
 */

use models\Item;
use repositories\UserRepository;
use utils\Security;

$id = $item->getId();
$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();
$image = $item->getImage();
$user = UserRepository::getById($item->getVendorId());
$username = $user->getUsername();

$name = Security::sanitize($name);
$description = Security::sanitize($description);
$username = Security::sanitize($username);

$color = "bg-blue-600";
if (Security::ownsItem($id)) {
  $color = "bg-teal-600";
}

$ownItem = Security::ownsItem($id);
?>
<div id="<?= $id ?>" class="flex flex-row gap-4 text-white">
  <div class="<?= $color ?> p-4 rounded-3xl">
    <img src="<?= HOME_PATH .
      "src/assets/uploads/" .
      $image ?>" alt="<?= $name ?>" class="max-h-96 rounded-3xl" />
  </div>
  <div class="<?= $color ?> flex-grow rounded-3xl p-8 flex flex-col justify-between gap-4">
    <div>
      <h1 class="font-bold text-6xl text-white"><?= $name ?></h1>
      <?php if ($ownItem) { ?>
        <p class="text-slate-600 text-3xl">You own this item.</p>
      <?php } else { ?>
        <p class="text-white text-3xl">Sold by <?= $username ?></p>
      <?php } ?>
    </div>
    <div class="grow">
      <p class="text-white text-xl"><?= $description ?></p>
    </div>
    <p class="text-4xl font-mono">CAD$<span class="text-6xl font-medium"><?= $price ?></span></p>
    <div class="flex flex-row gap-8">
      <?php if ($ownItem) { ?>
        <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Edit</a>
        <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Delete</a>
      <?php } else { ?>
        <a href="<?= HOME_PATH ?>market/buy?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Buy</a>
      <?php } ?>
      <a href="<?= HOME_PATH ?>" class="text-yellow-300 font-bold text-xl">Return</a>
    </div>
  </div>
</div>
