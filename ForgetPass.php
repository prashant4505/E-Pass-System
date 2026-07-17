<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$resetReady = !empty($_SESSION['reset_uid']) && !empty($_SESSION['reset_expires']) && $_SESSION['reset_expires'] > time();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    csrfCheck();

    $mobile = trim((string) $_POST['Mobile']);
    $email  = trim((string) $_POST['Email']);

    $stmt = $pdo->prepare("SELECT ID FROM login WHERE Mobile = ? AND email = ? AND Status = 'active' LIMIT 1");
    $stmt->execute([$mobile, $email]);
    $row = $stmt->fetch();

    if ($row) {
        $_SESSION['reset_uid'] = $row['ID'];
        $_SESSION['reset_expires'] = time() + 300;
    } else {
        flash('error', 'No matching account found for that mobile number and email.');
    }
    redirect('ForgetPass.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    csrfCheck();

    if (!$resetReady) {
        flash('error', 'That reset session expired. Please verify your details again.');
        redirect('ForgetPass.php');
    }

    $newPass = (string) ($_POST['pass'] ?? '');
    $confirm = (string) ($_POST['pass2'] ?? '');

    if (strlen($newPass) < 4) {
        flash('error', 'Password must be at least 4 characters.');
        redirect('ForgetPass.php');
    }
    if ($newPass !== $confirm) {
        flash('error', 'Passwords do not match.');
        redirect('ForgetPass.php');
    }

    $stmt = $pdo->prepare('UPDATE login SET Pass = ? WHERE ID = ?');
    $stmt->execute([password_hash($newPass, PASSWORD_BCRYPT), $_SESSION['reset_uid']]);

    unset($_SESSION['reset_uid'], $_SESSION['reset_expires']);
    flash('success', 'Password updated. You can now log in.');
    redirect('index.php');
}

if (isset($_POST['cancel'])) {
    unset($_SESSION['reset_uid'], $_SESSION['reset_expires']);
    redirect('ForgetPass.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — Forgot Password</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-cover bg-login">

<header class="page-title">Forgot Password</header>
<div class="top-actions">
	<a href="index.php" class="btn btn-ghost">&larr; Back to Login</a>
</div>

<div class="page-center">
<?php renderFlashes(); ?>

<?php if ($resetReady): ?>
	<div class="card card-wide">
		<h2>Set a New Password</h2>
		<form method="POST" action="ForgetPass.php">
			<?= csrfField() ?>
			<div class="field">
				<label for="pass">New Password</label>
				<input type="password" id="pass" name="pass" minlength="4" required autofocus>
			</div>
			<div class="field">
				<label for="pass2">Confirm Password</label>
				<input type="password" id="pass2" name="pass2" minlength="4" required>
			</div>
			<button type="submit" name="reset" value="1" class="btn btn-primary btn-block">Update Password</button>
		</form>
		<form method="POST" action="ForgetPass.php" style="margin-top:10px;">
			<?= csrfField() ?>
			<button type="submit" name="cancel" value="1" class="btn btn-ghost btn-block">Cancel</button>
		</form>
	</div>
<?php else: ?>
	<div class="card card-wide">
		<h2>Verify Your Account</h2>
		<p style="margin-bottom:16px; opacity:.85;">Enter the mobile number and email on your admin account to reset your password.</p>
		<form method="POST" action="ForgetPass.php">
			<?= csrfField() ?>
			<div class="field">
				<label for="Mobile">Mobile Number</label>
				<input type="text" id="Mobile" name="Mobile" placeholder="Enter Your Mobile Number" required>
			</div>
			<div class="field">
				<label for="Email">Email</label>
				<input type="email" id="Email" name="Email" placeholder="Enter Your Email" required>
			</div>
			<button type="submit" name="verify" value="1" class="btn btn-primary btn-block">Continue</button>
		</form>
	</div>
<?php endif; ?>
</div>

</body>
</html>
