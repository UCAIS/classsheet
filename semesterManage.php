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

//Load POST data in $SEMESTER_INFO_ARRAY
$SEMESTER_INFO_ARRAY[0] = $_POST["semesterPartA"]."_".$_POST["semesterPartB"];
$SEMESTER_INFO_ARRAY[1] = $_POST["part"];
$SEMESTER_INFO_ARRAY[2] = $_POST["weekCount"];
$SEMESTER_INFO_ARRAY[3] = $_POST["startYear"];
$SEMESTER_INFO_ARRAY[4] = $_POST["startMonth"];
$SEMESTER_INFO_ARRAY[5] = $_POST["startDay"];

//Load the database table name
$TABLE_NAME = $PAGE_TITLE_INFO[$PAGE_SWITCH][2];

//Load the database table key names 
$TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_TITLE_INFO[$PAGE_SWITCH], 1, 4);


//------  -[ Control Functions ]-  ------
	
//QUERY the $SEMESTER_LIST_ARRAY for functions
$SEMESTER_LIST_ARRAY = semesterArrayList_Query();

//CREATE the TABLE if query result not avaliable 
if(!$SEMESTER_LIST_ARRAY){
	databaseTable_Create($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
}

//ADD the semester information from DB if post avaliable
if($_POST["semesterInfoAdd"]){
	tableData_Add($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $SEMESTER_INFO_ARRAY);
}

//DELETE the semester information from DB if post avaliable
if($_POST["semesterListDelete"]){
	tableData_Delete_ByID($TABLE_NAME, $SEMESTER_LIST_ARRAY[$_POST["semesterList"]][6]);
}

//CHANGE the semester information from DB if post avaliable
if($_POST[""]){

}

//REQUERY the $SEMESTER_LIST_ARRAY for display
$SEMESTER_LIST_ARRAY = semesterArrayList_Query();

//Count the $SEMESTER_LIST_ARRAY
$SEMESTER_LIST_ARRAYCount0 = array_Counter($SEMESTER_LIST_ARRAY, 1);


//------  -[ Views Functions ]-  ------

//Print Main Title
mainTitle_Output($PAGE_SWITCH, $PAGE_TITLE_INFO);

//Print main form
divHead_Output_WithClassOption("form");
	
	//Print semesterList Block
	divHead_Output_WithClassOption("mainMiddleBlockLeft");
	semesterList_Output($PAGE_SWITCH, $PAGE_TITLE_INFO, $SEMESTER_LIST_ARRAYCount0, $_POST["semesterList"], $SEMESTER_LIST_ARRAY);
	divEnd_Output();

	//Print semesterInfo Block
	divHead_Output_WithClassOption("mainMiddleBlockRight");
	semesterInfo_Output();
	divEnd_Output();

divEnd_Output();

//Fin.
unset($SEMESTER_LIST_ARRAY, $SEMESTER_LIST_ARRAYCount0, $SEMESTER_INFO_ARRAY);	//Destory the vars
?>


