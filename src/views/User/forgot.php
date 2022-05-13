<?php use Utils\Security; ?>
<div>
  <?php require __DIR__ . "/../components/Message.php"; ?>
  <form method="post" id="form">
    <div class="mb-6">
      <div class="mb-3">
        <label class="block mb-1 font-medium" for="email">Email</label>
        <input
          class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="email" name="email" type="email">
      </div>
      <label for="csrf" hidden>
        <input type="hidden" name="csrf" value="<?= Security::generateCSRFToken("forgot") ?>">
      </label>
    </div>
    <div class="flex justify-between items-center gap-8">
      <button class="bg-slate-50 hover:bg-amber-300 text-black px-5 py-2 rounded font-medium" type="submit">Send an
                                                                                                            email
      </button>
      <a class="text-slate-300 hover:text-amber-400 font-medium"
         href="<?= HOME_PATH ?>user/login">Retour</a>
    </div>
  </form>
</div>
