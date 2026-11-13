<?php
require_once __DIR__ . '/includes/functions.php'; require_login();
$stmt=db()->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 100"); $stmt->execute([current_user()['id']]); $items=$stmt->fetchAll();
db()->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?")->execute([current_user()['id']]);
$pageTitle='Notifications'; require __DIR__ . '/includes/header.php';
?>
<div class="page-heading"><div><span class="eyebrow">STAY IN THE LOOP</span><h1>Notifications</h1></div></div>
<div class="notice-list"><?php foreach($items as $n): ?><article class="notice-item"><span class="notification-dot"></span><div><p><?= e($n['message']) ?></p><small><?= e($n['created_at']) ?></small></div></article><?php endforeach; if(!$items): ?><div class="empty">You're all caught up.</div><?php endif; ?></div>
<?php require __DIR__ . '/includes/footer.php'; ?>