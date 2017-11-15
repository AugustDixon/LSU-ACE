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
		"Forgot Password?" button - Hyperlinks to /forgotPassword.php
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
	if(($_SESSION['idle'] + 600) > time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);		
	}
	else{										
		header('Location: profile.php', true, 303);
		exit();
	}
}

echo "
	<html>
 	<head> 
   		 <title>Log In</title>
  	</head>
  	<body> 
		<h1>Log In</h1>
   		 	<form name=\"loginForm\">
				Username: <input type=\"text\" name=\"username\"><br>  
				Password: <input type=\"text\" name=\"password\"><br>
				<input type=\"button\" value=\"Submit\" onClick=\"loadDoc('functions/login.php', myFunction)\">
			</form>
			<a href=\"forgot1.php\">Forgot Password?</a>
			<a href=\"register.php\">Register?</a>
  	</body>
	
	<script>
	function loadDoc(url, cFunction) 
	{
		var username = document.getElementById('loginForm').username; 
		var password = document.getElementById('loginForm').password; 
		var attributes = 'username=' + username '&password=' + password;
	
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
		xhttp.send(attributes);
	}
	
	function myFunction(xhttp) 
	{
		switch(xhttp.responseText)
		{
		case \"0\": 
		
		break;
		
		case \"1\": 
		window.location = \"profile/index.php\"
		break;
		
		case \"2\": 
		
		break;
		}
	}
	</script>
</html>";

exit();

?>