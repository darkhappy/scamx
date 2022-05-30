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
        <label class="block mb-1 font-medium" for="email">Courriel</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="email" name="email" type="email">
      </div>
    </div>
    <div class="flex justify-between items-center gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Envoyer
      </button>
      <a class="text-slate-300 hover:text-amber-400 font-medium"
         href="<?= HOME_PATH ?>user/login">Retour</a>
    </div>
  </form>
</div>
