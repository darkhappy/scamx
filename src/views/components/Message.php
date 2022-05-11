<?php

use utils\Message;
use utils\MessageType;

$message = Message::get();
if (!isset($message)) {
  return;
}

$class = match ($message["type"]) {
  MessageType::Success => "bg-green-500",
  MessageType::Error => "bg-rose-500",
  MessageType::Warning => "bg-amber-500",
  default => "bg-teal-500",
};
?>

<div class="<?= $class ?> text-xl px-4 py-2" id="alert">
  <?= $message["message"] ?>
</div>
