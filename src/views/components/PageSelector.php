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

<p>Showing <?= $lower ?> to <?= $upper ?> of <?= $count ?> items. </p>
<div class="flex flex-row gap-8">
  <?php if ($page > 1) { ?>
    <a href="<?= $path . $page - 1 ?>">Previous</a>
  <?php } ?>
  
  <?php if ($count > $itemsPerPage * $page) { ?>
    <a href="<?= $path . $page + 1 ?>">Next</a>
  <?php } ?>
</div>
