<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

$totalPasses   = (int) $pdo->query("SELECT COUNT(*) FROM tblpass")->fetchColumn();
$activePasses  = (int) $pdo->query("SELECT COUNT(*) FROM tblpass WHERE Status = 'active'")->fetchColumn();
$revokedPasses = (int) $pdo->query("SELECT COUNT(*) FROM tblpass WHERE Status = 'revoked'")->fetchColumn();
$thisWeek      = (int) $pdo->query("SELECT COUNT(*) FROM tblpass WHERE CreatedAt >= NOW() - INTERVAL 7 DAY")->fetchColumn();

$byCategory = $pdo->query(
    "SELECT Category, COUNT(*) AS total FROM tblpass GROUP BY Category ORDER BY total DESC LIMIT 6"
)->fetchAll();

$recentPasses = $pdo->query(
    "SELECT * FROM tblpass ORDER BY CreatedAt DESC LIMIT 5"
)->fetchAll();

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="stats-grid">
	<div class="stat-card">
		<span class="stat-value"><?= $totalPasses ?></span>
		<span class="stat-label">Total Passes</span>
	</div>
	<div class="stat-card stat-active">
		<span class="stat-value"><?= $activePasses ?></span>
		<span class="stat-label">Active</span>
	</div>
	<div class="stat-card stat-revoked">
		<span class="stat-value"><?= $revokedPasses ?></span>
		<span class="stat-label">Revoked</span>
	</div>
	<div class="stat-card">
		<span class="stat-value"><?= $thisWeek ?></span>
		<span class="stat-label">Issued This Week</span>
	</div>
</div>

<div class="dashboard-grid">
	<a href="NewPass.php" class="dashboard-card"><span class="icon">🆕</span>New Pass</a>
	<a href="Passes.php" class="dashboard-card"><span class="icon">📋</span>All Passes</a>
	<a href="PrintPass.php" class="dashboard-card"><span class="icon">🖨️</span>Print Pass</a>
	<a href="ViewPass.php" class="dashboard-card"><span class="icon">🔍</span>Verify Pass</a>
	<a href="AddCategory.php" class="dashboard-card"><span class="icon">🗂️</span>Categories</a>
	<?php if ($admin['Role'] === 'superadmin'): ?>
	<a href="AdminUsers.php" class="dashboard-card"><span class="icon">👥</span>Admin Users</a>
	<?php endif; ?>
</div>

<div class="panel-row">
	<div class="panel">
		<h3>Passes by Category</h3>
		<?php if (!$byCategory): ?>
			<p class="empty-msg">No passes issued yet.</p>
		<?php else: ?>
			<ul class="bar-list">
			<?php foreach ($byCategory as $row): ?>
				<li>
					<span class="bar-label"><?= e($row['Category']) ?></span>
					<span class="bar-track"><span class="bar-fill" style="width:<?= $totalPasses ? round($row['total'] / $totalPasses * 100) : 0 ?>%"></span></span>
					<span class="bar-value"><?= (int) $row['total'] ?></span>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="panel">
		<h3>Recently Issued</h3>
		<?php if (!$recentPasses): ?>
			<p class="empty-msg">No passes issued yet.</p>
		<?php else: ?>
			<div class="table-scroll">
			<table class="result-table data-table">
				<thead><tr><th>Pass #</th><th>Name</th><th>Category</th><th>Status</th></tr></thead>
				<tbody>
				<?php foreach ($recentPasses as $row): ?>
					<tr>
						<td><?= e((string) $row['PassNumber']) ?></td>
						<td><?= e($row['Name']) ?></td>
						<td><?= e($row['Category']) ?></td>
						<td><span class="badge badge-<?= $row['Status'] === 'active' ? 'active' : 'revoked' ?>"><?= e($row['Status']) ?></span></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
