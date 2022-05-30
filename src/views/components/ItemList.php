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
$price = number_format($price, 2);
$image = $item->getImage();

$name = Security::sanitize($name);
$description = Security::sanitize($description);
?>
<div id="<?= $id ?>" class="text-white py-4 flex flex-row justify-between break-all">
  <div>
    <h1 class="font-bold text-2xl"><?= $name ?></h1>
    <h2 class="font-medium text-xl mb-2 font-mono">CAD$<?= $price ?></h2>
  </div>
  <div class="flex flex-col text-right text-yellow-400 font-bold text-xl">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $id ?>" class="hover:text-yellow-600 transition-colors">Plus
                                                                                                        d'information</a>
    <?php if (Security::ownsItem($id)) { ?>
      <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>" class="hover:text-yellow-600 transition-colors">Modifier</a>
      <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>" class="hover:text-red-700 transition-colors">Supprimer</a>
    <?php } ?>
  </div>
</div>