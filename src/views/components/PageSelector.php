<?php

/**
 * @var $offset
 * @var $itemsToShow
 * @var $count
 * @var $itemsPerPage
 * @var $page
 * @var $getParam
 */

$path = "?$getParam=";
$lower = $count == 0 ? $offset : $offset + 1;
$upper = $offset + $itemsToShow;
?>

<div class="my-4">
  <p class="font-medium"><?= $lower ?> à <?= $upper ?> produits affichés sur <?= $count ?>. </p>
  <div class="flex flex-row gap-8 font-bold">
    <?php if ($page > 1) { ?>
      <a href="<?= $path . $page - 1 ?>">Précédent</a>
    <?php } ?>

    <?php if ($count > $itemsPerPage * $page) { ?>
      <a href="<?= $path . $page + 1 ?>">Suivant</a>
    <?php } ?>
  </div>
</div>
