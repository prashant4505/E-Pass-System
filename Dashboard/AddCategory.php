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

if(isset($_POST['Add']))
	
	{
	
	$addctg=$_POST['categorya'];
	
	$qry="INSERT INTO `tblcategory`(`ID`, `CategoryName`, `CreationDate`) VALUES ('NULL','$addctg','')";
	$qry_run=mysqli_query($con,$qry);
	if($qry_run==true)
	{
		?>
		<script>
		alert("Category Added Successfully");
		window.open('AddCategory.php','_self');
		</script>
		<?php
	}
	
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — Add Category</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-cover bg-app">

<header class="page-title">Add Category</header>
<div class="top-actions">
	<a href="Dashboard.php" class="btn btn-ghost">&larr; Back to Dashboard</a>
</div>

<div class="page-center">
	<div class="card card-wide">
		<h2>New Category</h2>
		<form method="POST" action="">
			<div class="field">
				<label for="categorya">Category Name</label>
				<input type="text" id="categorya" name="categorya" placeholder="Enter Category Name" required>
			</div>
			<button type="submit" name="Add" class="btn btn-primary btn-block">Add Category</button>
		</form>
	</div>
</div>

</body>
</html>