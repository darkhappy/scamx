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
          class="shadow appearance-none border w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline"
          id="username" name="username" type="text">
      </div>
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="password">Mot de passe</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="password" name="password" type="password">
      </div>
    </div>
    <div class="flex items-center justify-between gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Connecter
      </button>
      <a class="text-slate-300 hover:text-amber-400 font-medium"
         href="<?= HOME_PATH ?>user/register">Se créer un compte...</a>
    </div>
  </form>
</div>
