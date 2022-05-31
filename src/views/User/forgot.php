<?php use Utils\Security;
/**
 * @var string $pagetitle
 * @var string $pagesub
 */
?>
<div>
  <h1><?= $pagetitle ?></h1>
  <h2><?= $pagesub ?></h2>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form">
    <input type="hidden" name="csrf" hidden value="<?= Security::generateCSRFToken("forgot") ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">Courriel</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="email" name="email" type="email">
      </div>
    </div>
    <div class="flex items-center gap-8">
      <button
        class="bg-blue-500 hover:bg-blue-700 transition-colors text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit">
        Envoyer
      </button>
      <a class="inline-block align-baseline transition-colors font-bold text-sm text-blue-500 hover:text-blue-800"
         href="<?= HOME_PATH ?>user/login">
        Retour
      </a>
    </div>
  </form>
</div>
