<body bgcolor='#3b3f45'><center><h1><font color=white><br><br>

<?php

$nick = $_POST['nick']; 
$email = $_POST['email']; 
$pass = $_POST['pass']; 
$pass2 = $_POST['pass2']; 

$r=0;

$con=mysql_connect("localhost", "seba","");

if(!$con)
   {
    die('Nu s-a putut realiza conectarea:'.mysql_error());
   }

mysql_select_db("seba", $con);

$result=mysql_query("SELECT Email 
                     FROM seba_signup 
                     ORDER BY CurrentDate "); 

while($row=mysql_fetch_array($result)){
	if($row[0]==$email)
		$r=1;
}







if($pass!=$pass2)
	echo 'Passwords do not match! Return <a href=\'../\'>here</a>.';
else if($r==1)
	echo 'The mail you wrote is taken, please use another one! Return <a href=\'../\'>here</a>.';
else{ 
	$pass = md5($pass);
	$dbname = "seba_signup"; 
	$conn = mysql_connect("localhost", "seba", "seba1234");

	if($conn){
		mysql_select_db("seba", $conn);
		$sql = "INSERT INTO ".$dbname."  (Nick,Email,Pass) VALUES ('".$nick."','".$email."','".$pass."')"; 											
		
		if (mysql_query($sql)) 
 		   echo "You've been signed up, well done! Return <a href='../'>here</a>.";
		else 
  		  echo "Error: " . $sql . "<br>" . mysql_error($conn);
		}
	else
		die("Connection failed: " . mysql_connect_error());	
}

?> 
