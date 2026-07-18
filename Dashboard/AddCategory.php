<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Add'])) {
    csrfCheck();
    $name = trim((string) $_POST['categorya']);

    if ($name === '') {
        flash('error', 'Category name is required.');
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO tblcategory (CategoryName) VALUES (?)');
            $stmt->execute([$name]);
            flash('success', "Category \"$name\" added.");
        } catch (PDOException $e) {
            flash('error', $e->getCode() === '23000' ? 'That category already exists.' : 'Could not add category.');
        }
    }
    redirect('AddCategory.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    csrfCheck();
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM tblcategory WHERE ID = ?');
    $stmt->execute([$id]);
    flash('success', 'Category deleted.');
    redirect('AddCategory.php');
}

$categories = $pdo->query('SELECT * FROM tblcategory ORDER BY CategoryName')->fetchAll();

$pageTitle = 'Categories';
$activeNav = 'category';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="card card-wide">
	<h2>New Category</h2>
	<form method="POST" action="AddCategory.php">
		<?= csrfField() ?>
		<div class="field">
			<label for="categorya">Category Name</label>
			<input type="text" id="categorya" name="categorya" placeholder="Enter Category Name" required>
		</div>
		<button type="submit" name="Add" value="1" class="btn btn-primary btn-block">Add Category</button>
	</form>
</div>

<div class="result-wrap" style="max-width:100%;">
	<div class="table-scroll">
	<table class="result-table data-table">
		<caption>Existing Categories</caption>
		<thead><tr><th>Name</th><th>Created</th><th>Actions</th></tr></thead>
		<tbody>
		<?php if (!$categories): ?>
			<tr><td colspan="3" class="empty-msg">No categories yet.</td></tr>
		<?php endif; ?>
		<?php foreach ($categories as $row): ?>
			<tr>
				<td><?= e($row['CategoryName']) ?></td>
				<td><?= e(date('d M Y', strtotime($row['CreationDate']))) ?></td>
				<td class="actions-cell">
					<form method="POST" action="AddCategory.php" class="inline-form" data-confirm="Delete this category?">
						<?= csrfField() ?>
						<input type="hidden" name="id" value="<?= (int) $row['ID'] ?>">
						<button type="submit" name="delete" value="1" class="btn btn-sm btn-danger">Delete</button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
