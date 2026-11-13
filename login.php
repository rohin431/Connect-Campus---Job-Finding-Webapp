<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) redirect('dashboard.php');
$pageTitle = 'Log in'; require __DIR__ . '/includes/header.php';
?>
<section class="form-wrap"><div class="form-card"><span class="eyebrow">WELCOME BACK</span><h1>Log in</h1>
<form method="post" action="api.php?action=login">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<label>Email<input type="email" name="email" required></label>
<label>Password<input type="password" name="password" required></label>
<button class="btn full">Log in</button>
</form><p class="muted center">New here? <a href="signup.php">Create an account</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>