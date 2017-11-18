<?php
/*
	Page Generate Script: class/info/TAedit.php
	This script generates the Add/Edit TA page.
	
	HTTP Inputs:
		'ID'
		'Name'
	
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" Text field - 1 to 30 characters. Must be unique.
		"Email" Text field - 0 to 30 characters
		"Add/Edit TA" button - Runs AJAX class/info/functions/editTA.php
		"Delete TA" button - Runs AJAX class/info/functions/removeTA.php. Only shows if 'Name' input is not null
		
	AJAX Functions:
		editTA.php
			Inputs
				'ID'
				'Name'
				'Email'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name constraint error
					Show Constraint
				Case "4" - Email Constraint error
					Show Constraint
		removeTA.php
			Inputs
				'ID'
				'Name' - Name from HTTP input, not text field
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
*/





?>