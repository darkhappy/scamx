<?php
if (!isset($content) || !isset($title)) {
  die('Missing content or title');
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?= HOME_PATH ?>assets/css/dist/styles.css">
  <title><?= $title ?></title>
<body class="bg-black text-white">
<?php require __DIR__ . "/components/Header.php"; ?>
<div class="flex min-h-screen justify-center items-center">
  <div class="border-solid border-4 border-white mx-8 p-8 mt-4">
    <div class="container">
      <?php require $content; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . "/components/Footer.php"; ?>
</body>
</html>