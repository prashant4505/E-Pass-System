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


include('../dbconnection.php');

if (isset($_POST['search'])) {
	$mobile = $_POST['Mobile'];

	$query = "SELECT * FROM `tblpass` WHERE Mobile='$mobile'";
	$query_run = mysqli_query($con, $query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — Verify Pass</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-cover bg-app">

<header class="page-title">Verify Pass</header>
<div class="top-actions">
	<a href="Dashboard.php" class="btn btn-ghost">&larr; Back to Dashboard</a>
</div>

<div class="search-card">
	<h3>Search Pass by Mobile Number</h3>
	<form method="POST" action="">
		<div class="field">
			<input type="text" name="Mobile" placeholder="Enter Your Mobile Number">
		</div>
		<button type="submit" name="search" class="btn btn-primary btn-block">Search</button>
	</form>
</div>

<?php
if (isset($_POST['search'])) {
	$found = false;
	while ($row = mysqli_fetch_array($query_run)) {
		$found = true;
?>
<div class="result-wrap">
	<table class="result-table">
		<caption>Pass Details</caption>
		<tr><th>Pass Number</th><td><?php echo htmlspecialchars($row['PassNumber']); ?></td></tr>
		<tr><th>Category</th><td><?php echo htmlspecialchars($row['Category']); ?></td></tr>
		<tr><th>Full Name</th><td><?php echo htmlspecialchars($row['Name']); ?></td></tr>
		<tr><th>Mobile Number</th><td><?php echo htmlspecialchars($row['Mobile']); ?></td></tr>
		<tr><th>Email</th><td><?php echo htmlspecialchars($row['email']); ?></td></tr>
		<tr><th>Identity Type</th><td><?php echo htmlspecialchars($row['IdentityType']); ?></td></tr>
		<tr><th>Identity Card Number</th><td><?php echo htmlspecialchars($row['IdentityCardNo']); ?></td></tr>
		<tr><th>From Date</th><td><?php echo htmlspecialchars($row['FromDate']); ?></td></tr>
		<tr><th>To Date</th><td><?php echo htmlspecialchars($row['ToDate']); ?></td></tr>
	</table>
</div>
<?php
	}
	if (!$found) {
		echo '<p class="empty-msg">No pass found for that mobile number.</p>';
	}
}
?>

</body>
</html>