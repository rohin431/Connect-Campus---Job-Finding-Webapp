<?php
require_once __DIR__ . '/includes/functions.php'; require_login();
$q=trim($_GET['q'] ?? '');
$sql="SELECT id,name,college,branch,year FROM users WHERE id<>?"; $params=[current_user()['id']];
if($q!==''){ $sql.=" AND (name LIKE ? OR college LIKE ? OR branch LIKE ? OR skills LIKE ?)"; $like="%$q%"; array_push($params,$like,$like,$like,$like); }
$sql.=" ORDER BY name LIMIT 50"; $stmt=db()->prepare($sql); $stmt->execute($params); $students=$stmt->fetchAll();
$pageTitle='Network'; require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><span class="eyebrow">FIND YOUR PEOPLE</span><h1>Student network</h1><p class="muted">Search by name, college, branch or skill.</p></div></div>
<form class="search-form" method="get"><input name="q" value="<?= e($q) ?>" placeholder="Try Python, design, or a student name"><button class="btn">Search</button></form>
<div class="student-grid"><?php foreach($students as $s): ?><article class="student-card"><div class="mini-avatar"><?= e(strtoupper(substr($s['name'],0,1))) ?></div><h3><?= e($s['name']) ?></h3><p><?= e($s['college']) ?></p><small><?= e($s['branch']) ?> · Year <?= (int)$s['year'] ?></small><a class="btn btn-light full" href="profile.php?id=<?= (int)$s['id'] ?>">View profile</a>
<form method="post" action="api.php?action=connect"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$s['id'] ?>"><button class="text-button">Send connection request</button></form></article><?php endforeach; ?></div>
<?php require __DIR__ . '/includes/footer.php'; ?>