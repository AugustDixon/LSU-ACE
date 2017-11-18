<?php
/*
	Page Generate Script: profile/password.php
	This script generates the change password page.
	
	HTTP Inputs:
		none
		
	Page Features:
		"Back" button - Hyperlinks to profile/edit.php
		"Current Password" text field
		"New Password" text field - 5 to 20 characters. Must not match Current Password
		"Confirm Password" text field - Must match New Password.
		"Change Password" button - Runs AJAX profile/functions/changePassword.php
		
	AJAX Functions:
		changePassword.php
			Inputs
				'password'
				'newpass'
				'cpass'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirects to profile/edit.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Incorrect Password
					Show message and clear Current Password field
				Case "4" - New Password Constraint Error
					Show message and clear Current Password field
				Case "5" - New Password is the same
					Show message and clear Current Password field
				Case "6" - Passwords do not match
					Show message and clear Current Password field
					
	Page Connections:
		profile/edit.php
*/





?>