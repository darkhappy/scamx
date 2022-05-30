<?php

use utils\Security;
use utils\Session;

$user = Session::getUser();
$username = $user->getUsername();
$username = Security::sanitize($username);
?>

<?php require __DIR__ . "/../components/Message.php"; ?>
<h1 class="text-4xl mb-3">Bienvenue, <?= $username ?>!</h1>
<div class="flex flex-row gap-4 text-xl">
  <a href="<?= HOME_PATH ?>user/reset">Changer le mot de passe</a>
  <a href="<?= HOME_PATH ?>user/logout">Se déconnecter</a>
</div>
