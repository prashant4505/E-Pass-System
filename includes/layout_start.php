<?php
declare(strict_types=1);
/**
 * Shared chrome for authenticated Dashboard/ pages.
 * Expects $pageTitle, $activeNav and $admin to already be set by the including page.
 */

$navItems = [
    'dashboard'  => ['label' => 'Dashboard',   'icon' => '🏠', 'href' => 'Dashboard.php'],
    'passes'     => ['label' => 'All Passes',  'icon' => '📋', 'href' => 'Passes.php'],
    'new-pass'   => ['label' => 'New Pass',    'icon' => '🆕', 'href' => 'NewPass.php'],
    'print-pass' => ['label' => 'Print Pass',  'icon' => '🖨️', 'href' => 'PrintPass.php'],
    'view-pass'  => ['label' => 'Verify Pass', 'icon' => '🔍', 'href' => 'ViewPass.php'],
    'category'   => ['label' => 'Categories',  'icon' => '🗂️', 'href' => 'AddCategory.php'],
];
if (($admin['Role'] ?? '') === 'superadmin') {
    $navItems['admins'] = ['label' => 'Admin Users', 'icon' => '👥', 'href' => 'AdminUsers.php'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — <?= e($pageTitle) ?></title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-cover bg-app">

<div class="app-shell">
	<button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu" aria-controls="sidebar" aria-expanded="false">&#9776;</button>

	<aside class="sidebar" id="sidebar">
		<div class="sidebar-brand">E-PASS</div>
		<nav class="sidebar-nav">
			<?php foreach ($navItems as $key => $item): ?>
			<a href="<?= e($item['href']) ?>" class="sidebar-link<?= $activeNav === $key ? ' active' : '' ?>">
				<span class="icon" aria-hidden="true"><?= $item['icon'] ?></span><?= e($item['label']) ?>
			</a>
			<?php endforeach; ?>
		</nav>
		<div class="sidebar-footer">
			<div class="sidebar-user">
				<?= e($admin['user'] ?? '') ?>
				<span class="role-badge"><?= e($admin['Role'] ?? '') ?></span>
			</div>
			<a href="Logout.php" class="btn btn-ghost btn-block">Logout</a>
		</div>
	</aside>

	<div class="app-backdrop" id="appBackdrop"></div>

	<main class="app-main">
		<header class="app-topbar"><h1><?= e($pageTitle) ?></h1></header>
		<div class="app-content">
<?php renderFlashes(); ?>
