<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

$results = [];
$searched = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    csrfCheck();
    $searched = true;
    $mobile = trim((string) $_POST['Mobile']);

    $stmt = $pdo->prepare('SELECT * FROM tblpass WHERE Mobile = ? ORDER BY CreatedAt DESC');
    $stmt->execute([$mobile]);
    $results = $stmt->fetchAll();
}

$pageTitle = 'Verify Pass';
$activeNav = 'view-pass';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="search-card">
	<h3>Search Pass by Mobile Number</h3>
	<form method="POST" action="ViewPass.php">
		<?= csrfField() ?>
		<div class="field">
			<input type="text" name="Mobile" placeholder="Enter Mobile Number" required>
		</div>
		<button type="submit" name="search" value="1" class="btn btn-primary btn-block">Search</button>
	</form>
</div>

<?php if ($searched): ?>
	<?php if (!$results): ?>
		<p class="empty-msg">No pass found for that mobile number.</p>
	<?php endif; ?>
	<?php foreach ($results as $row): ?>
	<div class="result-wrap">
		<table class="result-table">
			<caption>
				Pass Details
				<span class="badge badge-<?= $row['Status'] === 'active' ? 'active' : 'revoked' ?>"><?= e($row['Status']) ?></span>
			</caption>
			<tr><th>Pass Number</th><td><?= e((string) $row['PassNumber']) ?></td></tr>
			<tr><th>Category</th><td><?= e($row['Category']) ?></td></tr>
			<tr><th>Full Name</th><td><?= e($row['Name']) ?></td></tr>
			<tr><th>Mobile Number</th><td><?= e($row['Mobile']) ?></td></tr>
			<tr><th>Email</th><td><?= e($row['email']) ?></td></tr>
			<tr><th>Identity Type</th><td><?= e($row['IdentityType']) ?></td></tr>
			<tr><th>Identity Card Number</th><td><?= e($row['IdentityCardNo']) ?></td></tr>
			<tr><th>From Date</th><td><?= formatDate($row['FromDate']) ?></td></tr>
			<tr><th>To Date</th><td><?= formatDate($row['ToDate']) ?></td></tr>
		</table>
	</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
