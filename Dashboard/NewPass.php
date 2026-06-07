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
		
		if (isset($_POST['Submit']))
			
			{
				
				$fname= $_POST['name'];
				$Mnumber= $_POST['mobile'];
				$Email= $_POST['mail'];
				$identy= $_POST['identity'];
				$idno= $_POST['idnum'];
				$ctgy= $_POST['category'];
				$fdate= $_POST['fromDate'];
				$tdate= $_POST['toDate'];
				$passnum=mt_rand(100000000, 999999999);
				
				$qrys="INSERT INTO `tblpass`(`ID`, `PassNumber`, `Name`, `Mobile`, `email`, `IdentityType`, `IdentityCardNo`, `Category`, `FromDate`, `ToDate`) VALUES(NULL,'$passnum','$fname','$Mnumber','$Email','$identy','$idno','$ctgy','$fdate','$tdate')";
				$run=mysqli_query($con,$qrys);
				if($run==true)
				{
					?>
					<script>
					alert('Data Inserted Successfully');
					window.open('NewPass.php','_self');
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
<title>E-Pass System — New Pass</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-cover bg-app">

<header class="page-title">Add New Pass</header>
<div class="top-actions">
	<a href="Dashboard.php" class="btn btn-ghost">&larr; Back to Dashboard</a>
	<a href="Logout.php" class="btn btn-ghost">Logout</a>
</div>

<div class="page-center">
	<div class="card card-wide">
		<h2>Pass Details</h2>
		<form method="post" action="NewPass.php">
			<div class="field">
				<label for="name">Full Name</label>
				<input type="text" id="name" name="name" placeholder="Name" required>
			</div>
			<div class="field">
				<label for="mobile">Contact Number</label>
				<input type="tel" id="mobile" name="mobile" placeholder="Mobile" required>
			</div>
			<div class="field">
				<label for="mail">Email Address</label>
				<input type="email" id="mail" name="mail" placeholder="abc@gmail.com" required>
			</div>
			<div class="field">
				<label for="identity">Identity Type</label>
				<select id="identity" name="identity" required>
					<option value="">Choose Identity Type</option>
					<option value="Voter Card">Voter Card</option>
					<option value="PAN Card">PAN Card</option>
					<option value="Adhar Card">Aadhar Card</option>
					<option value="Student Card">Student Card</option>
					<option value="Driving License">Driving License</option>
					<option value="Passport">Passport</option>
					<option value="Any Official Card">Any Official Card</option>
					<option value="Any Other Govt Issued Doc">Any Other Govt Issued Doc</option>
				</select>
			</div>
			<div class="field">
				<label for="idnum">Identity Card No.</label>
				<input type="text" id="idnum" name="idnum" required>
			</div>
			<div class="field">
				<label for="category">Category</label>
				<select id="category" name="category">
<?php
$res = mysqli_query($con, "SELECT * FROM `tblcategory`");
while ($row = mysqli_fetch_array($res)) {
	echo '<option>' . htmlspecialchars($row['CategoryName']) . '</option>';
}
?>
				</select>
			</div>
			<div class="field">
				<label for="fromDate">From Date</label>
				<input type="text" id="fromDate" name="fromDate" placeholder="dd/mm/yyyy" required>
			</div>
			<div class="field">
				<label for="toDate">To Date</label>
				<input type="text" id="toDate" name="toDate" placeholder="dd/mm/yyyy" required>
			</div>
			<button type="submit" name="Submit" value="Add" class="btn btn-primary btn-block">Add Pass</button>
		</form>
	</div>
</div>

</body>
</html>