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
$date = $transaction->getDate();

$itemName = Security::sanitize($itemName);
$clientName = Security::sanitize($clientName);
$transactionPrice = number_format($transactionPrice, 2);
$transactionStatus = ucfirst($transactionStatus);

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
        class="font-mono">CAD$<?= $transactionPrice ?></span><span class="text-sm"> après les frais</span></h2>
    <p>Vendu à <?= $clientName ?>  </p>
    <p>Dernière modification: <?= $date ?></p>
  </div>
  <div class="flex flex-col text-right text-yellow-400 font-bold text-xl grow">
    <a href="<?= HOME_PATH ?>market/info?id=<?= $itemId ?>" class="hover:text-yellow-600 transition-colors">Voir le
                                                                                                            produit</a>
    <?php if ($transactionStatus === "Acheté") { ?>
      <a href="<?= HOME_PATH ?>market/confirm?id=<?= $id ?>"
         class="hover:text-green-700 transition-colors">Confirmer</a>
      <a href="<?= HOME_PATH ?>market/refund?id=<?= $id ?>" class="hover:text-red-700 transition-colors">Annuler</a>
    <?php } ?>
  </div>
</div>