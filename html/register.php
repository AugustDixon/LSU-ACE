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
		header('Location: https://ec2-34-226-212-195.compute-1.amazonaws.com/profile.php', true, 303);
		exit();
	}
}

echo "";

exit();


?>