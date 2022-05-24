<?php use utils\Session; ?>

<div class="py-4 px-8 bg-green-500 text-white font-medium flex flex-row items-center justify-between">
  <a href="<?= HOME_PATH ?>" class="text-3xl">SCAM X BABY</a>

  <div class="text-xl font-normal flex gap-4">
    <?php if (Session::isLogged()) : ?>
      <a href="<?= HOME_PATH ?>market/dashboard">Dashboard</a>
      <a href="<?= HOME_PATH ?>user/account">Profile</a>
    <?php else : ?>
      <a href="<?= HOME_PATH ?>user/login">Login</a>
      <a href="<?= HOME_PATH ?>user/register">Register</a>
    <?php endif; ?>
  </div>
</div>
