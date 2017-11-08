<?php
/*
	Page generate script: /profile/index.php
	This script generates the profile page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Logout" button - Runs AJAX /profile/functions/logout.php
		Displays from database:
			LSUID
			First Name
			Last Name
			Nickname
			Phone Number
		"Edit Profile" button - Hyperlinks to /profile/edit.php
		Displays from database for each real class taken by the student:
			Department
			Number
			"Class Page" button - Hyperlinks to /class/index.php
	
	AJAX Functions:
		logout.php
			Inputs
				none
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to /index.php
	
	Page Connections:
		/index.php
		/profile/edit.php
		/class/index.php
*/



?>