<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['uid'])) {
    redirect('Dashboard/Dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    csrfCheck();

    if (loginIsLocked()) {
        flash('error', 'Too many failed attempts. Please wait a minute and try again.');
        redirect('index.php');
    }

    $uname = trim((string) $_POST['username']);
    $pass  = (string) ($_POST['Password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM login WHERE user = ? AND Status = 'active' LIMIT 1");
    $stmt->execute([$uname]);
    $row = $stmt->fetch();

    if ($row && password_verify($pass, $row['Pass'])) {
        clearLoginThrottle();
        session_regenerate_id(true);
        $_SESSION['uid'] = $row['ID'];
        redirect('Dashboard/Dashboard.php');
    }

    registerFailedLogin();
    flash('error', 'Incorrect username or password.');
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-cover bg-login">

<header class="page-title">E-PASS SYSTEM</header>

<div class="page-center">
	<div class="card">
		<img src="Images/avtar.png" alt="User avatar" class="avatar">
		<h2>Login</h2>

		<?php renderFlashes(); ?>

		<form method="POST" action="index.php">
			<?= csrfField() ?>
			<div class="field">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Enter Username" autocomplete="username" required autofocus>
			</div>
			<div class="field">
				<label for="password">Password</label>
				<input type="password" id="password" name="Password" placeholder="Enter Password" autocomplete="current-password" required>
			</div>
			<button type="submit" class="btn btn-primary btn-block">Login</button>
		</form>

		<div class="links">
			<p><a href="ForgetPass.php">Forgot Password?</a></p>
		</div>
	</div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
