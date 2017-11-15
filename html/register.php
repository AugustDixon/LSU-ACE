<?php
/*
	Page gerenate script: /register.php
	This script generates the register account page. HTTPS Required.
	
	HTTP Inputs:
		No inputs besides session data.
		
	Page Features:
		"Username" text field
		"Nickname" text field
		"First Name" text field
		"Last Name" text field
		"Phone Number" text field
		"Password" text field
		"Confirm Password" text field
		"Register" button - runs AJAX /functions/registerAccount.php
		
	AJAX Functions:
		registerAccount.php
			Inputs
				'username'
				'nickname'
				'firstname'
				'lastname'
				'phone'
				'password'
				'cpass'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Successful Account Creation
					Redirect the user to their profile page.
				Case "2" - Username constraint error
					Show message and constraints and clear password fields
				Case "3" - Nickname constraint error
					Show message and constraints and clear password fields
				Case "4" - First Name constraint error
					Show message and constraints and clear password fields
				Case "5" - Last Name constraint error
					Show message and constraints and clear password fields
				Case "6" - Phone Number constraint error
					Show message and constraints and clear password fields
				Case "7" - Password contraint error
					Show message and constraints and clear password fields
				Case "8" - Passwords do not match
					Show message and clear password fields
				Case "9" - Username already exists
					Show message and clear password fields

	Page Connections:
		/index.php
		/profile/index.php
*/

session_start();

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

echo "<html>
 	<head> 
   		 <title>Register</title>
  	</head>
  	<body> 
		<h1>Register</h1>
   		 	<form name=\"registerForm\">
				Username: <input type=\"text\" name=\"username\"><br>
				Nickname: <input type=\"text\" name=\"nickname\"><br>
				First name: <input type=\"text\" name=\"firstName\"><br>
				Last name: <input type=\"text\" name=\"lastName\"><br>
				Phone number: <input type=\"text\" name=\"phoneNumber\"><br>
				Password: <input type=\"text\" name=\"password\"><br>
				Confirm Password: <input type=\"text\" name=\"confirmPassword\"><br>
				<input type=\"button\" value=\"Register\" onClick=\"loadDoc('functions/register.php', myFunction)\">
			</form>	
  	</body>
	
	</body>
	
	<script>
	function loadDoc(url, cFunction) 
	{
		var username = document.getElementById('registerForm').username;
		var nickname = document.getElementById('registerForm').nickname;
		var firstName = document.getElementById('registerForm').firstName;
		var lastName = document.getElementById('registerForm').lastName;
		var phoneNumber = document.getElementById('registerForm').phoneNumber;
		var password = document.getElementById('registerForm').password;
		var confirmPassword = document.getElementById('registerForm').confirmPassword;
		var attributes = 'username=' + username '&nickname=' + nickname '&firstName=' + firstName '&lastName=' + lastName '&phoneNumber=' + phoneNumber '&password=' + password '&confirmPassword=' + confirmPassword;
	
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