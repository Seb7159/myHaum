<?php

$email = $_POST['email']; 

	$dbname = "seba_subscribe"; 
	$conn = mysql_connect("localhost", "seba", "");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname."  (Email) VALUES ('".$email."')"; 											
		
		if (mysql_query($sql)) 
 		   echo "<body bgcolor='#3b3f45'><center><h1><font color=white><br><br>You've been subscribed, thank you! Return <a href='../'>here</a>.";
		else 
  		  echo "Error: " . $sql . "<br>" . mysql_error($conn);
		}
	else
		die("Connection failed: " . mysql_connect_error());	


// delete from seba_data order by CurrentDate desc limit 
//http://seba.tm-edu.ro/get.php?p=abc&temp=33&hum=16&gas=131&light=980&relay=1&ms=19832&lmdbu=6934&alarm=0
?> 
