<?php
/**
*	Class Room Schedule Manage Page
*	
*	Serial:		120512
*	by:			M.Karminski
*
*/


//Page switch
$PAGE_SWITCH = $CLASSROOM_SCHEDULE_PAGE_SWITCH = 10;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

/**
*	@todo:	Classroom Schedule Manage
*			
*			Classroom assign method
*				The 'Simple Classroom[Si]' access all [TI], [T0] title course, and as the first choice.
*				Other speical classroom only access original course, and as the second choice.
*			Teachers assign method
*				Teachers arranged by data list. One teacher only take one course in same time.
*				Create the 'TEACH_FREQUENCY' table to mark the teacher's take course frequency.
*				The teacher's take course number base on average value of 'TEACH_FREQUENCY'.
*/

//------  -[ Views Functions ]-  ------

div_head_output_with_class_option("mainMiddle");
	//Print Main Title 
	main_title_output($PAGE_INFO_ARRAY, $PAGE_SWITCH);
	//Print main form
	div_head_output_with_class_option("form");
		//Print form Block
		form_head_output($FILE_NAME, "post");	
		//Print semesterList Block
		div_head_output_with_class_option("mainMiddleBlockLeft");
		semester_list_output($PAGE_SWITCH, $semesterListArray, $SEMESTER_TABLE_KEY_NAMES_ARRAY, $semesterTargetArray);
		div_end_output();
		div_head_output_with_class_option("mainMiddleBlockRight");
	
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>