<?php
/*
	Page Generate Script:	class/forum/post.php
	This script generates the Make Post page.
	
	HTTP Inputs:
		'ID'
		'Tid' - Thread ID
		
	Page Features:
		"Back" button - Hyperlinks to class/forum/view.php
		"Body" text field - 1 to 400 characters
		"Anonymous" check box
		"Make Post" button - Runs AJAX class/forum/functions/makePost.php
		
	AJAX Functions:
		makePost.php
			Inputs
				'ID'
				'Tid'
				'Body'
				'Anon'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/forum/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Body Constraint Error
					Show constraints
					
	Page Connections:
		class/forum/view.php
*/





?>