<?php

$password = $_GET['p']; 
$lastm=$_GET['lastm'];



if($password=="abc"){

	// send mail notification 
	$con=mysql_connect("localhost", "seba","");

	mysql_select_db("seba", $con);

	$result=mysql_query("SELECT CurrentDate  
	                     FROM seba_data_motion
	                     ORDER BY CurrentDate 
	                     DESC LIMIT 1;"); 

	while($row=mysql_fetch_array($result)){
		$ny=date('y', strtotime($row[0]));
		$nm=date('m', strtotime($row[0]));
		$nd=date('j', strtotime($row[0]));

		$ay=date('y');
		$am=date('m'); 
		$ad=date('j');   

		if(!($ay==$ny && $am==$nm && $ad==$nd)){
			$to = "stanicisebastian@yahoo.com";
			$subject = "myHaum Motion detected";

			$message = "
			<html>
			<head>
			<title>myHaum Motion detected</title> 
			</head>
			<body>
			<h1><center>Warning!</center></h1>
			<h3>The alarm was triggered right now, on ".date("d-M-Y H:i:s").". <br><br>Please take action immediately!</h3>
			<br><br><br> Yours truly, <br> myHaum Team 
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <myhaum@tm-edu.ro>' . "\r\n";

			mail($to,$subject,$message,$headers); 
		}
	}




	$dbname = "seba_data_motion";

	$conn = mysql_connect("localhost", "seba", "seba1234");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname." (LMDBU) VALUES (".$lastm.")"; 											
		
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
