<?php
/**
*	Course Manage Page
*	
*	Serial:		120416
*	by:			M.Karminski
*
*/

//Page number
$PAGE_SWITCH = 5;
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//TODO: set the default POST value to disable the php notice. 
//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];

//Fin.
?>