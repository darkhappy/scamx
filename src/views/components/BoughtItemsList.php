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

$vendor = UserRepository::getById($transaction->getVendorId());
$vendorName = $vendor->getUsername();

$itemId = $transaction->getItemId();
$item = ItemRepository::getById($itemId);
$itemName = $item->getName();

$itemName = Security::sanitize($itemName);
$vendorName = Security::sanitize($vendorName);
$transactionPrice = Security::sanitize($transactionPrice);
$transactionStatus = ucfirst($transactionStatus);
$date = $transaction->getDate();
?>
<div id="<?= $id ?>" class="text-white p-4 flex flex-row justify-between items-center">
  <div>
    <h1 class="font-bold text-2xl"><?= $itemName ?></h1>
    <h2 class="font-medium text-xl"><?= $transactionStatus ?></h2>
    <p>Bought from <?= $vendorName ?> for CAD$<?= $transactionPrice ?> </p>
    <p>Last updated: <?= $date ?></p>
  </div>
  <div class="flex flex-col text-right text-amber-300 font-bold text-xl">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $itemId ?>">View item</a>
  </div>
</div>