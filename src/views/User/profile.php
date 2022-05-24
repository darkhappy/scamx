<?php

use utils\Session;

$user = Session::getUser();
?>

<h1>whats good <?= $user->getUsername() ?></h1>
<div class="flex flex-col">
  <a href="<?= HOME_PATH ?>user/change_user">change username</a>
  <a href="<?= HOME_PATH ?>user/change_email">change email</a>
  <a href="<?= HOME_PATH ?>user/reset">change password</a>
  <a href="<?= HOME_PATH ?>user/logout">logout</a>
</div>
