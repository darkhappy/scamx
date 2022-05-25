<?php use views\components\SellerBrowser; ?>
<div class="grid grid-cols-2">
  <div class="bg-blue-600 p-4 text-white stroke-2 stroke-black">
    <h1 class="text-xl">seller</h1>
    <a href="<?= HOME_PATH ?>market/add">add item</a>
    
    <?php
    $getParam = "items";
    $browser = SellerBrowser::showList($getParam, 6);
    extract($browser);

    require __DIR__ . "/../components/PageSelector.php";
    ?>
    
    <div class="grid grid-cols-1 divide-y-4">
      <?php foreach ($items as $item) {
        include __DIR__ . "/../components/ItemList.php";
      } ?>
    </div>
  </div>
  <div class="bg-yellow-300 p-4">
    <h1 class="text-xl">buyer</h1>
    <p>todo</p>
  </div>
</div>
