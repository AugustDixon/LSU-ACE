<?php
/*
	Page Generate Script: class/options.php
	This script generates the student options page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Hide LSUID" check box
		"Hide Name" check box
		"Hide Phone Number" check box
		"Edit Options" button - Runs AJAX class/functions/editOptions.php
		
	AJAX Functions:
		editOptions.php
			Inputs
				'ID'
				'HideID'
				'HideName'
				'HidePhone'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirect to class/index.php
				Case "2" - Idle Timeout
					Redirect to index.php
					
	Page Connections:
		class/index.php
*/




?>