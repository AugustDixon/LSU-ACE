<?php
/*
	Page Generate Script: class/info/instructorEdit.php
	This script generates the Edit Instructor page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" text field - 0 to 30 characters
		"Email" text field - 0 to 30 characters
		"Office" text field - 0 to 20 characters
		"Office Hours" text field - 0 to 50 characters
		"Submit Change Request" button - Runs class/info/functions/editInstructor.php
		
	AJAX Functions:
		editInstructor.php
			Inputs:
				'ID'
				'Name'
				'Email'
				'Office'
				'Hours'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name Constraint Error
					Show Constraints
				Case "4" - Email Constraint Error
					Show Constraints
				Case "5" - Office  Constraint Error
					Show Constraints
				Case "6" - Hours  Constraint Error
					Show Constraints
					
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
*/






?>