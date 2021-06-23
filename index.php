<?php

session_start();
		if(isset($_SESSION['uid']))
		{
			header('location:Dashboard/Dashboard.php');
		}

?>
<?php

$host="localhost";
$user="root";
$password="";
$db="epas";

$con=mysqli_connect($host,$user,$password,$db);

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



<html>  
<head>
<!--<link href="project.css" rel="stylesheet" type="text/css"> -->

<style>  
h1{  
color:white;  
background-color:red;  
padding:5px; 
text-align: center;
}

h2{  
padding: 0 0 20px
margin:0;	
color:white;   
text-align: center;   
   
}  
  
body{
	margin:0;
	padding:0;
	background-image : url("./Images/Nature.jpg");
	background-size : cover;
	background-position:center;
	background-repeat: no-repeat;
	font-family:sans-serif;
}

.login-box{
	hight:420px;
	width:320px;	
	background:rgba(0,0,0,0.5);
	color:#fff;
	top:50%;
	left:50%;
	position:absolute;
	transform:translate(-50%,-50%);
	box-sizing:border-box;
	padding: 30px 50px; 
}

.avtar
{
	width:100px;
	hight:10px;
	border-radious:50%;
	position:Absolute;
	top: -50px;
	left:calc(50% - 50px);
}

.login-box p{
	font-weight:bold;
}
.login-box input{
	width:100%;
}
.Reg{
	text-align: center;
}
.forget{
	text-align: center;
}

</style>  

</head> 

<body>
  
<h1>E-PASS SYSTEM</h1>

<div class="login-box">
<img src="Images/avtar.png" class="avtar">

<h2>Login</h2>

<form method="POST" action="#">
<p>Username:</p>
<input type="text" name="username" placeholder="Enter Username">
<p>Password:<p>
<input type="Password" name="Password" placeholder="Enter Password">
<p><input type="Submit" name="Submit" Value="Login">
<!--<p><input type="Submit" name="Registration" Value="Registration">-->
<p class="Reg"><a href="AdminRegistration.php"><font color="white">New Registration</font></a></p>
<p class="forget"><a href="ForgetPass.php"><font color="white">Forget Password</font></a>
</form>

</div>
 
</body>  
</html>  
