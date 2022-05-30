<?php

use utils\views\BoughtItemsBrowser;
use utils\views\SellerBrowser;
use utils\views\TransactionBrowser;
?>
<?php require __DIR__ . "/../components/Title.php"; ?>
<?php require __DIR__ . "/../components/Message.php"; ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 ">
  <div class="bg-blue-600 p-8 text-white rounded-3xl">
    <h1 class="text-3xl font-bold">Produits en ventes</h1>
    <a href="<?= HOME_PATH ?>market/add" class="text-yellow-300 font-medium">Add Item</a>
    
    <?php
    $getParam = "items";
    $browser = SellerBrowser::showList($getParam, 6);
    extract($browser);
    require __DIR__ . "/../components/PageSelector.php";
    ?>
    
    <div class="grid grid-cols-1 divide-y-2 my-2">
      <?php foreach ($items as $item) {
        include __DIR__ . "/../components/ItemList.php";
      } ?>
    </div>
  </div>
  <div class="flex flex-col justify-between text-white gap-4">
    <div class="p-8 bg-violet-600  rounded-3xl my-2">
      <h1 class="text-3xl font-bold">Ventes</h1>
      <?php
      $getParam = "transactions";
      $browser = TransactionBrowser::showList($getParam, 2);
      extract($browser);
      require __DIR__ . "/../components/PageSelector.php";
      ?>
      
      <div class="grid grid-cols-1 divide-y my-2">
        <?php foreach ($items as $transaction) {
          include __DIR__ . "/../components/TransactionList.php";
        } ?>
      </div>
    </div>
    <div class="p-8 bg-indigo-600 rounded-3xl ">
      <h1 class="text-3xl font-bold">Achats</h1>
      <?php
      $getParam = "bought";
      $browser = BoughtItemsBrowser::showList($getParam, 2);
      extract($browser);
      require __DIR__ . "/../components/PageSelector.php";
      ?>
      
      <div class="grid grid-cols-1 divide-y-2">
        <?php foreach ($items as $transaction) {
          include __DIR__ . "/../components/BoughtItemsList.php";
        } ?>
      </div>
    </div>
  </div>
</div>
