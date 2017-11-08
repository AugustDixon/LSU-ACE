<?php
/*
	Page generate script: /profile/edit.php
	This script generates the edit profile page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Back" button - Hyperlinks to /profile/index.php
		"Add Class" button - Hyperlinks to /profile/add.php
		"Change Password" button - Hyperlinks to /profile/password.php
		"First Name" text field
		"Last Name" text field
		"Nickname" text field
		"Phone Number" text field
		"Edit Profile" button - Runs AJAX /profile/functions/editProfile.php
	
	AJAX Functions:
		editProfile.php
			Inputs
				'FirstName'
				'LastName'
				'Nickname'
				'Phone'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to /profile/index.php
				Case "2" - Idle timer expiration
					Redirect to /index.php
				Case "3" - First Name Constraint Error
					Show message and constraints
				Case "4" - Last Name Constraint Error
					Show message and constraints
				Case "5" - Nickname Constraint Error
					Show message and constraints
				Case "6" - Phone Number Constraint Error
					Show message and constraints
	
	Page Connections:
		/profile/index.php
		/profile/password.php
		/profile/add.php
*/









?>