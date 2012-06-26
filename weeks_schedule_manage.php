<?php
/**
*	Weekes Schedule Manage Page
*	
*	Serial:		120430
*	by:			M.Karminski
*
*/
//Start session
session_start();

//Page number
$PAGE_SWITCH = $WEEKS_SCHEDULE_PAGE_SWITCH = 6;
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

//QUERY the $classlistArray
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($CLASS_TABLE_NAME);
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);

//Load in session for global table name load.
$_SESSION['targetTableName'] = $CLASS_TABLE_NAME;
$_SESSION['targetPageSwitch'] = $PAGE_SWITCH;

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
		table_info_output($CLASS_TABLE_KEY_NAMES_ARRAY, $classListArray);
		editable_grid_output();//Editable grid output
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print javascript blocks.
javascript_include_output();
print_conf_scripts_for_editable_grid($EDITABLE_GRID_UPDATE_PAGE_NAME, $EDITABLE_GRID_LOADDATA_PAGE_NAME, $CLASS_TABLE_NAME);
javascript_window_onload_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>