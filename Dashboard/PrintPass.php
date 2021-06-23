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

if(isset($_POST['search']))
//{
	//$mobile=$_POST['Mobile'];
	
	//$query= "SELECT * FROM `tblpass` WHERE Mobile='$mobile'";
	//$query_run=mysqli_query($con,$query);
	
//}

?>

<html>
<head>

<title>View Pass</title>

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
<h1>Lockdown Pass</h1>
<a href="Dashboard.php"><button class="btn">Back</button></a>
<center>
<div class="SearchBox">

<form method="POST" action="">

<h3>Search Pass By Pass Number</h3>
<p><input type="text" name="passnum" Placeholder="Enter Pass Number"></p>
<p><input type="Submit" name="search" Value="Search"></p>

</form>

</div>
<div class="pass">

<table border="1" bgcolor="Yellow" > 
                                    <tr align="center">
<td colspan="6" style="font-size:20px;color:blue">
 Pass ID:

<?php

if(isset($_POST['search']))
{
	$PassNum=$_POST['passnum'];
	
	$query= "SELECT * FROM `tblpass` WHERE PassNumber='$PassNum'";
	$query_run=mysqli_query($con,$query);
	
//}


	while($row=mysqli_fetch_array($query_run))
	{
		echo $row['PassNumber'];
	 ?>
 
   </td></tr>
   <tr>
        <th scope>Category</th>
    <td colspan="3">
	<?php
		echo $row['Category'];
	?>
   
   </td>
  </tr>

  <tr>
    <th scope>Full Name</th>
    <td colspan="3">
	<?php
	echo $row['Name'];
	?>
	</td>
  </tr>

  <tr>
    <th scope>Mobile Number</th>
    <td><?php
	echo $row['Mobile'];
	?></td>
    <th scope>Email</th>
    <td><?php
	echo $row['email'];
	?></td>
  </tr>
<tr>
    <th scope>Identity Type</th>
    <td><?php
	echo $row['IdentityType'];
	?></td>
    <th scope>Identity Card Number</th>
    <td><?php
	echo $row['IdentityCardNo'];
	?></td>

  </tr>
<tr>
    <th scope>From Date</th>
    <td><?php
	echo $row['FromDate'];
	?></td>
    <th scope>To Date</th>
    <td><?php
	echo $row['ToDate'];
	}
	}
	?></td>
  </tr>
                                  
  </table>
</div>

<p><button onclick="window.print()">Print</button></p>

</center>

</body>


</html>