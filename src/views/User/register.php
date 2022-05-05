<div>
  <?php if (!empty($errMsg)) { ?>
    <p class="my-4 text-rose-500">
      <?= $errMsg ?>
    </p>
  <?php } ?>
  <form method="post">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="username">Username</label>
        <input
                class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="username" name="username" type="text">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="password">Mot de passe</label>
        <input
                class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="password" name="password" type="password">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="confirm">Confirmation du mot de passe</label>
        <input
                class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="confirm" name="confirm" type="password">
      </div>
    </div>
    <div class="flex justify-between items-center gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Créer un
                                                                                                            compte
      </button>
      <a class="text-slate-300 hover:text-amber-400 font-medium"
         href="<?= HOME_PATH ?>user/login">Retour</a>
    </div>
  </form>
</div>
