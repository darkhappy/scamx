<?php
/**
 * @var $item Item
 */

use models\Item;
use repositories\UserRepository;
use utils\Security;

$id = $item->getId();
$name = $item->getName();
$description = $item->getDescription();
$price = $item->getPrice();
$price = number_format($price, 2);
$image = $item->getImage();

$userRepo = new UserRepository();
$user = $userRepo->getById($item->getVendorId());
$username = $user->getUsername();

$name = Security::sanitize($name);
$description = Security::sanitize($description);
$username = Security::sanitize($username);

$color = "bg-blue-600";
if (Security::ownsItem($id)) {
  $color = "bg-violet-600";
}

$ownItem = Security::ownsItem($id);
require __DIR__ . "/../components/Message.php";
?>
<div id="<?= $id ?>"
     class="flex flex-col md:flex-row gap-y-4 gap-x-8 p-4 <?= $color ?> rounded-3xl text-white items-center">
  <div class="<?= $color ?> rounded-3xl">
    <img src="<?= HOME_PATH . "src/assets/uploads/" . $image ?>" alt="<?= $name ?>" class="rounded-3xl" />
  </div>
  <div class="<?= $color ?> flex-grow rounded-3xl flex flex-col justify-between gap-4">
    <div>
      <h1 class="font-bold text-6xl text-white break-all"><?= $name ?></h1>
      <?php if ($ownItem) { ?>
        <p class="text-slate-300 text-3xl">Ce produit vous appartient.</p>
      <?php } else { ?>
        <p class="text-slate-300 text-3xl">Vendu par <?= $username ?></p>
      <?php } ?>
    </div>
    <div class="grow">
      <p class="text-white text-xl text-wrap break-words"><?= $description ?></p>
    </div>
    <p class="text-4xl font-mono text-wrap break-all">CAD$ <span
        class="text-4xl md:text-6xl font-medium"><?= $price ?></span></p>
    <div class="flex flex-row gap-8">
      <?php if ($ownItem) { ?>
        <a href="<?= HOME_PATH ?>market/edit?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Modifier</a>
        <a href="<?= HOME_PATH ?>market/delete?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Supprimer</a>
      <?php } else { ?>
        <a href="<?= HOME_PATH ?>market/buy?id=<?= $id ?>" class="text-yellow-300 font-bold text-xl">Acheter</a>
      <?php } ?>
      <a href="<?= HOME_PATH ?>" class="text-yellow-300 font-bold text-xl">Retour</a>
    </div>
  </div>
</div>
