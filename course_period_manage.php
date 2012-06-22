<?php
/**
*	Course Manage Page
*	
*	Serial:		120430
*	by:			M.Karminski
*
*/

//Page number
$PAGE_SWITCH = $COURSE_PERIOD_PAGE_SWITCH = 5;
//Include files
include('settings.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');
include('functions/editable_grid_conf.php');
include('html_head.php');


//TODO: set the default POST value to disable the php notice. 
//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

//Load $COURSE_TABLE_NAME
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);

//Reload $COURSE_TABLE_KEY_NAMES_ARRAY.
$COURSE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($COURSE_TABLE_NAME);

//QUERY the $courseListArray
$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);

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
		table_info_output($COURSE_TABLE_KEY_NAMES_ARRAY, $courseListArray);
		
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();
editable_grid_output();//Editable grid output
//Print javascript blocks.
javascript_include_output();
print_conf_scripts_for_editable_grid($EDITABLE_GRID_UPDATE_PAGE_NAME, $EDITABLE_GRID_LOADDATA_PAGE_NAME, 'course_2010_2011_1');
javascript_window_onload_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>