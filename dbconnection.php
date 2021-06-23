<?php

$host="localhost";
$user="root";
$password="";
$db="epas";

$con=mysqli_connect($host,$user,$password,$db);
if($con==true)
{
	echo"";
}
else{
	echo"connection fail";
}

?>