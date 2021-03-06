<?php
/**
*	Classroom Manage Page
*	
*	Serial:		120511
*	by:			M.Karminski
*
*/

//Page switch
$PAGE_SWITCH = $CLASSROOM_PAGE_SWITCH = 8;

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
//Load target classroom
$classroomTargetArray = $_POST['classroomList'];

//QUERY the $classroomListArray
$CLASSROOM_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASSROOM_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASSROOM_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$CLASSROOM_PAGE_SWITCH];
$CLASSROOM_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$CLASSROOM_PAGE_SWITCH];
$classroomListArray = table_data_query($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY);

//Load the target array ID number
$targetId = $classroomListArray[$classroomTargetArray][$CLASSROOM_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY, $CLASSROOM_TABLE_KEY_TYPES_ARRAY);
}

//ADD the information to database if POST
print $_POST['CLASSROOM_NAME']; 
if($_POST["classroomInfoAdd"]){
	//Load the POST info array
	foreach($CLASSROOM_TABLE_KEY_NAMES_ARRAY as $value){
		$classroomInfoArray[$value] = $_POST[$value];
	}
	unset($value);
	table_data_add($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY, $classroomInfoArray);
}
//DELETE the information from database if POST
if($_POST["classroomListDelete"]){
	table_data_delete_by_id($CLASSROOM_TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["classroomInfoChanged"]){
	foreach($CLASSROOM_TABLE_KEY_NAMES_ARRAY as $value){
		$classroomInfoChangeArray[$value] = $_POST[$CLASSROOM_TABLE_KEY_NAMES_ARRAY[$value]];
	}
	unset($value);
	table_data_change($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY, $targetId, $classroomInfoChangeArray);
}

//REQUERY the $classroomListArray for display
$classroomListArray = table_data_query($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY);


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
		classroom_list_output($classroomListArray, $classroomTargetArray);
		if(!$_POST['classroomListChange']){
			classroom_info_output($CLASSROOM_TABLE_KEY_NAMES_ARRAY);
		}else{
			classroom_info_change_output($classroomListArray, $CLASSROOM_TABLE_KEY_NAMES_ARRAY, $classroomTargetArray);
		}
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>