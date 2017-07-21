<?php
	session_start(); 
	if(empty($_SESSION["user"]))
		if(!empty($_POST['n'])){

			$mail=$_POST['n'];
			$pass=md5($_POST['p']);
			$con=mysql_connect("localhost", "seba","seba1234");
			$r=0; 
			if(!$con)
			   {
			    die('Nu s-a putut realiza conectarea:'.mysql_error());
			   }

			mysql_select_db("seba", $con);

			$result=mysql_query("SELECT Email, Pass, Nick, House
			                     FROM seba_signup 
			                     ORDER BY CurrentDate "); 

			while($row=mysql_fetch_array($result)){
				if($row[0]==$mail)
					if($row[1]==$pass){
						$r=1;
						$_SESSION["nick"]=$row[2];
						$_SESSION["house"]=$row[3]; 
					}
					else 
						echo '<script>alert("Passwords do not match!");</script>'; 
			}

			if($r==1)
				$_SESSION["user"] = $_POST['n'];
			else
				$_SESSION["user"] = ''; 
		}
		else
			$_SESSION["user"] = ''; 
?> 
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>myHaum</title>
		<link rel="shortcut icon" href="img/favicon.ico">
		<meta name="theme-color" content="#46b6fe" />
		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" /> 
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/pater.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link rel="stylesheet" type="text/css" href="css/content.css" />
		<script src="js/modernizr.custom.js"></script>
		<script>
			document.documentElement.className = 'js';
			var supportsCssVars = function() {
				var s = document.createElement('style'),
				support;

				s.innerHTML = "root: { --tmp-var: bold; }";
				document.head.appendChild(s);
				support = !!(window.CSS && window.CSS.supports && window.CSS.supports('font-weight', 'var(--tmp-var)'));
				s.parentNode.removeChild(s);
				return support;
			}
			if (!supportsCssVars()) alert('Please view this demo in a modern browser that supports CSS Variables.')
		</script>
		<style>
		.content__big {
			font-family: 'nexa_boldregular', sans-serif;
		}
		button:focus {outline:0;} 
		.pater::after {
			content: 
		<?php if($_SESSION["house"]=="Stanici"){ 
				$con=mysql_connect("localhost", "seba","seba1234");
				if($con){
				mysql_select_db("seba", $con);

				$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
				                     FROM seba_data 
				                     ORDER BY CurrentDate 
				                     DESC LIMIT 1;"); 
				$row=mysql_fetch_array($result); 
				echo '"';
				  $hr = $row[0][11].$row[0][12]; 
		          $apm; 
		          $monthNames = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" );
		          if( $hr > 12 ){ $apm = "pm"; $hr-=12; } 
		          else $apm = "am"; 

		          $hh = $row[0][11] . $row[0][12]; 
		          $mn = $row[0][14] . $row[0][15]; 
		          $sc = $row[0][17] . $row[0][18]; 

		          $mt = $row[0][6]; 
		          if($row[0][5]==1) 

		            $mt += 10; 
		          $mt = $monthNames[$mt-1]; 
		          $day = $row[0][8].$row[0][9]; 
				if(date('j')==$day&&date('M')==$mt) $date = 'today '.$hr.':'.$row[0][14].$row[0][15].':'.$row[0][17].$row[0][18].$apm;
				else if(date('j')-1==$day&&date('M')==$mt) $date = 'yesterday '.$day.' '.$mt.' '.$row[0][0].$row[0][1].$row[0][2].$row[0][3].' '.$hr.':'.$row[0][14].$row[0][15].':'.$row[0][17].$row[0][18].$apm;
				else $date = $day.' '.$mt.' '.$row[0][0].$row[0][1].$row[0][2].$row[0][3].' '.$hr.':'.$row[0][14].$row[0][15].':'.$row[0][17].$row[0][18].$apm;
				echo $date; 
				echo '"';   
			}

		 }
		 else 
		 	echo '"Our product"'; 
		 ?> 
		;
		}
		</style> 
	</head>
	<body class="demo-2 loading"> 
		<main>
			<div class="morph-wrap">
				<svg class="morph" width="1400" height="770" viewBox="0 0 1400 770">
					<path d="M 262.9,252.2 C 210.1,338.2 212.6,487.6 288.8,553.9 372.2,626.5 511.2,517.8 620.3,536.3 750.6,558.4 860.3,723 987.3,686.5 1089,657.3 1168,534.7 1173,429.2 1178,313.7 1096,189.1 995.1,130.7 852.1,47.07 658.8,78.95 498.1,119.2 410.7,141.1 322.6,154.8 262.9,252.2 Z"/>
				</svg>
			</div>
			
			<?php if($_SESSION["user"]=="") { 
					?>  
			<div id="logsign" class="mockup-content" style="left: 0; top: 0; z-index: 123">
			<script>
				if(screen.width > 800) {
					document.getElementById("logsign").style.position = "fixed"; 
				}
				else{
					document.getElementById("logsign").style.position = "absolute"; 
					document.getElementById("logsign").style.left = "58%"; 
					document.getElementById("logsign").style.top = "0"; 
				}
			</script>
						<div id="mobileErrorP3" class="morph-button morph-button-modal morph-button-modal-3 morph-button-fixed" style="width: 50%">
							<button id="buttonSignUp" style="left: 0%; top: 0%; height: 70%; width: 60%; color: white; background-color: transparent;"><b>Signup</b></button>
							<div class="morph-content">
								<div>
									<div class="content-style-form content-style-form-1">
										<span onclick="signupOFF();" class="icon icon-close">Close the dialog</span>
										<font style="font-size: 6px"><h2>Sign Up</h2></font>
										<form method="POST" action="action/signup.php">
											<p><label style="color: rgba(0,0,0,0.5)">Nickname</label><input maxlength="20" name="nick" type="text" required></p>
											<p><label style="color: rgba(0,0,0,0.5)">Email</label><input name="email" type="email" required></p>
											<p><label style="color: rgba(0,0,0,0.5)">Password</label><input minlength="8" name="pass" type="password" required></p>
											<p><label style="color: rgba(0,0,0,0.5)">Repeat Password</label><input minlength="8" name="pass2" type="password" required></p>
											<p><input style="background-color: #8C4D81" type="submit" value="Sign Up"></input></p>
										</form> 
									</div>
								</div>
							</div> 
						</div><!-- morph-button -->
						
						<div id="mobileErrorP" class="morph-button morph-button-modal morph-button-modal-2 morph-button-fixed">
							<button id="buttonLogIn" style="left: 30%; top: -100%; height: 70%; width: 50%; color: white; background-color: transparent;">Login</button>
							<div id="login" class="morph-content" id="mobileLong">
								<div>
									<div class="content-style-form content-style-form-2">
										<span onclick="loginOFF();" class="icon icon-close">Close the dialog</span>
										<h2>Login</h2>
										<form action="" method="POST">
											<p><label style="color: rgba(0,0,0,0.5)">Email</label><input name="n" type="email" required></p>
											<p><label style="color: rgba(0,0,0,0.5)">Password</label><input name="p" type="password" required></p>
											<p><input type="submit" style="background-color: #8C4D81" value="Login"></input></p>
										</form>
									</div>
								</div>
							</div>
						</div><!-- morph-button -->
					</div><!-- /form-mockup -->
			<?php } ?> 

			<div class="content content--fixed" id="headUp"> 
				<header class="codrops-header">
				  <h1 class="codrops-header__title">
					<?php if($_SESSION["user"]!="")
						echo "<div>".$_SESSION["nick"]."'s myHaum menu </div>"; 
					?> 
					</h1>
				</header>	

				<script>
				function destroySession(){
					window.location.href = "logoff.php";
				}
				</script>



					<?php if(empty($_SESSION["user"])||$_SESSION["house"]!="Stanici"){ ?>  
					<script>
					if (screen.width > 800) {
					  document.write('<a class="pater" href="#"><svg class="pater__deco" width="300" height="240" viewBox="0 0 1000 800"><path d="M27.4,171.8C73,42.9,128.6,1,128.6,1s0,0,0,0c58.5,0,368.3,0.3,873.2,0.8c38.5,211,42.1,373.5,38.9,476.7c-2.5,80.3-10.6,174.9-76.7,247.8c-15.1,16.6-37.4,41.2-72.8,53.9c-92.4,33.1-173-50.8-283.9-99.4c-224.3-98.4-334.9,51.4-472.2-45.6C-6.3,535.2-14.5,290.6,27.4,171.8z"/></svg><h4 class="pater__title">Reliable, low-cost, unique.</h4><p class="pater__desc">Our vision since the start of the project was set on approachability and pliability.</p></a>');
					} 
					</script>

					<?php }else{ ?>

					<script>
					if (screen.width > 800) { // desktop 
					document.write('<a class="pater" href="../display.php"><svg class="pater__deco" width="300" height="240" viewBox="0 0 1000 800"><path d="M27.4,171.8C73,42.9,128.6,1,128.6,1s0,0,0,0c58.5,0,368.3,0.3,873.2,0.8c38.5,211,42.1,373.5,38.9,476.7c-2.5,80.3-10.6,174.9-76.7,247.8c-15.1,16.6-37.4,41.2-72.8,53.9c-92.4,33.1-173-50.8-283.9-99.4c-224.3-98.4-334.9,51.4-472.2-45.6C-6.3,535.2-14.5,290.6,27.4,171.8z"/></svg><h4 class="pater__title">');

					<?php 

					$con=mysql_connect("localhost", "seba","seba1234");

					if(!$con)
					   {
					    die('Nu s-a putut realiza conectarea:'.mysql_error());
					   } 
					else{

					mysql_select_db("seba", $con);

					$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
					                     FROM seba_data 
					                     ORDER BY CurrentDate 
					                     DESC LIMIT 1;"); 
					$row=mysql_fetch_array($result); 

					if($row[3]/1024*100>40)
						echo "document.write('Bright   ');";
					else if($row[3]/1024*100>10)
						echo "document.write('Moody   ');";
					else
						echo "document.write('Dark   ');"; 
					echo 'document.write("<font size=6>';
					echo $row[1] . '&deg;C</font>");';
					
						}
					?>

					document.write('</h4><p class="pater__desc">There are ');

					<?php 

					$con=mysql_connect("localhost", "seba","seba1234");

					if(!$con)
					   {
					    die('Nu s-a putut realiza conectarea:'.mysql_error());
					   } 
					else{

					mysql_select_db("seba", $con);

					$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
					                     FROM seba_data 
					                     ORDER BY CurrentDate 
					                     DESC LIMIT 1;"); 
					$row=mysql_fetch_array($result); 

					echo 'document.write("' . $row[1] . '&deg;C in the main room, with ' . $row[2] . '% humidity and a brigthness point of ' . intval($row[3]/1024*100) . '%.");';
					
					
						}
					?>

					document.write('</p></a>');
					}
					else{ // mobile
						document.write('<a content="Yes" class="pater" href="../display.php"><svg class="pater__deco" width="300" height="240" viewBox="0 0 1000 800"><path d="M27.4,171.8C73,42.9,128.6,1,128.6,1s0,0,0,0c58.5,0,368.3,0.3,873.2,0.8c38.5,211,42.1,373.5,38.9,476.7c-2.5,80.3-10.6,174.9-76.7,247.8c-15.1,16.6-37.4,41.2-72.8,53.9c-92.4,33.1-173-50.8-283.9-99.4c-224.3-98.4-334.9,51.4-472.2-45.6C-6.3,535.2-14.5,290.6,27.4,171.8z"/></svg><h4 class="pater__title">');

					<?php 

					$con=mysql_connect("localhost", "seba","seba1234");

					if(!$con)
					   {
					    die('Nu s-a putut realiza conectarea:'.mysql_error());
					   } 
					else{

					mysql_select_db("seba", $con);

					$result=mysql_query("SELECT CurrentDate, Temperature, Humidity, Light 
					                     FROM seba_data 
					                     ORDER BY CurrentDate 
					                     DESC LIMIT 1;"); 
					$row=mysql_fetch_array($result); 

					if($row[3]/1024*100>40)
						echo "document.write('Bright   ');";
					else if($row[3]/1024*100>10)
						echo "document.write('Moody   ');";
					else
						echo "document.write('Dark   ');"; 
					echo 'document.write("<font size=3>';
					echo $row[1] . '&deg;C</font>");'; 
						}
					?>

					document.write('</h4><p class="pater__desc"></p></a>'); 
					}

					</script>

					<?php } ?> 
				
				<div class="deco deco--title">2017 Gălăciuc</div> 
			</div>

			<div class="content-wrap">
				<font style="font-size: 70px" class="content__big"> 
				<?php 
					$hi = array("Welcome, ", "Hey there, ", "Good day, ", "Hello, ");
					echo $hi[rand(0, count($hi) - 1)]; 
					if($_SESSION["user"]!=""){
						echo "<a href='../display.php'>".$_SESSION["nick"]."</a>!";
					}
					else{
					$input = array("friend!", "beautiful!", "cool one!", "bud!", "gorgeous!", "amigo¡");
					echo $input[rand(0, count($input) - 1)]; }
				?> </font> <br> 
				<font size="3em" class="content__big"> This is myHaum security's webpage.  
				<script type="text/javascript">
					if(screen.width<=800)
						document.write("<br>"); 
				</script>
				<?php
					if($_SESSION['user']!="")
						echo 'You can log out <a onclick="destroySession();">here</a>.';
					else
						echo 'Scroll down for more!'; 
				?>
				</font>
			</div> 

			<div class="content-wrap">
				<div class="content content--layout content--layout-3">
					<img class="content__img" src="img/2.jpg" alt="Some image" />
					<h3 class="content__title" style="position: absolute; margin-top: 102px; margin-left: 80px;">myHaum</h3>
					<p class="content__desc">Your home guardian, featuring thousands of possibilites, the system was designed for any existing house. </p>
					<a href="form/map" class="content__link">Discover more</a>
				</div>
			</div> 

			<!--
			<div class="content-wrap">
				<div class="content content--layout content--layout-1">
					<img class="content__img" src="img/3.jpg" alt="Some image" />
					<h3 class="content__title">Terms</h3>
					<p class="content__desc">Our terms short..</p>
					<a href="#" class="content__link">Discover</a>
				</div>
			</div> -->

			<div class="content-wrap">
				<div class="content content--layout content--layout-4" id="videoLayout">
					<img class="content__img" src="img/4.jpg" alt="Some image" />
					<h3 class="content__title">in action</h3>
					<p class="content__desc" style="width: 120%">
						We created a featured video displaying the benefits of the product, here it is. 
						<div id="videoId" class="morph-button morph-button-modal morph-button-modal-4 morph-button-fixed" style="margin-left: 320%; margin-top: 20%">
							<button type="button">Watch video</button>
							<div class="morph-content">
								
								<div> 
									<div class="content-style-video">
										<label class="icon icon-close">Close the dialog</label>
										<div class="video-mockup"><iframe frameborder="0" allowfullscreen width="100%" height="112%" src="https://www.youtube.com/embed/huV5ykzUKsU">
										</iframe></div>
									</div>
								</div>
							</div>
						</div><!-- morph-button -->

					</p> 
				</div>
			</div>

			<div class="content-wrap">
				<div class="content content--layout content--layout-1">
					<img class="content__img" src="img/5.jpg" alt="Some image" />
					<h3 class="content__title">pre-order</h3>
					<p class="content__desc">You can pre-order our product <a href="form">here</a> and you might get a gift for becoming a tester. </p> 
				</div>
			</div> 

			<div class="content-wrap">
				<div id="mobileErrorP2" class="content content--layout content--layout-2">
					<img class="content__img" src="img/1.jpg" alt="Some image" />
					<h3 class="content__title">contact</h3>
					<p class="content__desc"> 

						Do you see a potential growth within this project? Or have you encountered any problem with myHaum? Send an e-mail <a href="mailto:stanicisebastian@yahoo.com?subject=myHaum%20website%20contact"> here</a>.  

					</p>
				</div>
			</div>


			<script>
				if (screen.width <= 800) {
				  document.write('<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>'); 
				  document.getElementById("videoId").style.top = "140px"; 
				  document.getElementById("videoId").style.left = "-130px"; 
				  document.getElementById("videoLayout").style.height = "407px";
				}
			</script>

			<!-- Related demos -->
			<section class="content content--related">
				<p class="content__info">If you think our concept is cool,
					<script>
						if (screen.width <= 800) {
						  document.write('<br/>');
						}
					</script>
				 please share with your friends <br> or subscribe to 
				 <script type="text/javascript">
					if(screen.width <= 800){
						document.write('<br>'); 
					}
				</script>	
				 our newsletter for updates!</p>
				
				 <center>
				 <script>
					if (screen.width > 800) {
					  document.write('</center><div style="margin-right: 0%; ">');
					}
				</script>
				<div id="sb2" class="mockup-content" style="width: 50%">
					<div class="morph-button morph-button-inflow morph-button-inflow-1">
						<button id="sub" onmouseover="subClick()" type="button"><span>Subscribe to our Newsletter</span></button>
						<div class="morph-content">
							<div>
								<div class="content-style-form content-style-form-4">
									<h2 class="morph-clone"><font size="3">Subscribe to our Newsletter</font></h2>
									<form id="newsletter" method="POST" action="action/subscribe.php"> 
										<p><label>Your Email Address</label><input name="email" type="email" required><span>We promise, we won't send you any spam. Just love.</span></p>
										<p><input form="newsletter" type="submit" value="Subscribe me"></input></p>
									</form>
								</div>
							</div>
						</div>
					</div><!-- morph-button --> 
				</div> 
		
				<div id="sh2" class="morph-button morph-button-inflow morph-button-inflow-2" style="margin-left: 14%; margin-top: 8%; background-color: transparent">
					<button id="share" onmouseover="shareClick()" type="button" style="background-color: rgba(255,255,255,0.1); color: white"><span>Share our idea</span></button>
					<div class="morph-content" style="background-color: rgba(255,255,255,0.4)">
						<div>
							<div class="content-style-social"> 
								<a class="twitter" target="_blank" href="http://twitter.com/share?url=http://seba.tm-edu.ro&text=Check this out!"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path id="twitter" d="M26.667 0h-21.333c-2.934 0-5.334 2.4-5.334 5.334v21.332c0 2.936 2.4 5.334 5.334 5.334h21.333c2.934 0 5.333-2.398 5.333-5.334v-21.332c0-2.934-2.399-5.334-5.333-5.334zM26.189 10.682c0.010 0.229 0.015 0.46 0.015 0.692 0 7.069-5.288 15.221-14.958 15.221-2.969 0-5.732-0.886-8.059-2.404 0.411 0.050 0.83 0.075 1.254 0.075 2.463 0 4.73-0.855 6.529-2.29-2.3-0.043-4.242-1.59-4.911-3.715 0.321 0.063 0.65 0.096 0.989 0.096 0.479 0 0.944-0.066 1.385-0.188-2.405-0.492-4.217-2.654-4.217-5.245 0-0.023 0-0.045 0-0.067 0.709 0.401 1.519 0.641 2.381 0.669-1.411-0.959-2.339-2.597-2.339-4.453 0-0.98 0.259-1.899 0.712-2.689 2.593 3.237 6.467 5.366 10.836 5.589-0.090-0.392-0.136-0.8-0.136-1.219 0-2.954 2.354-5.349 5.257-5.349 1.512 0 2.879 0.65 3.838 1.689 1.198-0.24 2.323-0.685 3.338-1.298-0.393 1.249-1.226 2.298-2.311 2.96 1.063-0.129 2.077-0.417 3.019-0.842-0.705 1.073-1.596 2.015-2.623 2.769z" fill="#00aced" /></svg><span style="color: white">Share on Twitter</span></a>
								<a class="facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=http://seba.tm-edu.ro"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path id="facebook" d="M26.667 0h-21.333c-2.933 0-5.334 2.4-5.334 5.334v21.332c0 2.936 2.4 5.334 5.334 5.334l21.333-0c2.934 0 5.333-2.398 5.333-5.334v-21.332c0-2.934-2.399-5.334-5.333-5.334zM27.206 16h-5.206v14h-6v-14h-2.891v-4.58h2.891v-2.975c0-4.042 1.744-6.445 6.496-6.445h5.476v4.955h-4.473c-1.328-0.002-1.492 0.692-1.492 1.985l-0.007 2.48h6l-0.794 4.58z" fill="#3b5998" /></svg><span style="color: white">Share on Facebook</span></a>
								<a class="googleplus" target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://seba.tm-edu.ro"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path id="googleplus" d="M0.025 27.177c-0.008-0.079-0.014-0.158-0.018-0.238 0.004 0.080 0.011 0.159 0.018 0.238zM7.372 17.661c2.875 0.086 4.804-2.897 4.308-6.662-0.497-3.765-3.231-6.787-6.106-6.873-2.876-0.085-4.804 2.796-4.308 6.562 0.496 3.765 3.23 6.887 6.106 6.973zM32 8v-2.666c0-2.934-2.399-5.334-5.333-5.334h-21.333c-2.884 0-5.25 2.32-5.33 5.185 1.824-1.606 4.354-2.947 6.965-2.947 2.791 0 11.164 0 11.164 0l-2.498 2.113h-3.54c2.348 0.9 3.599 3.629 3.599 6.429 0 2.351-1.307 4.374-3.153 5.812-1.801 1.403-2.143 1.991-2.143 3.184 0 1.018 1.93 2.75 2.938 3.462 2.949 2.079 3.904 4.010 3.904 7.233 0 0.513-0.064 1.026-0.19 1.53h9.617c2.934 0 5.333-2.398 5.333-5.334v-16.666h-6v6h-2v-6h-6v-2h6v-6h2v6h6zM5.809 23.936c0.675 0 1.294-0.018 1.936-0.018-0.848-0.823-1.52-1.831-1.52-3.074 0-0.738 0.236-1.448 0.567-2.079-0.337 0.024-0.681 0.031-1.035 0.031-2.324 0-4.297-0.752-5.756-1.995v2.101l0 6.304c1.67-0.793 3.653-1.269 5.809-1.269zM0.107 27.727c-0.035-0.171-0.061-0.344-0.079-0.52 0.018 0.176 0.045 0.349 0.079 0.52zM14.233 29.776c-0.471-1.838-2.139-2.749-4.465-4.361-0.846-0.273-1.778-0.434-2.778-0.444-2.801-0.030-5.41 1.092-6.882 2.762 0.498 2.428 2.657 4.267 5.226 4.267h8.951c0.057-0.348 0.084-0.707 0.084-1.076 0-0.392-0.048-0.775-0.137-1.148z" fill="#d34836" /></svg><span style="color: white">Share on Google+</span></a>
							</div>
						</div>
					</div>
				</div><!-- morph-button -->
				<script type="text/javascript">
					if(screen.width <= 800){
						document.write('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'); 
					}
				</script>	
				
			</section>
		</main>
		<?php
		if($_SESSION["user"]!=""){
		?>
		<script>
		if(screen.width>0){
			document.getElementById("headUp").style.left="0%";
			document.getElementById("headUp").style.top="0%";
		}
		</script>
		<?php } else{ ?>
		<script>
		if(screen.width<=800){
			document.getElementById("headUp").style.left="7%";
			document.getElementById("headUp").style.top="-1%";
		}
		</script> 
		<?php } ?>
		<script src="js/imagesloaded.pkgd.min.js"></script>
		<script src="js/anime.min.js"></script>
		<script src="js/scrollMonitor.js"></script>
		<script src="js/demo2.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/uiMorphingButton_fixed.js"></script>	
		<script src="js/uiMorphingButton_inflow.js"></script> 
		<script> 
		function signupOFF() {
				setTimeout(function () {
			        function jsHello(i) {
					    if (i > 1) return;

					    setTimeout(function () {

					        document.getElementById("mobileErrorP3").style.opacity = i;

					        i = i + 0.1; 
					        jsHello(i);

					    }, 10);
					}

					jsHello(0);
			    }, 800);
			    document.getElementById("mobileErrorP3").style.opacity = "0";
			}

			function loginOFF() {
				setTimeout(function () {
			        function jsHello(i) {
					    if (i > 1) return;

					    setTimeout(function () {

					        document.getElementById("mobileErrorP").style.opacity = i;

					        i = i + 0.1; 
					        jsHello(i);

					    }, 10);
					}

					jsHello(0);
			    }, 800);
			    document.getElementById("mobileErrorP").style.opacity = "0";
			}

			function subClick() { 
				document.getElementById("sub").click();
				if(screen.width <= 800){
					for(var i=0;i<=50;i++){ 
						console.log(document.getElementById("sh2").style.marginTop); 
					  	document.getElementById("sh2").style.marginTop = i+"%";
						
					}
				} 
			}
			function shareClick() {
			    document.getElementById("share").click();
			}

			(function() {
				var docElem = window.document.documentElement, didScroll, scrollPosition;

				function noScrollFn() {
					window.scrollTo( scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0 );
				}

				function noScroll() {
					window.removeEventListener( 'scroll', scrollHandler );
					window.addEventListener( 'scroll', noScrollFn );
				}

				function scrollFn() {
					window.addEventListener( 'scroll', scrollHandler );
				}

				function canScroll() {
					window.removeEventListener( 'scroll', noScrollFn );
					scrollFn();
				}

				function scrollHandler() {
					if( !didScroll ) {
						didScroll = true;
						setTimeout( function() { scrollPage(); }, 60 );
					}
				};

				function scrollPage() {
					scrollPosition = { x : window.pageXOffset || docElem.scrollLeft, y : window.pageYOffset || docElem.scrollTop };
					didScroll = false;
				};

				scrollFn();

				[].slice.call( document.querySelectorAll( '.morph-button' ) ).forEach( function( bttn ) {
					new UIMorphingButton( bttn, {
						closeEl : '.icon-close',
						onBeforeOpen : function() {
							noScroll();
						},
						onAfterOpen : function() {
							canScroll();
						},
						onBeforeClose : function() {
							noScroll();
						},
						onAfterClose : function() {
							canScroll();
						}
					} );
				} );

				[].slice.call( document.querySelectorAll( 'form button' ) ).forEach( function( bttn ) { 
					bttn.addEventListener( 'click', function( ev ) { ev.preventDefault(); } );
				} );
			})();
		</script> 
	</body>
</html>
