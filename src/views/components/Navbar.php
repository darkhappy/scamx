<?php use utils\Session; ?>

<div class="py-4 px-8 bg-blue-600 text-white font-medium flex flex-row items-center justify-between">
  <a href="<?= HOME_PATH ?>" class="text-3xl">ScamX</a>

  <div class="text-xl font-normal flex gap-4">
    <?php if (Session::isLogged()): ?>
      <a href="<?= HOME_PATH ?>market/dashboard">Tableau de bord</a>
      <a href="<?= HOME_PATH ?>user/profile">Profil</a>
    <?php else: ?>
      <a href="<?= HOME_PATH ?>user/login">Se connecter</a>
      <a href="<?= HOME_PATH ?>user/register">Créer un compte</a>
    <?php endif; ?>
  </div>
</div>
