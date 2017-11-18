<?php
/*
	Page Generate Script: class/bulletin/post.php
	This script generates the Make Post page for the bulletin board.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		"Title" text field - 1 to 30 characters
		"Body" text field - 1 to 200 characters
		"Make Post" button - Runs AJAX class/bulletin/makePost.php
		
	AJAX functions:
		makePost.php
			Inputs
				'ID' - Cid
				'Title'
				'Body'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Title constraint error
					Show constraints
				Case "4" - Body constraint error
					Show constraints
	
	Page Connections:
		class/bulletin/view.php
		class/bulletin/index.php
*/




?>