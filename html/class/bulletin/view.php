<?php
/*
	Page Generate Script: class/bulletin/view.php
	This script generates a page which views bulletin posts. Query posts will have two buttons that allow you to
	respond yes or no to the query.
	
	HTTP Inputs:
		'ID' - Cid of the class
		'Pid' - Post ID
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		Title
		Body
		"Yes" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"No" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"Delete Post" button - Runs AJAX class/bulletin/functions/deletePost.php. Appears only if the user is the author of the post.
		
	AJAX Functions:
		altRespond.php
			Inputs:
				'Answer' - boolean
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
		deletePost.php
			Inputs:
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections
		class/bulletin/index.php
*/





?>