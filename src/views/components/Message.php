<?php

use utils\Message;
use utils\MessageType;
use utils\Security;

$message = Message::get();
if (!isset($message)) {
  return;
}

// Ensure that the message is safe to display
$message["message"] = Security::sanitize($message["message"]);

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
