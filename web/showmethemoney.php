<html>


<head>
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui">
<meta name="theme-color" content="#21A4B0" />
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css">
   <title>Data display</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script> 

<style>
.unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
div.hour{
  position: absolute;
  left: 67px; 
  top: 614px; 
  color:white; 
  font-size: 72px; 
  color: rgba(255,255,255,0.95);
}
div.uptime{
  position: absolute;
  top: 706px; 
  left: 144px; 
  color:white; 
  font-size: 30px; 
  color: rgba(255,255,255,0.8);
  text-transform: uppercase; 
}
div.downtime{
  position: absolute; 
  top: 736px; 
  left: 148px; 
  color: rgba(255,255,255,0.4); 
  font-size: 20px; 
  text-transform: uppercase;
}
div.lastAlert{
  position: absolute; 
  top: 698px; 
  left: 180px; 
  color:white; 
  font-size: 22px; 
  color: rgba(255,255,255,0.9);
  text-transform: uppercase;
}

div.apm{
  position: absolute; 
  top: 760px; 
  left: 148px; 
  color:white; 
  font-size: 14px; 
  text-transform: uppercase;
}





div.fixed1 {
    position: fixed;
    top: 20px;
    left: 0;
    //border-top: 1px solid rgba(255,255,255,0.4);
    text-decoration: overline; 
    //border-bottom: 1px solid rgba(255,255,255,0.4);
    width: 26px; 
    transform: rotate(90deg);
    color: rgba(255,255,255,0.4);
    font-face: Roboto, sans-serif;
    text-transform: uppercase;
    -webkit-user-select: none; 
    -moz-user-select: none; 
    -ms-user-select: none; 
}
div.fixed2 {
    position: fixed;
    top: 120px;
    left: 0;
    //border-top: 1px solid rgba(255,255,255,0.4);
    text-decoration: overline; 
    //border-bottom: 1px solid rgba(255,255,255,0.4);
    width: 26px; 
    transform: rotate(90deg);
    color: rgba(255,255,255,0.4); 
    font-face: Roboto, sans-serif; 
    text-transform: uppercase;
    -webkit-user-select: none; 
    -moz-user-select: none; 
    -ms-user-select: none; 
}
h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
}

table{
  overflow: scroll; 
  width:100%;
  
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3); 
  overflow-x: hidden;
 }
.tbl-content{
  height:300px;
  overflow-x: auto; 
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
#footer {
  position: static;
  margin-top: -10px; /* negative value of footer height */
  height: 25px;
  clear: both;
} 

/* demo styles */

@import url(http://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
body{
  background: -webkit-linear-gradient(left, #25c481, #25b7c4);
  background: linear-gradient(to right, #25c481, #25b7c4);
  font-family: 'Roboto', sans-serif;
  overflow: hidden; 
}

/* follow me template */
.made-with-love {


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
::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}
hr{
    padding: 0px;
    margin: 10px;    
  } 

</style>
</head>








<body id="gradient"> 

<div class="fixed2" id="scrollMid">
  <span style='color:rgba(255,255,255,0.7)'>hazard</span>
</div>

<div class="fixed1" id="scrollTop">
  <span style='color:rgba(255,255,255,0.7)'>overview</span>
</div>

<div class="high" id="top">

<?php
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set("Europe/Bucharest"); 
$ip = $_SERVER['REMOTE_ADDR']; 
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json")); 
file_put_contents("ips.txt", "app    " . date('Y m d   H i s') . "                  " . $ip . "   " . $details->country . "\n" . "   " . $details->region . "   " . $details->city . "   " . $details->hostname . "   " . $details->loc . "   " . $details->org . "\n", FILE_APPEND); 


$con=mysql_connect("localhost", "seba","seba1234");

if(!$con)
   {
    die('Nu s-a putut realiza conectarea:'.mysql_error());
   }

mysql_select_db("seba", $con);

$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
					FROM seba_data 
					ORDER BY CurrentDate 
					DESC LIMIT 1;");

echo "<br><div align='right' style='padding-right: 4%' class='container'>"; 
while($row=mysql_fetch_array($result)){
	echo "<br><br><font id='tempNot' style='font-size:60px; line-height: 0px; color: rgba(255,255,255,0.98);' face='Verdana'><span id='tempNotif'>";
	echo $row[1];
	echo "</span><a class='unselectable' onclick=lightUp()>&deg;C</a> &nbsp;&nbsp;&nbsp;  </font><hr id='tempHr' width=50% style='border: 1px solid rgba(255,255,255,0.8);'><font id='humNot' size=2 style='line-height: 4px; color: rgba(255,255,255,0.8);'>Humidity: <span id='humNotif'>"; 
	echo $row[2];
	echo "</span>% &emsp; Light: <span id='lightNotif'>"; 
	echo intval($row[3]/1024*100);
	echo "</span>% &nbsp; <br><br><br><b></font><font id='tempTimeNot' style='font-size:6px; line-height: 20px; color: rgba(255,255,255,0.6);' onClick='history.go(0)' id='lastDate'> Last update: <span id='tempTimeNotif'>";
	echo date('j M Y g:i:sA', strtotime($row[0])); 
	echo "</span></font></b>"; 
}
echo "</div><br>"; 






$result=mysql_query("Select * FROM seba_data ORDER BY CurrentDate DESC");

echo "<h1>Sensor data</h1>";
echo "	<section><div style='overflow:auto;'><div class='tbl-header'>";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th width='100px'>Current date</th><th width='100px'>Temperature</th><th width='80px'>Humidity</th><th width='70px'>Gas danger</th><th width='60px'>Light</th><th width='90px'>Miliseconds</th><th width='70px'>Last motion</th><th width='70px'>Alarm</th></tr></thead></table></div>";
echo "		<div class='tbl-content'><table id='wholeData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>";

$last=999999999; 
while($row=mysql_fetch_array($result)){
	if($last<$row[5]) break; 
	echo "<tr><td width='100px'>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td width='100px'>$row[1]&deg;C</td><td width='80px'>$row[2]%</td><td width='70px'>"; 
	echo intval($row[3]/1024*100);  
	echo "%</td><td width='60px'>";
	echo intval($row[4]/1024*100); 
	echo "%</td><td width='90px'>$row[5]</td><td width='70px'>$row[6]</td><td width='70px'>";
	if($row[7]==0) echo "OFF";
	else echo "ON"; 
	echo "</td></tr>";
	$last=$row[5]; 
}
echo "</tbody></table></div></div></section><br><br><br><br>";







$result=mysql_query("SELECT * FROM seba_data_gas ORDER BY CurrentDate Desc LIMIT 1");
$rowgas=mysql_fetch_array($result); 

$result=mysql_query("SELECT * FROM seba_data_motion ORDER BY CurrentDate Desc LIMIT 1");
$rowmot=mysql_fetch_array($result);

if($rowgas[0]>$rowmot[0]){
  $row_not=$rowgas;
  $row_ntf='gas'; 
}
else{ 
  $row_not=$rowmot; 
  $row_ntf='mot'; 
}

$ny=date('y', strtotime($row_not[0]));
$nm=date('m', strtotime($row_not[0]));
$nd=date('j', strtotime($row_not[0]));
$na=date('A', strtotime($row_not[0]));
$nh=date('G', strtotime($row_not[0])); // use g for 12h format 
$nM=date('i', strtotime($row_not[0]));
$ns=date('s', strtotime($row_not[0]));

$ay=date('y');
$am=date('m'); 
$ad=date('j');   

echo "<div class='lastAlert' ><font onClick='history.go(0)'>last alert</font></div>"; 
echo "<div class='hour'><p style='letter-spacing: -2px'>"; 

if($nh<10) echo "<indent style='padding-left: 40px'>";
echo "<span id='hourNotif'>".$nh."</span>";  
if($nh<10) echo "</indent>"; 
echo "</p></div><div class='uptime'>";
echo "<span id='minNotif'>".$nM."</span> &emsp; "; 
if($row_ntf=='gas'){
	echo " <font size='3px'><span id='whatNotif'>GAS ".intval($row_not[1]/1024*100)."%</span></div></div><div class='downtime'>";
	echo "<span id='secNotif'>".$ns."</span> &emsp; <font style='color: rgba(255,255,255,0.7)'><span id='whenNotif'>"; 



	if($nm==$am && $ny==$ay){
		if($nd==$ad) echo "today ";  
		else if($nd==$ad-1) echo "yesterday ";   
		else echo date('j M Y', strtotime($row_not[0])); 
	} 
	else
		echo date('j M Y', strtotime($row_not[0]));
	echo "</span></font></div>";
	/*echo "<div class='apm'>";
	echo $na."</div>"; */
}
else{
	echo " <font size='3px'><span id='whatNotif'>MOVEMENT</span></font> </div><div class='downtime'>";
	echo "<span id='secNotif'>".$ns."</span> &emsp; <font style='color: rgba(255,255,255,0.7)'><span id='whenNotif'>"; 



	if($nm==$am && $ny==$ay){
		if($nd==$ad) echo "today ";  
		else if($nd==$ad-1) echo "yesterday ";   
		else echo date('j M Y', strtotime($row_not[0])); 
	} 
	else
		echo date('j M Y', strtotime($row_not[0]));
	echo "</span></font></div>"; 
	/*echo "</div><div class='apm'>";
	echo $na."</div>"; */ 
}













//SELECT * FROM seba_data_gas ORDER BY CurrentDate Desc LIMIT 1

echo "</div><div style='margin-left: 4px; margin-right: 4px' class='high' id='middle'>"; 
$result=mysql_query("Select * FROM seba_data_gas ORDER BY CurrentDate DESC");

echo "<div style='float: left; width: 45%; padding-left: 2%; margin-top: 112px'><h1><font size=5> air <br> hazard </font></h1>";
echo "	<div class=\"tbl-header\">";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th>Exact time</th><th>Gas danger</th></tr></thead></table></div>";
echo "<div class=\"tbl-content\">";
echo "	<table id='gasData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "		<tbody>";

while($row=mysql_fetch_array($result)){
	echo "<tr><td>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td>"; 
	echo intval($row[1]/1024*100);  
	echo "%</td></tr>"; 
     }
echo "</tbody></table></div></div>"; 



$result=mysql_query("Select * FROM seba_data_motion ORDER BY CurrentDate DESC");

echo "<div style='float: right; width: 45%; padding-right: 2%; margin-top: 112px'><h1><font size=5>  motion <br> detect </font></h1>";
echo "	<div class=\"tbl-header\">";
echo "		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "			<thead><tr><th>Exact time</th><th>Last motion</th></tr></thead></table></div>";
echo "<div class=\"tbl-content\">";
echo "	<table id='motionData' cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
echo "		<tbody>";

while($row=mysql_fetch_array($result)){
	echo "<tr><td>".date('j M Y g:i:s A', strtotime($row[0]))."</td><td>$row[1]</td></tr>"; 
     }
echo "</tbody></table></div></div></div><br><br><br>";






mysql_close($con);
?>

</div> 
<div id="footer"></div>
</body>



<script>

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



/*
$(window).on("load resize ", function() {
  var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
  $('.tbl-header').css({'padding-right':scrollWidth});
}).resize(); 

$("div").on("scroll",function(){
    $("div:not(this)").scrollLeft($(this).scrollLeft()); 
});
*/



$(function(){
    var _left = $(".tbl-content").scrollLeft();
    var _direction;
    $(".tbl-content").scroll(function(){
        var _cur_left = $(".tbl-content").scrollLeft();
        if(_left < _cur_left)
        {
            _direction = 'right';
        }
        else
        {
            _direction = 'left'; 
        }
        _left = _cur_left;
        $("div .tbl-header").scrollLeft(_left);
        //console.log(_direction);
    });
})






function getElementY(query) {
  return window.pageYOffset + document.querySelector(query).getBoundingClientRect().top
}



function doScrolling(element, duration) {
  var startingY = window.pageYOffset
  var elementY = getElementY(element)
  // If element is close to page's bottom then window will scroll only to some position above the element.
  var targetY = document.body.scrollHeight - elementY < window.innerHeight ? document.body.scrollHeight - window.innerHeight : elementY
	var diff = targetY - startingY
  
  var easing = function (t) { return t<.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1 }
  var start

  if (!diff) return

	// Bootstrap our animation - it will get called right before next frame shall be rendered.
	window.requestAnimationFrame(function step(timestamp) {
    if (!start) start = timestamp
    // Elapsed miliseconds since start of scrolling.
    var time = timestamp - start
		// Get percent of completion in range [0, 1].
    var percent = Math.min(time / duration, 1)
    // Apply the easing.
    // It can cause bad-looking slow frames in browser performance tool, so be careful.
    percent = easing(percent)

    window.scrollTo(0, startingY + diff * percent)

		// Proceed with animation as long as we wanted it to.
    if (time < duration) {
      window.requestAnimationFrame(step)
    }
  })
}
// Apply event handlers. Example of firing the scrolling mechanism.
document.getElementById('scrollMid').addEventListener('click', doScrolling.bind(null, '#middle', 700))
document.getElementById('scrollTop').addEventListener('click', doScrolling.bind(null, '#top'   , 700)) 






var lastDate;  // GET THE LAST DATE FROM THE TABLE 
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
var lastDateGas;  // GET THE LAST GAS DATE FROM THE TABLE 
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                  //the script to call to get data          
      data: 'q=g',                        //you can insert url argumnets here to pass to api.php
                                       //for example "id=5&parent=6"
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
var lastDateMotion;  // GET THE LAST MOTION DATE FROM THE TABLE 
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                  //the script to call to get data          
      data: 'q=m',                        //you can insert url argumnets here to pass to api.php
                                       //for example "id=5&parent=6"
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
var lastIP;  // GET THE LAST IP FROM THE TABLE 
$.ajax({        
      type: "GET",                               
      url: 'get/getLast.php',                  //the script to call to get data          
      data: 'q=i',                        //you can insert url argumnets here to pass to api.php
                                       //for example "id=5&parent=6"
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        lastIP = data[1]; 
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);  
      }
    });

function refreshTable() { 
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

			if( i < 2 ) data[i].style.width="100px"; 
			else if 
			( i == 2 ) data[i].style.width="80px";
			else if 
			( i == 3 ) data[i].style.width="70px";
			else if 
			( i == 4 ) data[i].style.width="60px";
			else if 
			( i == 5 ) data[i].style.width="90px";
			else if 
			( i == 6 ) data[i].style.width="70px";
			else if 
			( i == 7 ) data[i].style.width="70px";

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
          var tempHrCol = 0.8; 

          document.getElementById("tempNot").style.opacity = 0; 
          document.getElementById("humNot").style.opacity = 0; 
          document.getElementById("tempTimeNot").style.opacity = 0; 
          document.getElementById("tempHr").style.opacity = 0; 

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
				c += tempHrCol/titleRef; 
				d += tempTimeNotifCol/titleRef; 

				if(a>tempNotifCol) a=tempNotifCol; 
				if(b>humNotifCol) b=humNotifCol; 
				if(c>tempHrCol) c=tempHrCol; 
				if(d>tempTimeNotifCol) d=tempTimeNotifCol; 

				document.getElementById("tempNot").style.opacity = Math.floor(a*100)/100; 
		        document.getElementById("humNot").style.opacity = Math.floor(b*100)/100; 
		        document.getElementById("tempHr").style.opacity = Math.floor(c*100)/100; 
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

function refreshTableGas() { 
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

          document.getElementById("hourNotif").innerHTML = hh; 
          document.getElementById("minNotif").innerHTML = mn; 
          document.getElementById("secNotif").innerHTML = sc; 
          document.getElementById("whatNotif").innerHTML = "GAS "+Math.floor(Data[1]/1024*100)+"%"; 
          document.getElementById("whenNotif").innerHTML = "TODAY"; 

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

function refreshTableMotion() { 
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


          document.getElementById("hourNotif").innerHTML = hh; 
          document.getElementById("minNotif").innerHTML = mn; 
          document.getElementById("secNotif").innerHTML = sc; 
          document.getElementById("whatNotif").innerHTML = "MOVEMENT"; 
          document.getElementById("whenNotif").innerHTML = "TODAY"; 


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
setInterval(refreshTable, 10000); 
setInterval(refreshTableGas, 10000); 
setInterval(refreshTableMotion, 10000); 

function lightUp() { 
    var lightURL = 'http://192.168.43.'+'164'+'/offline';  
    console.log(lightURL); 
    $.ajax({        
      type: "GET",                               
      url: lightURL,                 
      data: '',                        
                                      
      dataType: 'json',                     
      success: function(Data){
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        //console.log("Status: " + textStatus);
        //console.log("Error: " + errorThrown);  
      }
    });

    var lightURL = 'http://192.168.43.'+lastIP+'/offline';  
    console.log(lightURL); 
    $.ajax({        
      type: "GET",                               
      url: lightURL,                 
      data: '',                        
                                      
      dataType: 'json',                     
      success: function(Data){
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        //console.log("Status: " + textStatus);
        //console.log("Error: " + errorThrown);  
      }
    });

    var lightURL = 'http://192.168.1.'+'164'+'/offline';  
    console.log(lightURL); 
    $.ajax({        
      type: "GET",                               
      url: lightURL,                 
      data: '',                        
                                      
      dataType: 'json',                     
      success: function(Data){
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        //console.log("Status: " + textStatus);
        //console.log("Error: " + errorThrown);  
      }
    });

    var lightURL = 'http://192.168.1.'+lastIP+'/offline';  
    console.log(lightURL); 
    $.ajax({        
      type: "GET",                               
      url: lightURL,                 
      data: '',                        
                                      
      dataType: 'json',                     
      success: function(Data){
      }, 
      error: function(XMLHttpRequest, textStatus, errorThrown){
        //console.log("Status: " + textStatus);
        //console.log("Error: " + errorThrown);  
      }
    });
}
</script> 

</html>
