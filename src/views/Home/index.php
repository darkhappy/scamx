<?php use repositories\ItemRepository;

?>
<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>

  <h1>lets buy things</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
    <?php
    $items = ItemRepository::getAll();

    foreach ($items as $item)
      include __DIR__ . "/../components/ItemPreview.php";
    ?>
  </div>
</div>