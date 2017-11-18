<?php
/*
	Page generate script: /profile/add.php
	This script generates the Add Class page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Back" button - Hyperlinks to /profile/edit.php
		"Department" text Field - 1 to 5 characters
		"Number" text field - 1 to 5 characters
		"Section" text field - 1 to 3 characters
		"Monday", "Tuesday", etc. check boxes
		"Start Hour A" and "Start Minute A" text fields - 1 to 2 characters each.
		"End Hour A" and "End Minute A" text fields - 1 to 2 characters each.
		"Monday", "Tuesday", etc. check boxes
		"Start Hour B" and "Start Minute B" text fields - 1 to 2 characters, 2 characters
		"End Hour B" and "End Minute B" text fields - 1 to 2 characters, 2 characters
		"Hide Name", "Hide Phone", and "Hide LSUID" check boxes
		"Add Class" button - runs AJAX /profile/functions/addClass.php
	
	AJAX Functions:
		addClass.php
			Inputs
				'Dept'
				'Num'
				'Sect'
				'DayA' - Must be built
				'STimeA' - Must be built
				'ETimeA' - Must be built
				'DayB' - Must be built
				'STimeB' - Must be built
				'ETimeB' - Must be built
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
				Case "12" - Student Already In Class
					Show message
	
	Page Connections:
		/profile/edit.php
		/profile/index.php
		
	Extra Information:
		Building DayA and DayB:
			DayA and DayB are 5 character strings built from the five weekday checkboxes for each. Each day corresponds to a character in
			the string; Monday is character 0, Tuesday is 2, etc. A checked box will fill that place with the first letter of the name
			of the day uppercase and an unchecked box will be a space. E.g. "M W F", " T T ", "M W  ".
			
		Building STimeA, STimeB, ETimeA, and ETimeB:
			The first part of these strings will be the value of the Hour text field. A colon will be concatenated on to that. Then the Minute
			value will be concatenated onto that. Finally, lowercase "am" will be concatenated if the Hour field is within 7-11 and "pm" will
			be concatenated otherwise. E.g. "1:15pm", "7:30am", "12:00pm".
*/




?>