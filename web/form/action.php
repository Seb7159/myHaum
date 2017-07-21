<?php

$q1 = $_POST['q1']; 
$q2 = $_POST['q2']; 
$q3 = $_POST['q3']; 
$q4 = $_POST['q4']; 
$q5 = $_POST['q5']; 

	$dbname = "seba_form"; 
	$conn = mysql_connect("localhost", "seba", "");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname."  (Name, Email, HexColor, Descr, Budget) VALUES ('".$q1."', '".$q2."', '".$q3."', '".$q4."', ".$q5.")"; 											
		
		if (mysql_query($sql)) 
 		   echo "<body bgcolor='#3b3f45'><center><h1><font color=white><br><br>Data sent, have a beautiful day! Return <a href='../../'>here</a>.";
		else 
  		  echo "Error: " . $sql . "<br>" . mysql_error($conn);
		}
	else
		die("Connection failed: " . mysql_connect_error());	


// delete from seba_data order by CurrentDate desc limit 
//http://seba.tm-edu.ro/get.php?p=abc&temp=33&hum=16&gas=131&light=980&relay=1&ms=19832&lmdbu=6934&alarm=0
?> 
