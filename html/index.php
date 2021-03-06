<?php
/*
	Page generate script: /index.php
	This script generates login screen and is the homepage of the website.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Username" text field
		"Password" text field
		"Login" button - Runs AJAX /functions/login.php
		"Forgot Password?" button - Hyperlinks to /forgot1.php
		"Register" button - Hyperlinks to /register.php
	
	AJAX functions:
		login.php
			Inputs
				'username'
				'password'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Succesful login
					Redirect to profile.php
				Case "2" - Unsuccesful login
					Show unsuccesful login message and clear text fields
	
	Page connections:
		/profile/index.php
		/forgot1.php
		/register.php
*/



//Start/Load Session
session_start();

/*	
	This section acts upon users who are already logged in to an account in their session.
	
	If the user has been idle for ten minutes (i.e. an old session), the user is logged out 
		and the script continues.
	Else, the user is redirected to their profile homepage.
*/

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);		
	}
	else{										
		header('Location: profile/index.php', true, 303);
		exit();
	}
}

echo "<html>
 	<head> 
   		<title>Log In</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
  	</head>
  	<body> 
		<h1>Log In</h1>
   		 	<form name=\"loginForm\">
				Username: <input type=\"text\" name=\"username\" id=\"username\"><br>  
				Password: <input type=\"password\" name=\"password\" id=\"password\"><br>
				<input type=\"button\" value=\"Submit\" onClick=\"loadDoc('functions/login.php', myFunction)\">
			</form>
			<a href=\"forgot1.php\" hidden>Forgot Password?</a>
			<a href=\"register.php\">Register?</a>
	</body>
	<script>
		var user = document.getElementById('username');
		var pass = document.getElementById('password');
		user.addEventListener(\"keydown\", function (e) {
			if (e.keyCode === 13) {
				pass.focus();
			}
		}); 
		pass.addEventListener(\"keydown\", function (e) {
			if (e.keyCode === 13) {
				loadDoc('functions/login.php', myFunction);
			}
		}); 
		function loadDoc(url, cFunction) {
			var username = document.getElementById('username').value; 
			var password = document.getElementById('password').value; 
			var attributes = \"username=\" + username + \"&password=\" + password;
	
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					cFunction(this);
				}
			};
			xhttp.open(\"POST\", url, true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
	
		function myFunction(xhttp) {
			switch(xhttp.responseText){
			case \"0\": 
				alert(\"Login Script Failure\");
				break;
		
			case \"1\": 
				window.location = \"profile/index.php\";
				break;
		
			case \"2\": 
				alert(\"Incorrect Username or Password\");
				break;
			}
		}
	</script>
</html>";

exit();

?>