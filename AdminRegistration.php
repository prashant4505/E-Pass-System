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

<html>
<head>
<title>Admin Registration</title>

<style>

h1{  
color:white;  
background-color:Red;  
padding:5px; 
text-align: center;
}
.frm{
	hight:420px;
	width:350px;	
	background:rgba(0,0,0,0.5);
	color:#fff;
	top:50%;
	left:50%;
	position:absolute;
	transform:translate(-50%,-40%);
	box-sizing:border-box;
	padding: 10px 60px; 
}
h3{  
padding: 0 0 20px
margin:0;	
color:white;   
text-align: center;
}

.frm p{
	font-weight:bold;
}
.frm input{
	width:100%;
}
.btn2{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 50px;
}

</style>

</head>

<body bgcolor="Blue">
<h1>New Registration</h1>


<a href="index.php"><button class="btn2">Back</button></a>

<form method="post" action="#" class="frm">

<h3>New Registration</h3>
<p>Name:</p>
<p><input type="text" name="name" placeholder="User Name" Required></p>
<p>Mobile No:</p>
<p><input type="int" name="mobile" placeholder="Mobile" Required></p>
<p>Email:</p>
<p><input type="mail" name="mail" placeholder="abc@gmail.com" Required></p>
<p>Enter Password</p>
<p><input type="mail" name="pass" placeholder="Password" Required></p>
<p>Re-Enter Password</p>
<p><input type="mail" name="pass2" placeholder="Password" Required></p>

<p><input type="Submit" name="Submit" value="Submit" Required></p>

</form>

</html>