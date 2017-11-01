<?php
/*
	Function script: /functions/registerAccount.php
	This script creates a new account. This script should only be called using HTTPS.
	
	Script Inputs:
	'username'
		Max length 20 char
	'nickname'
		Max length 20 char
	'firstname'
		Max length 20 char
	'lastname'
		Max length 20 char
	'phone'
		Max length 20 char
	'password'
		Max length 20 char
	'cpass'
		Max length 20 char
	
	Script Output Codes:
		0 = Script Failure
		1 = Successful Account Creation
		2 = Username Constraint Error
		3 = Nickname Constraint Error
		4 = First Name Constraint Error
		5 = Last Name Constraint Error
		6 = Phone Number Constraint Error
		7 = Password Constraint Error
		8 = Passwords Do Not Match
		9 = Username Already Exists
*/

//Start/Load session
session_start();