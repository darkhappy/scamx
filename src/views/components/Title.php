<?php
if (!isset($pagetitle) || !isset($pagesub)) {
  die("Missing title or subtitle");
} ?>
<div class="py-16 px-8 bg-blue-500 text-white">
  <h1 class="font-extrabold text-8xl md:text-9xl mb-2"><?= $pagetitle ?></h1>
  <p class="text-4xl font-medium"><?= $pagesub ?></p>
</div>
