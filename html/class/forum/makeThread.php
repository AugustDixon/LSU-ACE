<?php
/*
	Page Generate Script:	class/forum/makeThread.php
	This script generates the Make Thread page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/forum/index.php
		"Title" text field - 1 to 40 characters
		"Body" text field - 1 to 400 characters
		"Anonymous" check box
		"Make Thread" button - Runs AJAX class/forum/functions/makeThread.php
		
	AJAX Functions:
		makeThread.php
			Inputs
				'ID'
				'Title'
				'Body'
				'Anon'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/forum/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Title Constraint Error
					Show constraints
				Case "4" - Body Constraint Error
					Show constraints
					
	Page Connections:
		class/forum/index.php
*/





?>