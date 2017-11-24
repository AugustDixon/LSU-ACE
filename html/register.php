<?php
/*
	Page gerenate script: /register.php
	This script generates the register account page. HTTPS Required.
	
	HTTP Inputs:
		No inputs besides session data.
		
	Page Features:
		"Username" text field - 1 to 20 characters. Must be Unique.
		"Nickname" text field - 0 to 20 characters.
		"First Name" text field - 0 to 20 characters.
		"Last Name" text field - 0 to 20 characters
		"Phone Number" text field - 0 to 20 characters
		"Password" text field - 5 to 20 characters
		"Confirm Password" text field - Must match Password
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
				Username: <input type=\"text\" name=\"username\" id=\"username\"><br>
				Nickname: <input type=\"text\" name=\"nickname\" id=\"nickname\"><br>
				First name: <input type=\"text\" name=\"firstName\" id=\"firstName\"><br>
				Last name: <input type=\"text\" name=\"lastName\" id=\"lastName\"><br>
				Phone number: <input type=\"text\" name=\"phoneNumber\" id=\"phoneNumber\"><br>
				Password: <input type=\"text\" name=\"password\" id=\"password\"><br>
				Confirm Password: <input type=\"text\" name=\"confirmPassword\" id=\"confirmPassword\"><br>
				<input type=\"button\" value=\"Register\" onClick=\"loadDoc('functions/registerAccount.php', myFunction)\">
			</form>	
  	</body>
	
	</body>
	
	<script>
	function loadDoc(url, cFunction) 
	{
		var username = document.getElementById('username').value;
		var nickname = document.getElementById('nickname').value;
		var firstName = document.getElementById('firstName').value;
		var lastName = document.getElementById('lastName').value;
		var phoneNumber = document.getElementById('phoneNumber').value;
		var password = document.getElementById('password').value;
		var confirmPassword = document.getElementById('confirmPassword').value;
		var attributes = 'username=' + username + '&nickname=' + nickname + '&firstname=' + firstName + '&lastname=' + lastName + '&phone=' + phoneNumber + '&password=' + password + '&cpass=' + confirmPassword;
	
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
	
	function myFunction(xhttp) 
	{
		switch(xhttp.responseText){
		case \"0\": 
			alert(\"Register Script Failure\");
			break;
		
		case \"1\": 
			window.location = \"profile/index.php\"
			break;
		
		case \"2\": 
			alert(\"Username must be between 1 and 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
			
		case \"3\":
			alert(\"Nickname must be less than 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"4\":
			alert(\"First Name must be less than 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"5\":
			alert(\"Last Name must be less than 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"6\":
			alert(\"Phone Number must be less than 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"7\":
			alert(\"Password must be between 5 and 20 characters\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"8\":
			alert(\"Passwords do not match\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		
		case \"9\":
			alert(\"Username already exists\");
			document.getElementById('password').value = \"\";
			document.getElementById('confirmPassword').value = \"\";
			break;
		}
	}
	</script>
</html>";

exit();


?>