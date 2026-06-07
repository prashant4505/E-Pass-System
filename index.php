<?php

session_start();
		if(isset($_SESSION['uid']))
		{
			header('location:Dashboard/Dashboard.php');
		}

include('dbconnection.php');

if(isset($_POST['username'])){
	
	$uname=$_POST['username'];
	$password=$_POST['Password'];
	
	$sql="select * from login where user='".$uname."'AND Pass='".$password."'limit 1";
	
	$result=mysqli_query($con,$sql);
	
	if(mysqli_num_rows($result)==1){
		
		
		$data=mysqli_fetch_assoc($result);
		$ID=$data['ID'];
		
		//session_start();
		$_SESSION['uid']=$ID;
		header('location:Dashboard/Dashboard.php');
		
	}
	else{
		?>
		
		<script>
		alert("You have Enter Incorect User Name or Password");
		window.open('index.php','_self');
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
<title>E-Pass System — Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-cover bg-login">

<header class="page-title">E-PASS SYSTEM</header>

<div class="page-center">
	<div class="card">
		<img src="Images/avtar.png" alt="User avatar" class="avatar">
		<h2>Login</h2>

		<form method="POST" action="#">
			<div class="field">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Enter Username" required>
			</div>
			<div class="field">
				<label for="password">Password</label>
				<input type="password" id="password" name="Password" placeholder="Enter Password" required>
			</div>
			<button type="submit" name="Submit" class="btn btn-primary btn-block">Login</button>
		</form>

		<div class="links">
			<p><a href="AdminRegistration.php">New Registration</a></p>
			<p><a href="ForgetPass.php">Forgot Password?</a></p>
		</div>
	</div>
</div>

</body>
</html>
