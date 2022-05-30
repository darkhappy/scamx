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
  MessageType::SUCCESS => "bg-green-500",
  MessageType::ERROR => "bg-rose-500",
  MessageType::WARNING => "bg-amber-500",
  default => "bg-violet-500",
};
?>

<div class="<?= $class ?> text-xl px-4 py-2 mb-4 mt-2 rounded-xl" id="alert">
  <?= $message["message"] ?>
</div>
