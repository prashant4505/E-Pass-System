<?php

include('dbconnection.php');
if (isset($_POST['Submit']))
			
			{
				$pass= $_POST['pass'];
				$name= $_POST['name'];
				$Mnumber= $_POST['mobile'];
				$Email= $_POST['mail'];
				$pass2=$_POST['pass2'];
				if($pass==$pass2)
				{
				
				$qry="INSERT INTO `login`(`ID`, `user`, `Pass`, `Mobile`, `email`) VALUES (Null,'$name','$pass','$Mnumber','$Email')";
				$run=mysqli_query($con,$qry);
				if($run==true&&$pass==true)
				{
					?>
					<script>
					alert('Data Inserted Successfully');
					window.open('AdminRegistration.php','_self');
					</script>
					<?php
					
				}
				}
				else{
					?><script>alert("Password not match");
					window.open('AdminRegistration.php','_self');</script>
					<?php
				}

			}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Pass System — New Registration</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-cover bg-login">

<header class="page-title">New Registration</header>
<div class="top-actions">
	<a href="index.php" class="btn btn-ghost">&larr; Back to Login</a>
</div>

<div class="page-center">
	<div class="card card-wide">
		<h2>Create Admin Account</h2>

		<form method="post" action="#">
			<div class="field">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" placeholder="User Name" required>
			</div>
			<div class="field">
				<label for="mobile">Mobile No.</label>
				<input type="tel" id="mobile" name="mobile" placeholder="Mobile" required>
			</div>
			<div class="field">
				<label for="mail">Email</label>
				<input type="email" id="mail" name="mail" placeholder="abc@gmail.com" required>
			</div>
			<div class="field">
				<label for="pass">Password</label>
				<input type="password" id="pass" name="pass" placeholder="Password" required>
			</div>
			<div class="field">
				<label for="pass2">Re-enter Password</label>
				<input type="password" id="pass2" name="pass2" placeholder="Password" required>
			</div>
			<button type="submit" name="Submit" value="Submit" class="btn btn-primary btn-block">Submit</button>
		</form>
	</div>
</div>

</body>
</html>
