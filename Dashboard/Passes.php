<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

function currentQuery(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return http_build_query($params);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    csrfCheck();
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("UPDATE tblpass SET Status = IF(Status = 'active', 'revoked', 'active') WHERE ID = ?");
    $stmt->execute([$id]);
    flash('success', 'Pass status updated.');
    redirect('Passes.php?' . (string) $_POST['back']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    csrfCheck();
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM tblpass WHERE ID = ?');
    $stmt->execute([$id]);
    flash('success', 'Pass deleted.');
    redirect('Passes.php?' . (string) $_POST['back']);
}

$q        = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$status   = trim((string) ($_GET['status'] ?? ''));
$page     = max(1, (int) ($_GET['page'] ?? 1));
$perPage  = 15;

$where  = [];
$params = [];

if ($q !== '') {
    $where[] = '(Name LIKE ? OR Mobile LIKE ? OR PassNumber LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
if ($category !== '') {
    $where[] = 'Category = ?';
    $params[] = $category;
}
if ($status !== '') {
    $where[] = 'Status = ?';
    $params[] = $status;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM tblpass $whereSql");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$sql = "SELECT * FROM tblpass $whereSql ORDER BY CreatedAt DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$passes = $stmt->fetchAll();

$categories = $pdo->query('SELECT CategoryName FROM tblcategory ORDER BY CategoryName')->fetchAll();
$backQuery = currentQuery(['page' => $page]);

$pageTitle = 'All Passes';
$activeNav = 'passes';
require __DIR__ . '/../includes/layout_start.php';
?>

<form method="get" action="Passes.php" class="filter-bar">
	<input type="text" name="q" value="<?= e($q) ?>" placeholder="Search name, mobile or pass number">
	<select name="category">
		<option value="">All Categories</option>
		<?php foreach ($categories as $row): ?>
		<option value="<?= e($row['CategoryName']) ?>" <?= $category === $row['CategoryName'] ? 'selected' : '' ?>><?= e($row['CategoryName']) ?></option>
		<?php endforeach; ?>
	</select>
	<select name="status">
		<option value="">All Statuses</option>
		<option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
		<option value="revoked" <?= $status === 'revoked' ? 'selected' : '' ?>>Revoked</option>
	</select>
	<button type="submit" class="btn btn-primary">Filter</button>
	<a href="Passes.php" class="btn btn-ghost">Reset</a>
</form>

<div class="result-wrap" style="max-width:100%;">
	<div class="table-scroll">
	<table class="result-table data-table">
		<thead>
			<tr><th>Pass #</th><th>Name</th><th>Mobile</th><th>Category</th><th>Valid</th><th>Status</th><th>Actions</th></tr>
		</thead>
		<tbody>
		<?php if (!$passes): ?>
			<tr><td colspan="7" class="empty-msg">No passes match your filters.</td></tr>
		<?php endif; ?>
		<?php foreach ($passes as $row): ?>
			<tr>
				<td><?= e((string) $row['PassNumber']) ?></td>
				<td><?= e($row['Name']) ?></td>
				<td><?= e($row['Mobile']) ?></td>
				<td><?= e($row['Category']) ?></td>
				<td><?= formatDate($row['FromDate']) ?> &ndash; <?= formatDate($row['ToDate']) ?></td>
				<td><span class="badge badge-<?= $row['Status'] === 'active' ? 'active' : 'revoked' ?>"><?= e($row['Status']) ?></span></td>
				<td class="actions-cell">
					<a href="NewPass.php?id=<?= (int) $row['ID'] ?>" class="btn btn-ghost btn-sm">Edit</a>
					<a href="PrintPass.php?passnum=<?= (int) $row['PassNumber'] ?>" class="btn btn-ghost btn-sm">Print</a>
					<form method="POST" action="Passes.php" class="inline-form" data-confirm="<?= $row['Status'] === 'active' ? 'Revoke this pass?' : 'Reactivate this pass?' ?>">
						<?= csrfField() ?>
						<input type="hidden" name="id" value="<?= (int) $row['ID'] ?>">
						<input type="hidden" name="back" value="<?= e($backQuery) ?>">
						<button type="submit" name="toggle_status" value="1" class="btn btn-sm <?= $row['Status'] === 'active' ? 'btn-danger' : 'btn-accent' ?>"><?= $row['Status'] === 'active' ? 'Revoke' : 'Reactivate' ?></button>
					</form>
					<form method="POST" action="Passes.php" class="inline-form" data-confirm="Delete this pass permanently?">
						<?= csrfField() ?>
						<input type="hidden" name="id" value="<?= (int) $row['ID'] ?>">
						<input type="hidden" name="back" value="<?= e($backQuery) ?>">
						<button type="submit" name="delete" value="1" class="btn btn-sm btn-danger">Delete</button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>

	<?php if ($totalPages > 1): ?>
	<nav class="pagination">
		<?php for ($p = 1; $p <= $totalPages; $p++): ?>
			<a href="Passes.php?<?= e(currentQuery(['page' => $p])) ?>" class="page-link<?= $p === $page ? ' active' : '' ?>"><?= $p ?></a>
		<?php endfor; ?>
	</nav>
	<?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
