<?php
/*
	Page generate script: /profile/add.php
	This script generates the Add Class page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Back" button - Hyperlinks to /profile/edit.php
		"Department" text Field
		"Number" text field
		"Section" text field
		"Monday", "Tuesday", etc. check boxes
		"Start Time A" text field
		"End Time A" text field
		"Monday", "Tuesday", etc. check boxes
		"Start Time B" text field
		"End Time B" text field
		"Hide Name", "Hide Phone", and "Hide LSUID" check boxes
		"Add Class" button - runs AJAX /profile/functions/addClass.php
	
	AJAX Functions:
		addClass.php
			Inputs
				'Dept'
				'Num'
				'Sect'
				'DayA'
				'STimeA'
				'ETimeA'
				'DayB'
				'STimeB'
				'ETimeB'
				'HName'
				'HPhone'
				'HLSUID'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to /profile/index.php
				Case "2" - Idle timer expiration
					Redirects to /index.php
				Case "3" - Department Constraint Error
					Show message and constraints
				Case "4" - Number Constraint Error
					Show message and constraints
				Case "5" - Section Constraint Error
					Show message and constraints
				Case "6" - DayA Constraint Error
					Show message and constraints
				Case "7" - Start Time A Constraint Error
					Show message and constraints
				Case "8" - End Time A Constraint Error
					Show message and constraints
				Case "9" - DayB Constraint Error
					Show message and constraints
				Case "10" - Start Time B Constraint Error
					Show message and constraints
				Case "11" - End Time B Constraint Error
					Show message and constraints
	
	Page Connections:
		/profile/edit.php
		/profile/index.php
*/




?>