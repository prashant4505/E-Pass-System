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

<html>
<head>

<title>Add Category</title>

<style>

h1{  
color:white;  
background-color:red;  
padding:5px; 
text-align: center;
}

h2{  
color:white;    
padding:5px; 
text-align: center;
}


.btn{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 50px;
}

.frm{	
	background:rgba(0,0,0,0.5);
	color:#fff;
	top:50%;
	left:50%;
	position:absolute;
	transform:translate(-50%,-50%);
	box-sizing:border-box;
	padding: 30px 50px; 
}
input{
	width:100%;
}
p{
	font-weight:bold;
}


</style>

</head>

<body bgcolor="blue">
<h1>ADD CATEGORY</h1>
<a href="Dashboard.php"><button class="btn">Back</button></a>

<form method="POST" action="" class="frm">

<h2>Category</h2>
<p>Enter Category Name</p>
<p><input type="text" name="categorya" placeholder="Enter Category Name" Requierd></p>
<p><input type="Submit" name="Add"></p>
</form>

</body>


</html>