<?php

session_start();
		if ($_SESSION['uid'])
		{
			echo "";
		}
		
		else
		{
			header('location:../index.php');
		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-cover bg-app">

<div class="dashboard-header">
	<h1>Welcome to the Dashboard</h1>
	<a href="Logout.php" class="btn btn-ghost">Logout</a>
</div>

<nav class="dashboard-grid">
	<a href="NewPass.php" class="dashboard-card"><span class="icon">🆕</span>New Pass</a>
	<a href="PrintPass.php" class="dashboard-card"><span class="icon">🖨️</span>Print Pass</a>
	<a href="ViewPass.php" class="dashboard-card"><span class="icon">🔍</span>Verify Pass</a>
	<a href="AddCategory.php" class="dashboard-card"><span class="icon">🗂️</span>Add Category</a>
</nav>

</body>
</html>