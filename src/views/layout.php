<?php
if (!isset($content)) {
  die("Missing content");
} ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="theme-color" content="#22c55e">
  <link rel="stylesheet" href="<?= HOME_PATH ?>src/assets/css/dist/styles.css">
  <title>ScamX<?= empty($title) ? "" : " - $title" ?></title>
<body class="bg-lime-50">
<div class="flex flex-col min-h-screen justify-between">
  <?php require __DIR__ . "/components/Navbar.php"; ?>
  <?php require __DIR__ . "/components/Title.php"; ?>
  <div class="grow p-8">
    <?php require $content; ?>
  </div>
  <?php require __DIR__ . "/components/Footer.php"; ?>
</div>
<script src="<?= HOME_PATH ?>src/assets/js/selfxss.js"></script>
</body>
</html>