<?php use Utils\Security; ?>
<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form">
    <input type="hidden" hidden name="csrf" value="<?= Security::generateCSRFToken("forgot_reset") ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="new">Nouveau mot de
                                                                                                    passe</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="new" name="new" type="password">
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="confirm">Confirmation du
                                                                                                        nouveau mot de
                                                                                                        passe</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="confirm" name="confirm" type="password">
      </div>
    </div>
    <div class="flex items-center gap-8">
      <button
        class="bg-blue-500 hover:bg-blue-700 transition-colors text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit">
        Réinitialiser
      </button>
      <a class="inline-block align-baseline transition-colors font-bold text-sm text-blue-500 hover:text-blue-800"
         href="<?= HOME_PATH ?>user/login">
        Retour
      </a>
    </div>
  </form>
</div>
