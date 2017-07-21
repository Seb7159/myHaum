<?php
$q = $_GET['q'];

$con = mysql_connect('localhost','seba','');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysql_select_db("seba", $con);
if( $q == 'w' ){
	$sql="SELECT * FROM seba_data ORDER BY CurrentDate DESC LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result); 
} 
else if( $q == 'g' ){
	$sql="SELECT * FROM seba_data_gas ORDER BY CurrentDate DESC LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result); 
} 
else if( $q == 'm' ){
	$sql="SELECT * FROM seba_data_motion ORDER BY CurrentDate DESC LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result); 
} 
else if( $q == 'i' ){
	$sql="SELECT * FROM seba_ip ORDER BY CurrentDate DESC LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result); 
} 

echo json_encode($row); 
mysql_close($con);
?> 