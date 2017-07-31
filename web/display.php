<html>


<head>
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">
   <title>Data display</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>  
<style>

h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
}
table{
  width:100%;
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3); 
 }
.tbl-content{
  height:300px;
  overflow-x:auto;
  margin-top: 0px;
  border: 1px solid rgba(255,255,255,0.3);
}
th{
  padding: 20px 15px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  color: #fff;
  text-transform: uppercase;
}
td{
  padding: 15px;
  text-align: left;
  vertical-align:middle;
  font-weight: 300;
  font-size: 12px;
  color: #fff;
  border-bottom: solid 1px rgba(255,255,255,0.1);
}


/* demo styles */

@import url(http://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
body{
  background: -webkit-linear-gradient(left, #25c481, #25b7c4);
  background: linear-gradient(to right, #25c481, #25b7c4);
  font-family: 'Roboto', sans-serif;
}
section{
  margin: 50px;
}


/* follow me template */
.made-with-love {
  margin-top: 40px;
  padding: 10px;
  clear: left;
  text-align: center;
  font-size: 10px;
  font-family: arial;
  color: #fff;
}
.made-with-love i {
  font-style: normal;
  color: #F50057;
  font-size: 14px;
  position: relative;
  top: 2px;
}
.made-with-love a {
  color: #fff;
  text-decoration: none;
}
.made-with-love a:hover {
  text-decoration: underline;
}


/* for custom scrollbar for webkit browser*/

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}
</style>
</head>


<body id="gradient"> 

<div id="contentFull">

<script>
if (screen.width <= 800) {                         // in case a smartphone is used 
  document.getElementById("contentFull").style.opacity = "0";
  window.location = "AppDisplay.php";
}
</script>


<?php
error_reporting(0);                                // set mysql error displaying to none 
ini_set('display_errors', 0);
date_default_timezone_set("Europe/Bucharest");     // set used timezone 

$ip = $_SERVER['REMOTE_ADDR'];                     // fetch ip details from client 
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json")); 
file_put_contents("ips.txt", "web    " . date('Y m d   H i s') . "                  " . $ip . "   " . $details->country . "\n" . "   " . $details->region . "   " . $details->city . "   " . $details->hostname . "   " . $details->loc . "   " . $details->org . "\n", FILE_APPEND); 


$con=mysql_connect("localhost", "seba","seba1234"); // connect to SQL database 
if(!$con)
   {
    die('Nu s-a putut realiza conectarea:'.mysql_error());
   }

mysql_select_db("seba", $con);

$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
                     FROM seba_data 
                     ORDER BY CurrentDate 
                     DESC LIMIT 1;");                   // get notification panel data 

echo "<div style=\"float: right; width: 25%; padding-right: 4%\">"; 
while($row=mysql_fetch_array($result)){
	echo "<p align=center style=\"margin: 0em\"><font id='tempNot' size=8 color=white face='Verdana'>&nbsp;<span id='tempNotif'>";
	echo $row[1];
	echo "</span>&deg;C <font id='humNot' size=2><p align=right style=\"margin-top: -1em\">Humidity: <span id='humNotif'>"; 
	echo $row[2];
	echo "</span>% &emsp; Light: <span id='lightNotif'>";
	echo intval($row[3]/1024*100);
	echo "</span>% <br><b><font id='tempTimeNot' size=1><p align=right style=\"margin-top: 1em\"> Last update: <span id='tempTimeNotif'>";
	echo date('j M Y g:i:sA', strtotime($row[0])); 
	echo "</span></font></b>"; 
}
echo "</div><br><br>"; 


$result=mysql_query("Select * FROM seba_data ORDER BY CurrentDate DESC");         // get latest sensor data to be displayed on table 

echo "<br><h1>Sensor data</h1>";
echo "	<div class=\"tbl-header\">";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th width=12%>Current date</th><th width=14%>Temperature</th><th width=14%>Humidity</th><th width=14%>Gas danger</th><th width=14%>Light</th><th width=14%>Miliseconds</th><th width=14%>Last motion</th><th width=14%>Alarm</th></tr></thead></table></div>";
echo "<div class=\"tbl-content\">";
echo "	<table id='wholeData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "		<tbody>";

$last=999999999; 

while($row=mysql_fetch_array($result)){
	if($last<$row[5]) break; 
	echo "<tr><td width=12%>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td width=14%>$row[1]&deg;C</td><td width=14%>$row[2]%</td><td width=14%>"; 
	echo intval($row[3]/1024*100);  
	echo "%</td><td width=14%>";
	echo intval($row[4]/1024*100);
	echo "%</td><td width=14%>$row[5]</td><td width=14%>$row[6]"; 
	//echo $row[0]-$row[6]+$row[7]; 
	echo "</td><td width=14%>";
	if($row[7]==0) echo "OFF";
	else echo "ON"; 
	echo "</td></tr>";
	$last=$row[5]; 
     }
echo "</tbody></table></div>";






$result=mysql_query("Select * FROM seba_data_gas ORDER BY CurrentDate DESC");           // get latest gas leaks detected by the product 

echo "<div style='float: left; width: 40%; padding-left: 5%'><h1> Air hazard </h1>";
echo "	<div class=\"tbl-header\">";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th>Current date</th><th>Gas danger</th></tr></thead></table></div>";
echo "<div class=\"tbl-content\">";
echo "	<table id='gasData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "		<tbody>";

while($row=mysql_fetch_array($result)){
	echo "<tr><td>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td>"; 
	echo intval($row[1]/1024*100);  
	echo "%</td></tr>"; 
     }
echo "</tbody></table></div></div>"; 



$result=mysql_query("Select * FROM seba_data_motion ORDER BY CurrentDate DESC");        // get latest motion sensed by the product 

echo "<div style='float: right; width: 40%; padding-right: 5%'><h1> Movement detect </h1>";
echo "	<div class=\"tbl-header\">";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th>Current date</th><th>Last motion</th></tr></thead></table></div>";
echo "<div class=\"tbl-content\">";
echo "	<table id='motionData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "		<tbody>";

while($row=mysql_fetch_array($result)){
	echo "<tr><td>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td>$row[1]</td></tr>"; 
     }
echo "</tbody></table></div></div>";






mysql_close($con);
?>
</div>


</div> 

</body>



<script>
// background script for changing colors 
var colors = new Array(
  [37,196,129],
  [37,183,196],
  [37,136,178],
  [37,175,130],
  [37,191,121],
  [37,128,198]);

var step = 0;
//color table indices for: 
// current color left
// next color left
// current color right
// next color right
var colorIndices = [0,1,2,3];

//transition speed
var gradientSpeed = 0.002;

function updateGradient()
{
  
  if ( $===undefined ) return;
  
var c0_0 = colors[colorIndices[0]];
var c0_1 = colors[colorIndices[1]];
var c1_0 = colors[colorIndices[2]];
var c1_1 = colors[colorIndices[3]];

var istep = 1 - step;
var r1 = Math.round(istep * c0_0[0] + step * c0_1[0]);
var g1 = Math.round(istep * c0_0[1] + step * c0_1[1]);
var b1 = Math.round(istep * c0_0[2] + step * c0_1[2]);
var color1 = "rgb("+r1+","+g1+","+b1+")";

var r2 = Math.round(istep * c1_0[0] + step * c1_1[0]);
var g2 = Math.round(istep * c1_0[1] + step * c1_1[1]);
var b2 = Math.round(istep * c1_0[2] + step * c1_1[2]);
var color2 = "rgb("+r2+","+g2+","+b2+")";

 $('#gradient').css({
   background: "-webkit-gradient(linear, left top, right top, from("+color1+"), to("+color2+"))"}).css({
    background: "-moz-linear-gradient(left, "+color1+" 0%, "+color2+" 100%)"});  
  step += gradientSpeed;
  if ( step >= 1 )
  {
    step %= 1;
    colorIndices[0] = colorIndices[1];
    colorIndices[2] = colorIndices[3];
    
    //pick two new target color indices
    //do not pick the same as the current one
    colorIndices[1] = ( colorIndices[1] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
    colorIndices[3] = ( colorIndices[3] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
    
  }
}

setInterval(updateGradient,10);




$(window).on("load resize ", function() {             // use jQuery to resize the table's parameters 
  var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
  $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();



var lastDate;  // GET THE LAST DATE FROM THE MAIN TABLE USING AJAX 
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                        
      data: 'q=w',                        
                                     
      dataType: 'json',               
      success: function(data)          
      {
        lastDate = data[0]; 
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });
var lastDateGas;  // GET THE LAST DATE FROM THE GAS TABLE USING AJAX
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                  //the script to call to get data          
      data: 'q=g',                        
                                       
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        lastDateGas = data[0]; 
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });
var lastDateMotion;  // GET THE LAST DATE FROM THE MOTION TABLE USING AJAX
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                  //the script to call to get data          
      data: 'q=m',                        
                                       
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        lastDateMotion = data[0]; 
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });


function refreshTable() {               // add new rows to the main table for the user to not refresh the page 
    var table = document.getElementById("wholeData");
    var row = table.insertRow(0); 
    var data = new Array(10); 

    $.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                 
      data: 'q=w',                        
                                      
      dataType: 'json',                     
      success: function(Data)          
      {
        var date = Data[0];
        if( date > lastDate ){
          lastDate = date; 

          for(var i=0;i<9;++i) 
           data[i] = row.insertCell(i);

          for(var i = 0; i < 9; ++i){ 
            data[i].style.color = "rgba(255,255,255,0)"; 
            data[i].style.backgroundColor="rgba(255,255,255,0.1)"; 

            if( i == 0 ) data[i].style.width="12%"; 
            else if 
            ( i == 1 ) data[i].style.width="14%";
            else if 
            ( i == 2 ) data[i].style.width="14%";
            else if 
            ( i == 3 ) data[i].style.width="14%";
            else if 
            ( i == 4 ) data[i].style.width="14%";
            else if 
            ( i == 5 ) data[i].style.width="14%";
            else if 
            ( i == 6 ) data[i].style.width="14%";
            else if 
            ( i == 7 ) data[i].style.width="14%";

          }

          var hr = Data[0][11] + Data[0][12]; 
          var apm; 
          var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
          if( hr > 12 ){ apm = "PM"; hr-=12; } 
          else apm = "AM"; 
          if( hr == 00 )
            hr = 12; 

          var mt = Data[0][6]; 
          if(Data[0][5]==1) 
            mt += 10; 
          mt = monthNames[mt-1]; 

          if(Data[0][8]!=0) data[0].innerHTML = Data[0][8]; 
          else data[0].innerHTML = ''; 
          data[0].innerHTML += Data[0][9]+' '+mt+' '+Data[0][0]+Data[0][1]+Data[0][2]+Data[0][3]+' '+hr+':'+Data[0][14]+Data[0][15]+':'+Data[0][17]+Data[0][18]+' '+apm;
          data[1].innerHTML = Data[1]+"&deg;C";
          data[2].innerHTML = Data[2]+"%";
          data[3].innerHTML = Math.floor(Data[3]/1024*100)+"%";
          data[4].innerHTML = Math.floor(Data[4]/1024*100)+"%"; 
          data[5].innerHTML = Data[5];
          data[6].innerHTML = Data[6];
          if(Data[7]==0) data[7].innerHTML = "OFF";
          else data[7].innerHTML = "ON"; 

          

          
          var tempNotifCol = 0.98; 
          var humNotifCol = 0.8; 
          var tempTimeNotifCol = 0.6; 

          document.getElementById("tempNot").style.opacity = 0; 
          document.getElementById("humNot").style.opacity = 0; 
          document.getElementById("tempTimeNot").style.opacity = 0;  

          document.getElementById("tempNotif").innerHTML = Data[1]; 
          document.getElementById("humNotif").innerHTML = Data[2]; 
          document.getElementById("lightNotif").innerHTML = Math.floor(Data[4]/1024*100); 
          if(Data[0][8]!=0) document.getElementById("tempTimeNotif").innerHTML = Data[0][8]; 
          else document.getElementById("tempTimeNotif").innerHTML = ''; 
          document.getElementById("tempTimeNotif").innerHTML += Data[0][9]+' '+mt+' '+Data[0][0]+Data[0][1]+Data[0][2]+Data[0][3]+' '+hr+':'+Data[0][14]+Data[0][15]+':'+Data[0][17]+Data[0][18]+apm; 

          var pos = 0; 
          var a = 0, b = 0, c = 0, d = 0; 
          var bgc = 0.1; 
          var time = 20, increment = 0.005, rowOpac=1;
          var titleRef = rowOpac/increment/5;  
          var id  = setInterval(opacitate, time);

          function opacitate(){
            if(pos>=rowOpac)     
              clearInterval(id); 
            else{
              for(var i = 0; i < 9; ++i) {
                  data[i].style.color = "rgba(255,255,255,"+pos+")"; 
                  data[i].style.backgroundColor="rgba(255,255,255,"+bgc+")"
            } 
        
            a += tempNotifCol/titleRef; 
            b += humNotifCol/titleRef; 
            d += tempTimeNotifCol/titleRef; 

            if(a>tempNotifCol) a=tempNotifCol; 
            if(b>humNotifCol) b=humNotifCol; 
            if(d>tempTimeNotifCol) d=tempTimeNotifCol; 

            document.getElementById("tempNot").style.opacity = Math.floor(a*100)/100; 
            document.getElementById("humNot").style.opacity = Math.floor(b*100)/100; 
            document.getElementById("tempTimeNot").style.opacity = Math.floor(d*100)/100; 

                pos += increment; 
                bgc -= increment; 
            }
          }

        }

      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    }); 
}

function refreshTableGas() {                // add new rows to the gas table 
    var tableg = document.getElementById("gasData");
    var row = tableg.insertRow(0); 
    var data = new Array(10); 

    $.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                 
      data: 'q=g',                        
                                      
      dataType: 'json',                     
      success: function(Data)          
      {
        var date = Data[0];
        if( date > lastDateGas ){
          lastDateGas = date; 

          for(var i=0;i<2;++i)
          data[i] = row.insertCell(i); 

          for(var i = 0; i < 2; ++i){ 
            data[i].style.color = "rgba(255,255,255,0)"; 
      data[i].style.backgroundColor="rgba(255,255,255,0.1)";
          }

          var hr = Data[0][11] + Data[0][12]; 
          var apm; 
          var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
          if( hr > 12 ){ apm = "PM"; hr-=12; } 
          else apm = "AM"; 

          var hh = Data[0][11] + Data[0][12]; 
          var mn = Data[0][14] + Data[0][15]; 
          var sc = Data[0][17] + Data[0][18]; 

          var mt = Data[0][6]; 
          if(Data[0][5]==1) 
            mt += 10; 
          mt = monthNames[mt-1]; 

          data[0].innerHTML = Data[0][8]+Data[0][9]+' '+mt+' '+Data[0][0]+Data[0][1]+Data[0][2]+Data[0][3]+' '+hr+':'+Data[0][14]+Data[0][15]+':'+Data[0][17]+Data[0][18]+' '+apm;
          
          data[1].innerHTML = Math.floor(Data[1]/1024*100)+"%";


          var pos = 0; 
          var bgc = 0.1; 
          var id  = setInterval(opacitate, 10);

          function opacitate(){
            if(pos>=1)     
              clearInterval(id); 
            else{
              for(var i = 0; i < 2; ++i) {
                data[i].style.color = "rgba(255,255,255,"+pos+")"; 
         data[i].style.backgroundColor="rgba(255,255,255,"+bgc+")";
        } 

              pos += 0.005; 
              bgc -= 0.005; 
            }
          }

        }

      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });
}

function refreshTableMotion() {                // add new rows to the motion sense table 
    var tablem = document.getElementById("motionData");
    var row = tablem.insertRow(0); 
    var data = new Array(10); 

    $.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                 
      data: 'q=m',                        
                                      
      dataType: 'json',                     
      success: function(Data)          
      {
        var date = Data[0];
        if( date > lastDateMotion ){
          lastDateMotion = date; 

          for(var i=0;i<2;++i)
          data[i] = row.insertCell(i); 

          for(var i = 0; i < 2; ++i){   
            data[i].style.color = "rgba(255,255,255,0)"; 
      data[i].style.backgroundColor="rgba(255,255,255,0.1)";
          }

          var hr = Data[0][11] + Data[0][12]; 
          var apm; 
          var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
          if( hr > 12 ){ apm = "PM"; hr-=12; } 
          else apm = "AM"; 

          var hh = Data[0][11] + Data[0][12]; 
          var mn = Data[0][14] + Data[0][15]; 
          var sc = Data[0][17] + Data[0][18]; 

          var mt = Data[0][6]; 
          if(Data[0][5]==1) 

            mt += 10; 
          mt = monthNames[mt-1]; 

          data[0].innerHTML = Data[0][8]+Data[0][9]+' '+mt+' '+Data[0][0]+Data[0][1]+Data[0][2]+Data[0][3]+' '+hr+':'+Data[0][14]+Data[0][15]+':'+Data[0][17]+Data[0][18]+' '+apm;

          data[1].innerHTML = Data[1]; 



          var pos = 0; 
          var bgc = 0.1; 
          var id  = setInterval(opacitate, 10);

          function opacitate(){
            if(pos>=1)     
              clearInterval(id); 
            else{
              for(var i = 0; i < 2; ++i) {
                data[i].style.color = "rgba(255,255,255,"+pos+")"; 
        data[i].style.backgroundColor="rgba(255,255,255,"+bgc+")";
        } 

              pos += 0.005; 
              bgc -= 0.005; 
            }
          }

        }

      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });
}

// set 10 seconds for all the tables to refresh 
setInterval(refreshTable, 10000); 
setInterval(refreshTableGas, 10000); 
setInterval(refreshTableMotion, 10000); 


  

</script> 

</html>