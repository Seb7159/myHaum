<?php

$password = $_GET['p']; 
$lmdbu=$_GET['lmdbu'];
$temp=$_GET['temp'];
$gas=$_GET['gas'];
$hum=$_GET['hum'];
$light=$_GET['light'];
$ms=$_GET['ms'];
$alarm=$_GET['alarm'];

if($password=="abc"){

	$dbname = "seba_data"; 
	$conn = mysql_connect("localhost", "seba", "");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname." (Temperature, Humidity, Gas, Light, Milliseconds, LMDBU, ALARM) VALUES (".$temp.", ".$hum.", ".$gas.", ".$light.", ".$ms.", ".$lmdbu.", ".$alarm.")"; 											
		
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
// delete from seba_data order by CurrentDate desc limit 
//http://seba.tm-edu.ro/get.php?p=abc&temp=33&hum=16&gas=131&light=980&relay=1&ms=19832&lmdbu=6934&alarm=0
?> 
