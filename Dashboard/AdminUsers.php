<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireRole('superadmin');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    csrfCheck();

    $name   = trim((string) $_POST['name']);
    $mobile = trim((string) $_POST['mobile']);
    $email  = trim((string) $_POST['mail']);
    $role   = ($_POST['role'] ?? 'admin') === 'superadmin' ? 'superadmin' : 'admin';
    $pass   = (string) $_POST['pass'];
    $pass2  = (string) $_POST['pass2'];

    $errors = [];
    if ($name === '' || $mobile === '' || $email === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($pass) < 4) {
        $errors[] = 'Password must be at least 4 characters.';
    }
    if ($pass !== $pass2) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO login (`user`, `Pass`, `Mobile`, `email`, `Role`) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, password_hash($pass, PASSWORD_BCRYPT), $mobile, $email, $role]);
            flash('success', "Admin account \"$name\" created.");
            redirect('AdminUsers.php');
        } catch (PDOException $e) {
            $errors[] = ($e->getCode() === '23000')
                ? 'That username or email is already in use.'
                : 'Could not create the account.';
        }
    }

    foreach ($errors as $error) {
        flash('error', $error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    csrfCheck();
    $targetId = (int) $_POST['id'];

    if ($targetId === (int) $admin['ID']) {
        flash('error', 'You cannot deactivate your own account.');
    } else {
        $stmt = $pdo->prepare("UPDATE login SET Status = IF(Status = 'active', 'inactive', 'active') WHERE ID = ?");
        $stmt->execute([$targetId]);
        flash('success', 'Account status updated.');
    }
    redirect('AdminUsers.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    csrfCheck();
    $targetId = (int) $_POST['id'];
    $newPass  = (string) $_POST['new_pass'];

    if (strlen($newPass) < 4) {
        flash('error', 'Password must be at least 4 characters.');
    } else {
        $stmt = $pdo->prepare('UPDATE login SET Pass = ? WHERE ID = ?');
        $stmt->execute([password_hash($newPass, PASSWORD_BCRYPT), $targetId]);
        flash('success', 'Password reset for that account.');
    }
    redirect('AdminUsers.php');
}

$admins = $pdo->query('SELECT * FROM login ORDER BY CreatedAt DESC')->fetchAll();

$pageTitle = 'Admin Users';
$activeNav = 'admins';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="card card-wide" style="max-width:640px;">
	<h2>Create Admin Account</h2>
	<form method="POST" action="AdminUsers.php">
		<?= csrfField() ?>
		<div class="field">
			<label for="name">Name</label>
			<input type="text" id="name" name="name" placeholder="User Name" required>
		</div>
		<div class="field">
			<label for="mobile">Mobile No.</label>
			<input type="tel" id="mobile" name="mobile" placeholder="Mobile" required>
		</div>
		<div class="field">
			<label for="mail">Email</label>
			<input type="email" id="mail" name="mail" placeholder="abc@gmail.com" required>
		</div>
		<div class="field">
			<label for="role">Role</label>
			<select id="role" name="role">
				<option value="admin">Admin</option>
				<option value="superadmin">Superadmin</option>
			</select>
		</div>
		<div class="field">
			<label for="pass">Password</label>
			<input type="password" id="pass" name="pass" minlength="4" required>
		</div>
		<div class="field">
			<label for="pass2">Re-enter Password</label>
			<input type="password" id="pass2" name="pass2" minlength="4" required>
		</div>
		<button type="submit" name="create" value="1" class="btn btn-primary btn-block">Create Account</button>
	</form>
</div>

<div class="result-wrap" style="max-width:100%; margin-top:32px;">
	<table class="result-table data-table">
		<caption>All Admin Accounts</caption>
		<thead>
			<tr><th>Name</th><th>Mobile</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr>
		</thead>
		<tbody>
		<?php foreach ($admins as $row): ?>
			<tr>
				<td><?= e($row['user']) ?></td>
				<td><?= e($row['Mobile']) ?></td>
				<td><?= e($row['email']) ?></td>
				<td><?= e($row['Role']) ?></td>
				<td><span class="badge badge-<?= $row['Status'] === 'active' ? 'active' : 'revoked' ?>"><?= e($row['Status']) ?></span></td>
				<td><?= e(date('d M Y', strtotime($row['CreatedAt']))) ?></td>
				<td class="actions-cell">
					<details class="inline-form">
						<summary class="btn btn-ghost btn-sm">Reset PW</summary>
						<form method="POST" action="AdminUsers.php" class="popover-form">
							<?= csrfField() ?>
							<input type="hidden" name="id" value="<?= (int) $row['ID'] ?>">
							<input type="password" name="new_pass" placeholder="New password" minlength="4" required>
							<button type="submit" name="reset_password" value="1" class="btn btn-primary btn-sm">Set</button>
						</form>
					</details>
					<form method="POST" action="AdminUsers.php" class="inline-form" data-confirm="<?= $row['Status'] === 'active' ? 'Deactivate this account?' : 'Reactivate this account?' ?>">
						<?= csrfField() ?>
						<input type="hidden" name="id" value="<?= (int) $row['ID'] ?>">
						<button type="submit" name="toggle_status" value="1" class="btn btn-sm <?= $row['Status'] === 'active' ? 'btn-danger' : 'btn-accent' ?>" <?= (int) $row['ID'] === (int) $admin['ID'] ? 'disabled' : '' ?>>
							<?= $row['Status'] === 'active' ? 'Deactivate' : 'Reactivate' ?>
						</button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
