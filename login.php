<?php
require __DIR__ . '/config.php';
if (!empty($_SESSION['user'])) redirect('/index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $st = $pdo->prepare('SELECT id,full_name,username,password_hash,role,active FROM users WHERE username=? LIMIT 1');
    $st->execute([$username]); $user = $st->fetch();
    if ($user && (int)$user['active'] === 1 && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        unset($user['password_hash'], $user['active']);
        $_SESSION['user'] = $user;
        flash('success', 'Welcome back, ' . $user['full_name'] . '.');
        redirect('/index.php');
    }
    $error = 'Invalid username or password, or the account is inactive.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in · NRB</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-body"><main class="auth-card"><div class="brand-mark">MW<br>NRB</div><p class="eyebrow">REPUBLIC OF MALAWI</p><h1>National Registration Bureau</h1><p class="muted">Secure National ID Registration System</p>
<?php if ($f=take_flash()): ?><div class="alert <?=e($f[0])?>"><?=e($f[1])?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Username<input name="username" required autocomplete="username" autofocus></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn primary full">Sign in securely</button></form>
<p class="tiny muted">Authorized personnel only · Access is logged through your account session.</p></main></body></html>
