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


//Load the database table name
$TABLE_NAME = $PAGE_TITLE_INFO[$PAGE_SWITCH][2];
//Load the database table key names 
$TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_TITLE_INFO[$PAGE_SWITCH], 1, 4);


//Reunion the semester name 
$_POST["semester"] = $_POST["semesterPartA"]."_".$_POST["semesterPartB"];
//Count the $TABLE_KEY_NAMES_ARRAY
$TABLE_KEY_NAMES_ARRAY_Count0 = array_Counter($TABLE_KEY_NAMES_ARRAY, 1);
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

//------  -[ Control Functions ]-  ------
	
//QUERY the $SEMESTER_LIST_ARRAY for functions
$SEMESTER_LIST_ARRAY = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);

//Load the target array ID
$TARGET_ID = $SEMESTER_LIST_ARRAY[$_POST["semesterList"]][6];

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
if($_POST["semesterListChange"]){
	tableData_Change($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $TARGET_ID, $SEMESTER_INFO_CHANGE_ARRAY);
}

//REQUERY the $SEMESTER_LIST_ARRAY for display
$SEMESTER_LIST_ARRAY = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);

//Count the $SEMESTER_LIST_ARRAY
$SEMESTER_LIST_ARRAY_Count0 = array_Counter($SEMESTER_LIST_ARRAY, 1);


//------  -[ Views Functions ]-  ------

//Print Main Title
mainTitle_Output($PAGE_SWITCH, $PAGE_TITLE_INFO);

//Print main form
divHead_Output_WithClassOption("form");
	
	//Print semesterList Block
	divHead_Output_WithClassOption("mainMiddleBlockLeft");
	semesterList_Output($PAGE_SWITCH, $PAGE_TITLE_INFO, $SEMESTER_LIST_ARRAY_Count0, $_POST["semesterList"], $SEMESTER_LIST_ARRAY);
	divEnd_Output();

	//Print semesterInfo Block
	divHead_Output_WithClassOption("mainMiddleBlockRight");
	if(!$_POST["semesterInfoChange"]){
		semesterInfo_Output();
	}elseif($_POST["semesterInfoChange"]){
		semesterInfo_change_Output();
	}

	divEnd_Output();

divEnd_Output();

//Fin.
//unset($SEMESTER_LIST_ARRAY, $SEMESTER_LIST_ARRAY_Count0, $SEMESTER_INFO_ARRAY, $TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);	//Destory the vars
?>


