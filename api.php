<?php
require_once __DIR__ . '/includes/functions.php';
$action = $_GET['action'] ?? '';
function post_only(): void { if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed');} verify_csrf(); }
function notify_user(int $uid, string $message): void {
    db()->prepare("INSERT INTO notifications(user_id,message) VALUES(?,?)")->execute([$uid,$message]);
}
try {
switch ($action) {
case 'register':
    post_only();
    $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $college=trim($_POST['college']??'');
    $branch=trim($_POST['branch']??''); $year=(int)($_POST['year']??0); $password=$_POST['password']??'';
    if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$college||!$branch||$year<1||$year>5||strlen($password)<8) throw new Exception('Please complete every field. Password must be at least 8 characters.');
    $stmt=db()->prepare("INSERT INTO users(name,email,password,college,branch,year) VALUES(?,?,?,?,?,?)");
    $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$college,$branch,$year]);
    $_SESSION['user']=['id'=>(int)db()->lastInsertId(),'name'=>$name,'email'=>$email,'college'=>$college];
    if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json')) {header('Content-Type: application/json');echo json_encode(['ok'=>true,'redirect'=>'dashboard.php']);exit;}
    redirect('dashboard.php');
case 'login':
    post_only(); $stmt=db()->prepare("SELECT id,name,email,password,college FROM users WHERE email=?"); $stmt->execute([strtolower(trim($_POST['email']??''))]); $u=$stmt->fetch();
    if(!$u||!password_verify($_POST['password']??'',$u['password'])) throw new Exception('Incorrect email or password.');
    session_regenerate_id(true); unset($u['password']); $_SESSION['user']=$u; redirect('dashboard.php');
case 'logout':
    $_SESSION=[]; session_destroy(); redirect('index.php');
case 'create_post':
    require_login(); post_only(); $content=trim($_POST['content']??''); if(!$content||strlen($content)>2000) throw new Exception('Post must contain 1–2000 characters.');
    db()->prepare("INSERT INTO posts(user_id,content) VALUES(?,?)")->execute([current_user()['id'],$content]); flash('Your post was published.'); redirect('dashboard.php');
case 'delete_post':
    require_login(); post_only(); db()->prepare("DELETE FROM posts WHERE id=? AND user_id=?")->execute([(int)$_POST['post_id'],current_user()['id']]); flash('Post deleted.'); redirect('dashboard.php');
case 'like':
    require_login(); post_only(); $pid=(int)$_POST['post_id']; $pdo=db();
    $stmt=$pdo->prepare("SELECT id,user_id FROM posts WHERE id=?"); $stmt->execute([$pid]); $post=$stmt->fetch(); if(!$post) throw new Exception('Post not found.');
    $stmt=$pdo->prepare("SELECT id FROM likes WHERE post_id=? AND user_id=?"); $stmt->execute([$pid,current_user()['id']]);
    if($stmt->fetch()) $pdo->prepare("DELETE FROM likes WHERE post_id=? AND user_id=?")->execute([$pid,current_user()['id']]);
    else { $pdo->prepare("INSERT INTO likes(post_id,user_id) VALUES(?,?)")->execute([$pid,current_user()['id']]); if($post['user_id']!=current_user()['id']) notify_user((int)$post['user_id'],current_user()['name'].' liked your post.'); }
    flash('Like updated.'); redirect('dashboard.php');
case 'comment':
    require_login(); post_only(); $content=trim($_POST['content']??''); $pid=(int)$_POST['post_id']; if(!$content||strlen($content)>1000) throw new Exception('Comment must contain 1–1000 characters.');
    $stmt=db()->prepare("SELECT user_id FROM posts WHERE id=?"); $stmt->execute([$pid]); $owner=$stmt->fetchColumn(); if(!$owner) throw new Exception('Post not found.');
    db()->prepare("INSERT INTO comments(post_id,user_id,content) VALUES(?,?,?)")->execute([$pid,current_user()['id'],$content]); if($owner!=current_user()['id']) notify_user((int)$owner,current_user()['name'].' commented on your post.'); flash('Comment added.'); redirect('dashboard.php');
case 'connect':
    require_login(); post_only(); $target=(int)$_POST['user_id']; if($target=== (int)current_user()['id']) throw new Exception('You cannot connect with yourself.');
    $stmt=db()->prepare("SELECT id FROM users WHERE id=?"); $stmt->execute([$target]); if(!$stmt->fetch()) throw new Exception('Student not found.');
    $a=min($target,(int)current_user()['id']); $b=max($target,(int)current_user()['id']);
    db()->prepare("INSERT IGNORE INTO connections(requester_id,receiver_id,status) VALUES(?,?,'pending')")->execute([$a,$b]);
    notify_user($target,current_user()['name'].' sent you a connection request.'); flash('Connection request recorded.'); redirect('network.php');
case 'update_profile':
    require_login(); post_only();
    db()->prepare("UPDATE users SET headline=?,about=?,skills=?,github=?,linkedin=? WHERE id=?")->execute([
      trim($_POST['headline']??''),trim($_POST['about']??''),trim($_POST['skills']??''),trim($_POST['github']??''),trim($_POST['linkedin']??''),current_user()['id']
    ]); flash('Profile updated.'); redirect('profile.php');
case 'add_project':
    require_login(); post_only(); $title=trim($_POST['title']??''); $desc=trim($_POST['description']??''); if(!$title||!$desc) throw new Exception('Title and description are required.');
    db()->prepare("INSERT INTO projects(user_id,title,description,technologies,github,demo) VALUES(?,?,?,?,?,?)")->execute([current_user()['id'],$title,$desc,trim($_POST['technologies']??''),trim($_POST['github']??''),trim($_POST['demo']??'')]); flash('Project added.'); redirect('projects.php');
case 'delete_project':
    require_login(); post_only(); db()->prepare("DELETE FROM projects WHERE id=? AND user_id=?")->execute([(int)$_POST['project_id'],current_user()['id']]); flash('Project deleted.'); redirect('projects.php');
default: http_response_code(404); echo 'Unknown action.';
}
} catch (PDOException $ex) {
    if($action==='register' && $ex->getCode()==='23000') { http_response_code(409); exit('That email is already registered.'); }
    error_log($ex->getMessage()); http_response_code(500); echo 'A database error occurred. Check your database setup.';
} catch (Throwable $ex) {
    if($action==='register' && str_contains($_SERVER['HTTP_ACCEPT']??'','application/json')) {http_response_code(400);header('Content-Type: application/json');echo json_encode(['ok'=>false,'message'=>$ex->getMessage()]);exit;}
    flash($ex->getMessage(),'error'); redirect($action==='login'?'login.php':($action==='register'?'signup.php':'dashboard.php'));
}