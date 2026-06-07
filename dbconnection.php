<?php

$host="database";
$user="lamp";
$password="lamp";
$db="lamp";

$con=mysqli_connect($host,$user,$password,$db);
if($con==true)
{
	echo"";
}
else{
	echo"connection fail";
}

?>