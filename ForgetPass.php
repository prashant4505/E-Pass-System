<?php

include('dbconnection.php');

if(isset($_POST['search']))

?>

<html>
<head>

<title>Retrieve Password</title>

<style>

h1{  
color:white;  
background-color:red;  
padding:5px; 
text-align: center;
}

h3{  
color:white;    
padding:5px; 
text-align: center;
}


input{

	width:50%;
	text-align: center;
	padding:5px;
} 
table{
		color:blue;
		font-size:20;
		font-weight:bold;
}

.btn{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 50px;
}

</style>

</head>

<body bgcolor="blue">
<h1>Retrieve Password</h1>
<a href="index.php"><button class="btn">Back</button></a>
<center>
<div class="SearchBox">

<form method="POST" action="">

<h3>Enter Your Mobile Number</h3>
<p><input type="text" name="Mobile" Placeholder="Enter Your Mobile Number"></p>
<p><input type="text" name="Email" Placeholder="Enter Your Email"></p>
<p><input type="Submit" name="search" Value="Search"></p>

</form>

</div>
<div class="pass">

<table border="1" bgcolor="Yellow" > 
                                    <tr align="center">
<td colspan="6" style="font-size:20px;color:blue">
 Password:

<?php

if(isset($_POST['search']))
{
	$mobile=$_POST['Mobile'];
	$email=$_POST['Email'];
	
	$query= "SELECT * FROM `login` WHERE Mobile='$mobile' AND email='$email'";
	$query_run=mysqli_query($con,$query);
	
//}


	while($row=mysqli_fetch_array($query_run))
	{
		echo $row['Pass'];
	 
	 }
	}
	 ?>                          
  
   </table>
</div>
</center>
</body>


</html>