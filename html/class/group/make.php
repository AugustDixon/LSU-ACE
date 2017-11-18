<?php
/*
	Page Generate Script:	class/group/make.php
	This script generates the Form Group page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/group/index.php
		"Group Name" text box - 1 to 30 characters
		"Max Group Members" slider/whatever
		"Looking for Members" check box
		"Open Group" check box
		"Form Group" button - Runs AJAX class/group/functions/makeGroup.php
		
	AJAX Functions:
		makeGroup.php
			Inputs
				'ID'
				'Name'
				'Max'
				'Looking'
				'Open'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirect to class/group/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Group Name Constraint Error
					Show constraintts
				Case "4" - User Already Leads Group
					Show message
					
	Page Connections:
		class/group/index.php
		class/group/view.php
*/





?>