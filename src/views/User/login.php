<?php use utils\Security; ?>
<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form">
    <input type="hidden" hidden name="csrf" value="<?= Security::generateCSRFToken("login") ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="username">Nom d'utilisateur</label>
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
      <div class="mb-3">
        <a class="text-slate-300 hover:text-amber-400 font-medium"
           href="<?= HOME_PATH ?>user/forgot">Oubli de mot de passe</a>
      </div>
      <div class="mb-3">
        <input id="remember" name="remember" type="checkbox">
        <label class="mb-1 font-medium" for="remember">Se souvenir de moi</label>
      </div>
    </div>
    <div class="flex items-center gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Connecter
      </button>
      <a class="text-slate-300 hover:text-amber-400 font-medium"
         href="<?= HOME_PATH ?>user/register">Créer un compte</a>
    </div>
  </form>
</div>
