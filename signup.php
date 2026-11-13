<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) redirect('dashboard.php');
$pageTitle = 'Create account'; require __DIR__ . '/includes/header.php';
?>
<section class="form-wrap"><div class="form-card"><span class="eyebrow">GET STARTED</span><h1>Create your account</h1><p class="muted">Join your college community.</p>
<form id="signupForm">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<label>Full name<input name="name" required maxlength="100" placeholder="Your name"></label>
<label>College email<input type="email" name="email" required maxlength="190" placeholder="you@college.edu"></label>
<label>College<input name="college" required maxlength="150" placeholder="College name"></label>
<div class="two"><label>Branch<input name="branch" required maxlength="100" placeholder="CSE"></label><label>Study year<select name="year" required><option value="">Select</option><?php for($i=1;$i<=5;$i++) echo "<option value=\"$i\">Year $i</option>"; ?></select></label></div>
<label>Password<input type="password" name="password" minlength="8" required placeholder="At least 8 characters"></label>
<button class="btn full">Create account</button><div id="formMessage" class="form-message"></div>
</form><p class="muted center">Already registered? <a href="login.php">Log in</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>