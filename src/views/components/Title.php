<?php
if (!isset($pagetitle) || !isset($pagesub)) {
  die("Missing title or subtitle");
}
?>
<div class="py-16 px-8 bg-green-500 flex flex-row justify-between align-baseline">
  <div>
    <h1 class="font-extrabold text-9xl mb-2"><?= $pagetitle ?></h1>
    <p class="text-4xl font-medium">
      <?= $pagesub ?>
    </p>
  </div>
  <div>
    <img src="<?=HOME_PATH?>src/assets/images/money.gif" alt="very cool">
  </div>
</div>
