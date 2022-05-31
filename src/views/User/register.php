<?php use Utils\Security; ?>
<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form">
    <input type="hidden" hidden name="csrf" value="<?= Security::generateCSRFToken("register") ?>">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="username">Nom
                                                                                                         d'utilisateur</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="username" name="username" type="text" required>
        <p class="text-gray-600 text-xs italic">Un nom d'utilisateur valide est entre 3 et 20 caractères, et contient
                                                seulement des lettres, des chiffres et des tirets.</p>
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">Courriel</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="email" name="email" type="email" required>
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="password">Mot de
                                                                                                         passe</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="password" name="password" type="password" required>
      </div>
      <div class="mb-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="confirm">Confirmation du
                                                                                                        mot de
                                                                                                        passe</label>
        <input
          class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-100 transition-colors"
          id="confirm" name="confirm" type="password" required>
      </div>
    </div>
    <div class="flex items-center gap-8">
      <button
        class="bg-blue-500 hover:bg-blue-700 transition-colors text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit">
        S'inscrire
      </button>
      <a class="inline-block align-baseline transition-colors font-bold text-sm text-blue-500 hover:text-blue-800"
         href="<?= HOME_PATH ?>user/login">
        Retour à la page de connexion
      </a>
    </div>
  </form>
</div>
