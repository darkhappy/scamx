<?php use utils\views\ItemBrowser; ?>
<div>
  <?php require __DIR__ . "/../components/Title.php"; ?>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <?php
  $getParam = "page";
  $browser = ItemBrowser::showList($getParam, 12);
  extract($browser);
  require __DIR__ . "/../components/PageSelector.php";
  ?>
  
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 items-stretch">
    <?php foreach ($items as $item) {
      include __DIR__ . "/../components/ItemPreview.php";
    } ?>
  </div>
</div>