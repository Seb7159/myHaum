<?php

$password = $_GET['p']; 
$ip = $_GET['ip']; 

if($password=="abc"){

	$dbname = "seba_ip"; 
	$conn = mysql_connect("localhost", "seba", "");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname." (ip) VALUES (".$ip.")"; 											
		
		if (mysql_query($sql)) 
 		   echo "Okay";
		else 
  		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	else
		die("Connection failed: " . mysqli_connect_error());	

}
else
	echo "Password not good!"; 
?> 
