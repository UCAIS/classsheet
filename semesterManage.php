<?php
/**
*	Semester Manage page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ Environment initialization ]-  ------

//Page number
static $PAGE_SWITCH = 1;

//Include files
include('settings.php');
include('htmlHead.php');
include('functions/databaseConnectionFunctions.php');
include('functions/databaseQueryFunctions.php');
include('functions/globalFunctions.php');
include('functions/viewsOutputFunctions.php');
//Load the file name for post
$FILE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['FILE_NAME'];
//Load the database table name
$TABLE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['TABLE_NAME'];
//Load this page database table key names array
$THIS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];
//Load this page database table key types array
$THIS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];

//Load the database table key names 
//$TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_INFO_ARRAY[$PAGE_SWITCH], 1, 4);
//Count the $TABLE_KEY_NAMES_ARRAY
//$TABLE_KEY_NAMES_ARRAY_Count0 = array_Counter($TABLE_KEY_NAMES_ARRAY, 1);

//------  -[ Control Functions ]-  ------
	
//QUERY the $semesterListArray
$semesterListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);
//Load the target array number
$targetArray = $_POST['semesterList'];
//Load the target array ID number
$targetId = $semesterListArray[$targetArray]['ID'];
//Reunion the semester name 
$_POST[$THIS_TABLE_KEY_NAMES_ARRAY['SEMESTER']] = $_POST["semesterPartA"]."_".$_POST["semesterPartB"];

//Load POST data in $SEMESTER_INFO_CHANGE_ARRAY
//if($_POST["semesterInfoChange"]){
//	for($i=0;$i<$TABLE_KEY_NAMES_ARRAY_Count0;$i++){
//		$SEMESTER_INFO_CHANGE_ARRAY[$i] = "'".$_POST["$TABLE_KEY_NAMES_ARRAY[$i]"]."'";
//	}
//}
//CREATE the TABLE if query result not avaliable 
if(!$semesterListArray){
	database_table_create($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $THIS_TABLE_KEY_TYPES_ARRAY);
}
//ADD the semester information to database if POST 
if($_POST["semesterInfoAdd"]){
	//Load the POST info array
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$semesterInfoArray[$value] = "'".$_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	unset($value);
	table_data_add($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $semesterInfoArray);
}
//DELETE the semester information to database if POST
//if($_POST["semesterListDelete"]){
//	tableData_Delete_ByID($TABLE_NAME, $targetId);
//}
//CHANGE the semester information to database if POST 
//if($_POST["semesterInfoChange"]){
//	tableData_Change($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $targetId, $SEMESTER_INFO_CHANGE_ARRAY);
//}

//REQUERY the $semesterListArray for display
//$semesterListArray = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
//Count the $semesterListArray
//$semesterListArrayCount0 = array_Counter($semesterListArray, 1);
//------  -[ Views Functions ]-  ------

//Print Main Title
main_title_output($PAGE_SWITCH, $PAGE_INFO_ARRAY);

//Print main form
div_head_output_with_class_option("form");
	//Print form Block
	form_head_output($FILE_NAME, "post");	
	//Print semesterList Block
	div_head_output_with_class_option("mainMiddleBlockLeft");
	semester_list_output($PAGE_SWITCH, $targetArray, $semesterListArray, $THIS_TABLE_KEY_NAMES_ARRAY);
	div_end_output();
	
	//Print semesterInfo Block
	div_head_output_with_class_option("mainMiddleBlockRight");
	if(!$_POST["semesterListChange"]){
		semester_info_output($THIS_TABLE_KEY_NAMES_ARRAY);
	}elseif($_POST["semesterListChange"]){
		semester_info_change_output($THIS_TABLE_KEY_NAMES_ARRAY, $semesterListArray, $targetArray);
	}
	div_end_output();
	
	form_end_output();
div_end_output();

//Fin.
?>


