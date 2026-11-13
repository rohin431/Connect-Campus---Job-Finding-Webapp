<?php
require_once __DIR__ . '/includes/functions.php'; require_login();
$pageTitle='Campus feed'; require __DIR__ . '/includes/header.php';
$posts = db()->query("SELECT p.*, u.name, u.college, u.id AS author_id FROM post_feed p JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC")->fetchAll();
?>
<div class="layout">
<aside class="side-card"><div class="avatar"><?= e(strtoupper(substr(current_user()['name'],0,1))) ?></div><h3><?= e(current_user()['name']) ?></h3><p><?= e(current_user()['college']) ?></p><a href="profile.php">View your profile →</a></aside>
<main class="feed"><div class="page-heading"><div><span class="eyebrow">CAMPUS COMMUNITY</span><h1>Your feed</h1></div></div>
<form class="post-form" method="post" action="api.php?action=create_post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><textarea name="content" maxlength="2000" required placeholder="Share an update, idea or achievement..."></textarea><div class="form-bottom"><small>Keep it useful and respectful.</small><button class="btn">Publish post</button></div></form>
<?php foreach($posts as $post): ?>
<article class="post-card"><div class="post-author"><div class="mini-avatar"><?= e(strtoupper(substr($post['name'],0,1))) ?></div><div><b><?= e($post['name']) ?></b><small><?= e($post['college']) ?> · <?= e($post['created_at']) ?></small></div></div><p class="post-content"><?= nl2br(e($post['content'])) ?></p>
<div class="post-actions"><form method="post" action="api.php?action=like"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>"><button class="text-button">Like</button></form><span><?= (int)$post['like_count'] ?> likes</span>
<?php if ((int)$post['author_id']===(int)current_user()['id']): ?><form method="post" action="api.php?action=delete_post" onsubmit="return confirm('Delete this post?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>"><button class="text-button danger">Delete</button></form><?php endif; ?></div>
<form class="comment-form" method="post" action="api.php?action=comment"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>"><input name="content" maxlength="1000" required placeholder="Write a comment..."><button class="btn btn-small">Comment</button></form>
<?php $stmt=db()->prepare("SELECT c.content,u.name FROM comments c JOIN users u ON u.id=c.user_id WHERE c.post_id=? ORDER BY c.created_at"); $stmt->execute([$post['id']]); foreach($stmt as $comment): ?><p class="comment"><b><?= e($comment['name']) ?>:</b> <?= e($comment['content']) ?></p><?php endforeach; ?>
</article>
<?php endforeach; if(!$posts): ?><div class="empty">No posts yet. Be the first to share something.</div><?php endif; ?>
</main><aside class="side-card side-tip"><b>Make your profile count</b><p>Add your skills and projects so other students can discover your work.</p><a href="profile.php">Update profile →</a></aside>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>