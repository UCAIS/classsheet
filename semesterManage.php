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
$PAGE_SWITCH = 1;

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
//Load the database table key names 
//$TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_INFO_ARRAY[$PAGE_SWITCH], 1, 4);
//Count the $TABLE_KEY_NAMES_ARRAY
//$TABLE_KEY_NAMES_ARRAY_Count0 = array_Counter($TABLE_KEY_NAMES_ARRAY, 1);

//------  -[ Control Functions ]-  ------
	
//QUERY the $SEMESTER_LIST_ARRAY
$SEMESTER_LIST_ARRAY = table_data_query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH]);
/*
//Load the target array number
$TARGET_ARRAY = $_POST["semesterList"];
//Load the target array ID number
$TARGET_ID = $SEMESTER_LIST_ARRAY[$TARGET_ARRAY][6];
//Reunion the semester name 
$_POST[$PAGE_INFO_ARRAY[$PAGE_SWITCH][4]] = $_POST["semesterPartA"]."_".$_POST["semesterPartB"];
//Load POST data in $SEMESTER_INFO_ARRAY
if($_POST["semesterInfoAdd"]){
	for($i=0;$i<$TABLE_KEY_NAMES_ARRAY_Count0;$i++){
		$SEMESTER_INFO_ARRAY[$i] = "'".$_POST["$TABLE_KEY_NAMES_ARRAY[$i]"]."'";
	}
}
//Load POST data in $SEMESTER_INFO_CHANGE_ARRAY
if($_POST["semesterInfoChange"]){
	for($i=0;$i<$TABLE_KEY_NAMES_ARRAY_Count0;$i++){
		$SEMESTER_INFO_CHANGE_ARRAY[$i] = "'".$_POST["$TABLE_KEY_NAMES_ARRAY[$i]"]."'";
	}
}
//CREATE the TABLE if query result not avaliable 
if(!$SEMESTER_LIST_ARRAY){
	databaseTable_Create($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
}
//ADD the semester information from DB if POST 
if($_POST["semesterInfoAdd"]){
	tableData_Add($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $SEMESTER_INFO_ARRAY);
}
//DELETE the semester information from DB if POST
if($_POST["semesterListDelete"]){
	tableData_Delete_ByID($TABLE_NAME, $TARGET_ID);
}
//CHANGE the semester information from DB if POST 
if($_POST["semesterInfoChange"]){
	tableData_Change($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $TARGET_ID, $SEMESTER_INFO_CHANGE_ARRAY);
}

//REQUERY the $SEMESTER_LIST_ARRAY for display
$SEMESTER_LIST_ARRAY = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
//Count the $SEMESTER_LIST_ARRAY
*/
$SEMESTER_LIST_ARRAY_Count0 = array_Counter($SEMESTER_LIST_ARRAY, 1);
//------  -[ Views Functions ]-  ------

//Print Main Title
main_title_output($PAGE_SWITCH, $PAGE_INFO_ARRAY);

//Print main form
div_head_output_with_class_option("form");
	//Print form Block
	form_head_output($FILE_NAME, "post");	
	//Print semesterList Block
	div_head_output_with_class_option("mainMiddleBlockLeft");
	semesterList_Output($PAGE_SWITCH, $TARGET_ARRAY, $SEMESTER_LIST_ARRAY, $SEMESTER_LIST_ARRAY_Count0);
	div_end_output();
	/*
	//Print semesterInfo Block
	div_head_output_with_class_option("mainMiddleBlockRight");
	if(!$_POST["semesterListChange"]){
		semesterInfo_Output($TABLE_KEY_NAMES_ARRAY);
	}elseif($_POST["semesterListChange"]){
		semesterInfo_change_Output($TABLE_KEY_NAMES_ARRAY, $SEMESTER_LIST_ARRAY, $TARGET_ARRAY);
	}
	divEnd_Output();
	*/
	form_end_output();
div_end_output();

//Fin.
?>


