<?php

include('dbconnection.php');

if(isset($_POST['search']))

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

<div class="search-card">
	<h3>Enter Your Mobile Number &amp; Email</h3>
	<form method="POST" action="">
		<div class="field">
			<input type="text" name="Mobile" placeholder="Enter Your Mobile Number">
		</div>
		<div class="field">
			<input type="email" name="Email" placeholder="Enter Your Email">
		</div>
		<button type="submit" name="search" class="btn btn-primary btn-block">Search</button>
	</form>
</div>

<?php if (isset($_POST['search'])) : ?>
<div class="result-wrap">
	<table class="result-table">
		<caption>Password</caption>
		<tr>
			<th>Password</th>
			<td>
<?php
	$mobile = $_POST['Mobile'];
	$email = $_POST['Email'];

	$query = "SELECT * FROM `login` WHERE Mobile='$mobile' AND email='$email'";
	$query_run = mysqli_query($con, $query);

	$found = false;
	while ($row = mysqli_fetch_array($query_run)) {
		$found = true;
		echo htmlspecialchars($row['Pass']);
	}
	if (!$found) {
		echo 'No matching account found.';
	}
?>
			</td>
		</tr>
	</table>
</div>
<?php endif; ?>

</body>
</html>
