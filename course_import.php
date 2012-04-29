<?php
/**
*	Course and Course Type Import Page
*	
*	Serial:		120424
*	by:			M.Karminski
*
*/

//TODO: add database_table_create function.
//Page number
$PAGE_SWITCH = 12;
$SEMESTER_PAGE_SWITCH = 1;
$COURSE_TYPE_PAGE_SWITCH = 2;
$COURSE_PAGE_SWITCH = 3;

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

//Load $COURSE_TABLE_NAME
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);

//Load import data 
if($_POST['upload']){
	$courseImportInfoArray = uploaded_file_data_load();
	$courseInsertInfoArray = import_data_form($courseImportInfoArray);
	//ADD the COURSE data
	$COURSE_TABLE_KEY_NAMES_ARRAY = array_picker($courseImportInfoArray, 0);//Pick the table key names from import array.
	$COURSE_TABLE_KEY_NAMES_ARRAY = array_add_key($COURSE_TABLE_KEY_NAMES_ARRAY, "ID", "ID");//The imporrt data not include ID key, so add it.
	$COURSE_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($COURSE_TABLE_KEY_TYPES_ARRAY, $COURSE_TABLE_KEY_NAMES_ARRAY, 0, "varchar(15)", 1);
	$COURSE_TABLE_KEY_TYPES_ARRAY = array_add_key($COURSE_TABLE_KEY_TYPES_ARRAY, "ID", "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)");
	database_table_create($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY, $COURSE_TABLE_KEY_TYPES_ARRAY);
	$tableKeyNumbersCount = count($courseInsertInfoArray);
	for($i=0;$i<$tableKeyNumbersCount;$i++){
		table_data_add($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY, $courseInsertInfoArray[$i]);
	}
}

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
		course_info_table_output($courseListArray, $COURSE_TABLE_KEY_NAMES_ARRAY);
		files_upload_output();
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>