<?php

use models\Item;
use Utils\Security;

/**
 * @var Item $item
 */

$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();
?>

<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= Security::generateCSRFToken(
      "add"
    ) ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="name">Product Name</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="name" name="name" type="text" value="<?= $name ?>">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="description">Description</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="description" name="description" type="text" value="<?= $description ?>">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="price">Price</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="price" name="price" type="number" step="any" value="<?= $price ?>">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="image">Image (leaving blank will keep default)</label>
        <input
          class="appearance-none py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="image" name="image" type="file" accept=".jpg, .jpeg, .png">
      </div>
    </div>
    <div class="flex justify-between items-center gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Add
      </button>
      <a class="text-black px-5 py-2 rounded font-medium" href="/market">Cancel</a>
    </div>
  </form>
</div>
