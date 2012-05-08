<?php
/**
*	Class and Week Course Schedule Import Page
*	
*	Serial:		120429
*	by:			M.Karminski
*
*/

//TODO:ADD database table drop method.[IMPORTANT]
//Page number
$PAGE_SWITCH = 13;
$CLASS_TABLE_NAME;
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

//QUERY the $courseListArray
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$COURSE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($COURSE_TABLE_NAME);
$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);

//Load $CLASS_TABLE_NAME
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);

//Load import data 
if($_POST['upload']){
	$classImportInfoArray = uploaded_file_data_load();
	$classInsertInfoArray = import_data_form($classImportInfoArray);
	//ADD the COURSE data
	$CLASS_TABLE_KEY_NAMES_ARRAY = array_picker($classImportInfoArray, 0);//Pick the table key names from import array.
	$CLASS_TABLE_KEY_NAMES_ARRAY = array_key_insert($CLASS_TABLE_KEY_NAMES_ARRAY, "ID", "ID");//The imporrt data not include ID key, so add it.
	$CLASS_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($CLASS_TABLE_KEY_TYPES_ARRAY, $CLASS_TABLE_KEY_NAMES_ARRAY, 0, "varchar(15)", 1);
	$CLASS_TABLE_KEY_TYPES_ARRAY = array_key_insert($CLASS_TABLE_KEY_TYPES_ARRAY, "ID", "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)");
		//COURSE_LEFT TABLE structure create
		$courseListArrayCount0 = count($courseListArray);
		for($i=0;$i<$courseListArrayCount0;$i++){
			$CLASS_TABLE_KEY_NAMES_ARRAY = array_key_insert($CLASS_TABLE_KEY_NAMES_ARRAY, $courseListArray[$i]['COURSE_KEY_NAME'], $courseListArray[$i]['COURSE_KEY_NAME']);
			$CLASS_TABLE_KEY_TYPES_ARRAY = array_key_insert($CLASS_TABLE_KEY_TYPES_ARRAY, $courseListArray[$i]['COURSE_KEY_NAME'], "varchar(15)");
		}
	database_table_create($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $CLASS_TABLE_KEY_TYPES_ARRAY);
	$tableKeyNumbersCount = count($classInsertInfoArray);	
	for($i=0;$i<$tableKeyNumbersCount;$i++){
			//COURSE_LEFT auto fill method
			$courseListArrayCount0= count($courseListArray);
			$classType = $classInsertInfoArray[$i]['CLASS_TYPE'];
			for($j=0;$j<$courseListArrayCount0;$j++){
				$classInsertInfoArray[$i][$courseListArray[$j]['COURSE_KEY_NAME']] = $courseListArray[$j][$classType];
			}
		table_data_add($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $classInsertInfoArray[$i]);
	}
}

//Reload $CLASS_TABLE_KEY_NAMES_ARRAY.
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($CLASS_TABLE_NAME);

//QUERY the $courseListArray
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);


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
		table_info_output($classListArray, $CLASS_TABLE_KEY_NAMES_ARRAY);
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