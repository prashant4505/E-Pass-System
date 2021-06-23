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
<html>
<head>
<title>New Pass</title>
<style>

h1{  
color:white;  
background-color:red;  
padding:5px; 
text-align: center;
}
.frm{
	hight:420px;
	width:350px;	
	background:rgba(0,0,0,0.5);
	color:#fff;
	top:60%;
	left:50%;
	position:absolute;
	transform:translate(-50%,-40%);
	box-sizing:border-box;
	padding: 10px 60px; 
}
h2{  
padding: 0 0 20px
margin:0;	
color:white;   
text-align: center;   
   
}  
.frm input{
	width:100%;
}

.btn1{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 1250px;
        }
		
.btn2{
           background-color: lightblue;
           border:5px blue double;     
           border-radius:25px;
		   margin:-55px 50px;
}
</style>
</head>
<body bgcolor="blue">
</body>
<h1>Add New Pass</h1>

<a href="Logout.php"><button class="btn1">Logout</button></a>
<a href="Dashboard.php"><button class="btn2">Back</button></a>

<div class="frm">
<h2>Add Details</h2>
<form method="post" action="NewPass.php">

Full Name:
<p><input type="text" name="name" placeholder ="Name" required="true"></p>
<p>Contact Number:</p>
<p>
<input type="text" name="mobile" placeholder ="Mobile" required="true"></p>
<p>Email Address:</p>
<p><input type="text" name="mail" placeholder ="abc@gmail.com" required="true"></p>
<p>Identity Type:</p>
<p><select type="text" name="identity" required="true"></p>

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
<p>Identity Card No.:</p>
<p><input type="text" name="idnum" placeholder ="" required="true"></p>

<p>Category:</p>
<select type="text" name="category">
<?php
$res=mysqli_query($con,"SELECT * FROM `tblcategory`");
while($row=mysqli_fetch_array($res))
{
	?>
<option>
<?php echo $row['CategoryName']; ?> </option>
<?php
}
?>
</select>

<p>From Date:</p>
<p><input type="text" name="fromDate" placeholder ="dd/mm/yyyy" required="true"></p>
<p>To Date:</p>
<p><input type="text" name="toDate" placeholder ="dd/mm/yyyy" required="true"></p>
<p><input type="Submit" name="Submit" Value="Add"></p>
</form>

</div>

</html>