<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) redirect('dashboard.php');
$pageTitle = 'CampusConnect';
require __DIR__ . '/includes/header.php';
?>
<main class="hero">
  <section class="hero-copy">
    <span class="eyebrow">YOUR CAMPUS. YOUR COMMUNITY.</span>
    <h1>Build your network.<br><span>Make your next move.</span></h1>
    <p>Connect with students, share what you're building, and discover opportunities across your college community.</p>
    <div class="actions"><a class="btn" href="signup.php">Create account</a><a class="btn btn-light" href="login.php">Log in</a></div>
  </section>
  <section class="hero-panel"><div class="mock-card"><div class="mock-avatar">CC</div><h3>Your campus network starts here.</h3><p>Projects · People · Opportunities</p><div class="mock-stats"><span><b>Connect</b><small>with peers</small></span><span><b>Showcase</b><small>your work</small></span><span><b>Discover</b><small>opportunities</small></span></div></div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>