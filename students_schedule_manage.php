<?php
/**
*	Students Schedule Manage Page
*	
*	Serial:		120528
*	by:			M.Karminski
*
*	This page loop the class and pickup the course of class to form a sheet.
*
*
*/

//Page switch
$PAGE_SWITCH = $STUDENTS_SCHEDULE_PAGE_SWITCH = 12;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//TODO: SEMESTER_WEEK method is not avaliable.

//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

//Load $classListArray
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($CLASS_TABLE_NAME);
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);
//Load $totalScheduleArray
$TOTAL_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TOTAL_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($TOTAL_SCHEDULE_TABLE_NAME);
$totalScheduleArray = table_data_query($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");
//Load $classroomScheduleArray
$CLASSROOM_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASSROOM_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$CLASSROOM_SCHEDULE_PAGE_SWITCH];
$classroomScheduleArray = table_data_query($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");

//Load $studentsScheduleArray
$STUDENTS_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $STUDENTS_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$STUDENTS_SCHEDULE_PAGE_SWITCH];
//$studentsScheduleArray = table_data_query($STUDENTS_TABLE_NAME, $STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY);

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

		week_select_output($STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $SEMESTER_WEEK_SET);//Temporary views output
		table_info_output($STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $studentsScheduleArray);//Temporary views output
		reschedule_button_output();

		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();



//Fin.
?>