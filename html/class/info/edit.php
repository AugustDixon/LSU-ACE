<?php
/*
	Page Generate Script: class/info/edit.php
	This script generates the Edit Class page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Class Title" text field - 0 to 30 characters
		"Classroom" text field - 0 to 20 characters
		"Submit Change Request" button - Runs AJAX class/info/functions/editClassInfo.php
		
	AJAX Functions:
		editClassInfo.php
			Inputs:
				'ID'
				'Title'
				'Classroom'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Title Constraint Error
					Show Constraints
				Case "4" - Classroom Constraint Error
					Show Constraints
	
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
*/





?>