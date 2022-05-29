<?php

use views\components\SellerBrowser;
use views\components\TransactionBrowser;
?>
<div class="grid grid-cols-2 rounded-3xl bg-blue-400 gap-4">
  <div class="bg-blue-600 p-4 text-white stroke-2 stroke-black rounded-3xl">
    <h1 class="text-3xl font-bold">Items up for sale</h1>
    <a href="<?= HOME_PATH ?>market/add" class="text-yellow-300 font-medium">Add Item</a>
    
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
  <div class="p-4 text-white stroke-2 stroke-black rounded-3xl">
    <h1 class="text-3xl font-bold">Transactions</h1>
    <?php
    $getParam = "transactions";
    $browser = TransactionBrowser::showList($getParam, 6);
    extract($browser);

    require __DIR__ . "/../components/PageSelector.php";
    ?>
    
    <div class="grid grid-cols-1 divide-y-4">
      <?php foreach ($items as $transaction) {
        include __DIR__ . "/../components/TransactionList.php";
      } ?>
    </div>
  </div>
</div>
