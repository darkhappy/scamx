<?php
/**
 * @var $transaction Transaction
 */

use models\Transaction;
use repositories\ItemRepository;
use repositories\UserRepository;
use utils\Market;
use utils\Security;
use utils\Session;

$id = $transaction->getId();
$transactionStatus = $transaction->getStatus();

$user = Session::getUser();
$userRepo = new UserRepository();
$client = $userRepo->getById($transaction->getClientId());
$clientName = $client->getUsername();

$itemId = $transaction->getItemId();
$itemRepo = new ItemRepository();
$item = $itemRepo->getById($itemId);
$itemName = $item->getName();

$transactionPrice = $item->getPrice();
$transactionPrice = Market::calculateProfit($transactionPrice);

$itemName = Security::sanitize($itemName);
$clientName = Security::sanitize($clientName);
$transactionPrice = number_format($transactionPrice, 2);
$transactionStatus = ucfirst($transactionStatus);
?>
<div id="<?= $id ?>" class="text-white p-4 flex flex-row justify-between items-center">
  <div>
    <h1 class="font-bold text-2xl"><?= $itemName ?></h1>
    <h2 class="font-medium text-xl"><?= $transactionStatus ?></h2>
    <p>Sold to <?= $clientName ?> for CAD$<?= $transactionPrice ?> after fees. </p>
  </div>
  <div class="flex flex-col text-right text-amber-300 font-bold text-xl grow">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $itemId ?>">View item</a>
    <?php if ($transactionStatus === "Paid") { ?>
      <a href="<?= HOME_PATH ?>market/confirm?id=<?= $id ?>">Confirm</a>
      <a href="<?= HOME_PATH ?>market/refund?id=<?= $id ?>">Cancel</a>
    <?php } ?>
  </div>
</div>