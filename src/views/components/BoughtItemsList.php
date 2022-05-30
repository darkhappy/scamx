<?php
/**
 * @var $transaction Transaction
 */

use models\Transaction;
use repositories\ItemRepository;
use repositories\UserRepository;
use utils\Security;
use utils\Session;

$id = $transaction->getId();
$transactionPrice = $transaction->getPrice();
$transactionStatus = $transaction->getStatus();

$user = Session::getUser();

$userRepo = new UserRepository();
$vendor = $userRepo->getById($transaction->getVendorId());
$vendorName = $vendor->getUsername();

$itemId = $transaction->getItemId();

$itemRepo = new ItemRepository();
$item = $itemRepo->getById($itemId);
$itemName = $item->getName();

$itemName = Security::sanitize($itemName);
$vendorName = Security::sanitize($vendorName);
$transactionPrice = Security::sanitize($transactionPrice);
$transactionStatus = ucfirst($transactionStatus);
$date = $transaction->getDate();

$transactionStatus = match ($transactionStatus) {
  "Refunded" => "Remboursé",
  "Confirmed" => "Confirmé",
  default => "Acheté",
};
?>
<div id="<?= $id ?>" class="text-white py-4 flex flex-row justify-between items-center">
  <div>
    <h1 class="font-bold text-2xl"><?= $itemName ?></h1>
    <h2 class="font-medium text-xl mb-2"><?= $transactionStatus ?> - <span
        class="font-mono">CAD$<?= $transactionPrice ?></span></h2>
    <p>Acheté de <?= $vendorName ?>  </p>
    <p>Dernière modification: <?= $date ?></p>
  </div>
  <div class="flex flex-col text-right text-amber-300 font-bold text-xl">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $itemId ?>" class="hover:text-yellow-600 transition-colors">Voir le
                                                                                                            produit</a>
    <?php if ($transactionStatus === "Acheté") { ?>
      <a href="<?= HOME_PATH ?>market/refund?id=<?= $id ?>" class="hover:text-red-700 transition-colors">Annuler</a>
    <?php } ?>
  </div>
</div>