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

<html>
<head>
<title>Dashboard</title>
<style>

h1{  
color:white;  
background-color:red;  
padding:5px; 
text-align:center;
}
a{
color:white;   

float:right;
}
button{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 50px;
        }
		
body{
	margin:0;
	padding:0;
	background-image : url("../Images/gradiant.jpg");
	background-size : cover;
	background-position:center;
	background-repeat: no-repeat;
	font-family:sans-serif;
}
table th,td{
	padding:100px;
	border:5px solid #fff;
}
marquee{
 	Left-margine:50px;
	Right-Margine:50px;
}

div.b {
  position: absolute;
  left: 550px;
  width: 200px;
  height: 120px;
  
} 
div.a {
  position: absolute;
  left: 50;
  width: 250px;
  height: 450px;
  
  background-image : url("../Images/avtar.png");
  background-size : 200px 200px;
  background-repeat: no-repeat;
  background-position:center;
} 

</style>

</head>
<body>

<h1>WELCOME TO DASHBOARD</h1>
<a href="Logout.php"><button>Logout</button></a>

<div class="a">
<h1>ADMIN</h1>
</div>
<div class="b">
<table align="center">  
<tr><th bgcolor="RED"><a href="NewPass.php">NEW PASS</a></th><th bgcolor="Blue"><a href="PrintPass.php">PRINT PASS</a></th></tr>
<tr><th bgcolor="Yellow"><a href="ViewPass.php">VERIFY PASS</a></th><th bgcolor="Magenta"><a href="AddCategory.php">ADD CATEGORY</a></th></tr>  
</table>  
</div>
</body>
</html>