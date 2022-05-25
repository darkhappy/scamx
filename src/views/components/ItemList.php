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
?>
<div id="<?= $id ?>" class="text-white p-4 flex flex-row justify-between">
  <div>
    <h1 class="font-bold text-2xl"><?= $name ?></h1>
    <p class="text-slate-300"><span class="font-medium"><?= $price ?></span> vbuks</p>
  </div>
  <div class="flex flex-col text-right text-amber-300 font-bold text-xl">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $id ?>">View</a>
    <?php if (Security::ownsItem($id)) { ?>
      <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>">Edit</a>
      <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>">Delete</a>
    <?php } ?>
  </div>
</div>