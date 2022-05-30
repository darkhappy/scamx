<?php

use models\Item;
use Utils\Security;

/**
 * @var Item $item
 */

$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();

/**
 * @var string $pagetitle
 * @var string $pagesub
 */
?>
<div>
  <h1 class="text-3xl text-bold"><?= $pagetitle ?></h1>
  <h2 class="text-xl text-slate-700 text-mnedium mb-4"><?= $pagesub ?></h2>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= Security::generateCSRFToken("add") ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">Nom du
                                                                                                     produit</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="name" name="name" type="text" value="<?= $name ?>" placeholder="Product">
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
               for="description">Description</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="description" name="description" type="text" value="<?= $description ?>"
          placeholder="A product that is amazing">
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price">Prix</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="price" name="price" type="number" step="any" value="<?= $price ?>" placeholder="4.00">
        <p class="text-gray-600 text-xs italic">ScamX prend 5% des profits comme frais. Le prix doit être entre $2.00 et
                                                $100,000.00.</p>
      </div>
      <div class="mb-3">
        <label class="" for="image">
          <p class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Image</p>
          <div class="flex justify-center items-center w-full mt-2">
            <div
              class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100">
              <div class="flex flex-col justify-center items-center pt-5 pb-6">
                <svg class="mb-3 w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="mb-2 text-sm text-gray-500 text-wrap text-center"><span class="font-semibold">Cliquez pour télécharger </span>
                  ou faites glisser et déposez</p>
                <p class="text-xs text-gray-500">JPEG ou PNG (taille de 500x500px recommandé)</p>
              </div>
              <input id="image" name="image" type="file" class="hidden" accept="image/jpeg, image/png" />
            </div>
          </div>
        </label>
      </div>
    </div>
    <div class="flex items-center gap-8">
      <button
        class="bg-blue-500 hover:bg-blue-700 transition-colors text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit">
        Modifier
      </button>
      <a class="inline-block align-baseline transition-colors font-bold text-sm text-blue-500 hover:text-blue-800"
         href="<?= HOME_PATH ?>market">
        Annuler
      </a>
    </div>
  </form>
</div>
