<?php
require __DIR__ . '/config.php';
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if ($count > 0) { $message = 'An account already exists. Sign in or ask an administrator to create accounts.'; }
    else {
        $name = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || $username === '' || strlen($password) < 10) {
            $message = 'Enter a name, username, and password of at least 10 characters.';
        } else {
            $st = $pdo->prepare("INSERT INTO users(full_name,username,password_hash,role) VALUES(?,?,?,'admin')");
            $st->execute([$name, $username, password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Administrator account created. Please sign in.');
            redirect('/login.php');
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Initial setup · NRB</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-body"><main class="auth-card"><div class="brand-mark">MW<br>NRB</div><p class="eyebrow">ONE-TIME SETUP</p><h1>Create administrator</h1><p class="muted">Run this only on a trusted local installation. The setup is disabled once an account exists.</p>
<?php if ($message): ?><div class="alert error"><?=e($message)?></div><?php endif; ?>
<?php if ($count > 0): ?><div class="alert info">An account already exists. <a href="login.php">Go to login</a>.</div><?php else: ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Full name<input name="full_name" required maxlength="120"></label><label>Username<input name="username" required maxlength="60" autocomplete="username"></label><label>Password (minimum 10 characters)<input type="password" name="password" required minlength="10" autocomplete="new-password"></label><button class="btn primary full">Create administrator</button></form><?php endif; ?>
</main></body></html>
