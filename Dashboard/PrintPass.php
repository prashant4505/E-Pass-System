<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

$row = null;
$searched = false;
$passNumInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    csrfCheck();
    $passNumInput = trim((string) $_POST['passnum']);
} elseif (isset($_GET['passnum'])) {
    $passNumInput = trim((string) $_GET['passnum']);
}

if ($passNumInput !== '') {
    $searched = true;
    $stmt = $pdo->prepare('SELECT * FROM tblpass WHERE PassNumber = ?');
    $stmt->execute([$passNumInput]);
    $row = $stmt->fetch();
}

$pageTitle = 'Print Pass';
$activeNav = 'print-pass';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="search-card no-print">
	<h3>Search Pass by Pass Number</h3>
	<form method="POST" action="PrintPass.php">
		<?= csrfField() ?>
		<div class="field">
			<input type="text" name="passnum" value="<?= e($passNumInput) ?>" placeholder="Enter Pass Number" required>
		</div>
		<button type="submit" name="search" value="1" class="btn btn-primary btn-block">Search</button>
	</form>
</div>

<?php if ($searched): ?>
	<?php if (!$row): ?>
		<p class="empty-msg no-print">No pass found with that number.</p>
	<?php else: ?>
	<div class="pass-card">
		<div class="pass-card-header">
			<span class="pass-card-title">E-Pass</span>
			<span class="badge badge-<?= $row['Status'] === 'active' ? 'active' : 'revoked' ?>"><?= e($row['Status']) ?></span>
		</div>
		<div class="pass-card-body">
			<div class="pass-card-details">
				<p><strong>Pass Number:</strong> <?= e((string) $row['PassNumber']) ?></p>
				<p><strong>Name:</strong> <?= e($row['Name']) ?></p>
				<p><strong>Category:</strong> <?= e($row['Category']) ?></p>
				<p><strong>Mobile:</strong> <?= e($row['Mobile']) ?></p>
				<p><strong>Email:</strong> <?= e($row['email']) ?></p>
				<p><strong>Identity:</strong> <?= e($row['IdentityType']) ?> — <?= e($row['IdentityCardNo']) ?></p>
				<p><strong>Valid:</strong> <?= formatDate($row['FromDate']) ?> to <?= formatDate($row['ToDate']) ?></p>
			</div>
			<div class="pass-card-qr">
				<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode((string) $row['PassNumber']) ?>" alt="QR code for pass <?= e((string) $row['PassNumber']) ?>" width="150" height="150" loading="lazy">
			</div>
		</div>
	</div>
	<p class="no-print" style="text-align:center;margin-top:16px;">
		<button type="button" onclick="window.print()" class="btn btn-accent">Print</button>
	</p>
	<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
