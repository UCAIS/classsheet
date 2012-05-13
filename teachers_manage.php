<?php
/**
*	Teachers Manage Page
*	
*	Serial:		120512
*	by:			M.Karminski
*
*/


//Page switch
$PAGE_SWITCH = $CLASSROOM_PAGE_SWITCH = 9;

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
* @todo:	Teachers Manage
*			$TEACHERS_TABLE_KEY_NAMES_ARRAY
*				['ID']
*				['TEACHER_NAME']
*				['TEACHER_TYPE']	The teacher type data access from course type, and storage in course key names format.
*				['TEACH_FREQUENCE']	Default value is 0.
*
*			$TEACHERS_TABLE_KEY_TYPES_ARRAY
*
*			Database structure
*			| ID | TEACHER_NAME | TEACHER_TYPE | TEACH_FREQUENCY |
*
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