<?php
/**
 * @var $item Item
 */

use models\Item;
use repositories\UserRepository;
use utils\Market;
use utils\Security;

$id = $item->getId();
$name = $item->getName();
$description = $item->getDescription();
$image = $item->getImage();
$user = UserRepository::getById($item->getVendorId());
$username = $user->getUsername();

$name = Security::sanitize($name);
$description = Security::sanitize($description);
$username = Security::sanitize($username);

$subtotal = $item->getPrice();
$tps = Market::calculateTPS($subtotal);
$tvq = Market::calculateTVQ($subtotal);
$shipping = Market::calculateShipping($subtotal);
$total = Market::calculateTotal($subtotal);

$color = "bg-blue-600";

require __DIR__ . "/../components/Message.php";
?>
<div class="flex flex-col lg:flex-row gap-4 my-4 text-white">
  <form method="post" class="p-8 bg-blue-600 grow rounded-3xl" id="buy">
    <input type="hidden" aria-hidden="true" hidden name="csrf" value="<?= Security::generateCSRFToken(
      "buy"
    ) ?>">
    <div>
      <h2 class="text-3xl mb-2">Information de l'acheteur</h2>
      <div class="flex flex-col gap-4">
        <div>
          <label for="name" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">Nom
                                                                                                       complet</label>
          <input type="text"
                 class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                 name="name" id="name" required="" autocomplete="name" placeholder="François Legault">
        </div>
        <div>
          <label for="address"
                 class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">Adresse</label>
          <input type="text"
                 class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                 name="address" id="address" required="" autocomplete="street-address"
                 placeholder="1045 Rue des Parlementaires">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="col-span-1 md:col-span-2">
            <label for="city" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">Ville</label>
            <input type="text"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   name="city" id="city" required="" autocomplete="address-level2" placeholder="Québec">
          </div>
          <div>
            <label for="postal" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">Code
                                                                                                           postal</label>
            <input type="text"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   name="postal" id="postal" required="" autocomplete="postal-code" placeholder="G1A 1A3">
          </div>
          <div class="relative">
            <label for="province"
                   class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">Province</label>
            <select
              class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
              name="province" id="province" required="" autocomplete="address-level1">
              <option value="AB">Alberta</option>
              <option value="BC">British Columbia</option>
              <option value="MB">Manitoba</option>
              <option value="NB">New Brunswick</option>
              <option value="NL">Newfoundland and Labrador</option>
              <option value="NS">Nova Scotia</option>
              <option value="ON">Ontario</option>
              <option value="PE">Prince Edward Island</option>
              <option value="QC" selected>Quebec</option>
              <option value="SK">Saskatchewan</option>
              <option value="NT">Northwest Territories</option>
              <option value="NU">Nunavut</option>
              <option value="YT">Yukon</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="my-4">
      <h2 class="text-3xl mb-2">Information de paiement</h2>
      <div class="flex flex-col gap-4">
        <div>
          <label for="ccname" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">
            Name on Card
          </label>
          <input type="text"
                 class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                 name="ccname" id="ccname" required="" autocomplete="cc-name">
        </div>
        <div>
          <label for="cardnumber" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">
            Credit Card Number
          </label>
          <input type="text"
                 class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                 name="cardnumber" id="cardnumber" required=""
                 autocomplete="cc-number">
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label for="expmonth" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">
              Expiration Month
            </label>
            <input type="text"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   name="expmonth" id="expmonth" required="" autocomplete="cc-expmonth">
          </div>
          <div>
            <label for="expyear" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">
              Expiration Year
            </label>
            <input type="text"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   name="expyear" id="expyear" required="" autocomplete="cc-expyear">
          </div>
          <div>
            <label for="cvc2" class="block uppercase tracking-wide text-gray-100 text-xs font-bold mb-2">
              CVC Code
            </label>
            <input type="text"
                   class="appearance-none block w-full bg-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                   name="cvc2" id="cvc2" required="" autocomplete="cc-csc">
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="bg-slate-700 rounded-3xl">
    <div id="<?= $id ?>" class="flex flex-col gap-2 rounded-3xl <?= $color ?> p-8">
      <div class="flex flex-row gap-2 items-center">
        <div class="p-4">
          <img src="<?= HOME_PATH .
            "src/assets/uploads/" .
            $image ?>" alt="<?= $name ?>"
               class="max-h-48 rounded-3xl" />
        </div>
        <div>
          <h1 class="font-bold text-6xl text-white"><?= $name ?></h1>
          <p class="text-white text-3xl">Sold by <?= $username ?></p>
        </div>
      </div>
    </div>
    <div class="flex flex-col justify-between rounded-3xl p-8 text-white gap-4 grow-0">
      <div>
        <div class="flex flex-row justify-between items-end gap-2">
          <span>Subtotal:</span>
          <span class="font-mono">CAD$ <?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="flex flex-row justify-between items-end gap-2">
          <span>TPS:</span>
          <span class="font-mono">CAD$ <?= number_format($tps, 2) ?></span>
        </div>
        <div class="flex flex-row justify-between items-end gap-2">
          <span>TVQ:</span>
          <span class="font-mono">CAD$ <?= number_format($tvq, 2) ?></span>
        </div>
        <div class="flex flex-row justify-between items-end gap-2">
          <span>Shipping:</span>
          <span class="font-mono">CAD$ <?= number_format($shipping, 2) ?></span>
        </div>
        <div class="flex flex-row justify-between items-end gap-2">
          <span class="text-4xl">Total:</span>
          <span class="text-4xl font-mono">CAD$<span
              class="text-6xl font-medium"><?= number_format(
                $total,
                2
              ) ?></span></span>
        </div>
      </div>
      <button type="submit" form="buy" class="px-4 py-2 bg-blue-600 rounded-3xl m-4">Buy now</button>
    </div>
  </div>
</div>
