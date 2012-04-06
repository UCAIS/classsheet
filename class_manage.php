<?php
/**
*	Class Manage Page
*	
*	Serial:		120328
*	by:			M.Karminski
*
*/


//------  -[ Environment initialization ]-  ------

//Page number
$PAGE_SWITCH = 2;
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;

//Include files
include('settings.php');
include('htmlHead.php');
include('functions/databaseConnectionFunctions.php');
include('functions/databaseQueryFunctions.php');
include('functions/globalFunctions.php');
include('functions/viewsOutputFunctions.php');

//Load the page name
$PAGE_NAME = $PAGE_TITLE_INFO[$PAGE_SWITCH][0].".php";
//Load the database table name
$SEMESTER_TABLE_NAME = $PAGE_TITLE_INFO[$SEMESTER_PAGE_SWITCH][2];
//Load the database table key names 
$TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_TITLE_INFO[$PAGE_SWITCH], 1, 4);
$SEMESTER_TABLE_KEY_NAMES_ARRAY = array_Partitioner($PAGE_TITLE_INFO[$SEMESTER_PAGE_SWITCH], 1, 4);
//Count the $TABLE_KEY_NAMES_ARRAY
$TABLE_KEY_NAMES_ARRAY_Count0 = array_Counter($TABLE_KEY_NAMES_ARRAY, 1);

//------  -[ Control Functions ]-  ------

//Load the target array number
$SEMESTER_TARGET_ARRAY = $_POST["semesterList"];
$TARGET_ARRAY = $_POST["classList"];
//QUERY the list array
$SEMESTER_LIST_ARRAY = tableData_Query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
	//Reunion the $TABLE_KEY_NAMES_ARRAY
	$WEEKS_COUNTER = $SEMESTER_LIST_ARRAY[$SEMESTER_TARGET_ARRAY][2];
	for($i=0;$i<$WEEKS_COUNTER;$i++){
		$TABLE_KEY_NAMES_ARRAY_SERIAL = $TABLE_KEY_NAMES_ARRAY_Count0 + $i;
		$TABLE_KEY_NAMES_ARRAY[$TABLE_KEY_NAMES_ARRAY_SERIAL] = "week".$i;
	}
	//Reunion the $TABLE_NAME
	$SEMESTER = $SEMESTER_LIST_ARRAY[$SEMESTER_TARGET_ARRAY][0]."_".$SEMESTER_LIST_ARRAY[$SEMESTER_TARGET_ARRAY][1];
	$TABLE_NAME = $PAGE_TITLE_INFO[$PAGE_SWITCH][2].$SEMESTER; 	
$CLASS_LIST_ARRAY = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);

//Load the target array ID number
//$TARGET_ID = $CLASS_LIST_ARRAY[$TARGET_ARRAY][6];

//Load POST data in $SCLASS_INFO_ARRAY
if($_POST["classInfoAdd"]){
	for($i=0;$i<$TABLE_KEY_NAMES_ARRAY_Count0;$i++){
		$SCLASS_INFO_ARRAY[$i] = "'".$_POST["$TABLE_KEY_NAMES_ARRAY[$i]"]."'";
	}
}
//Load POST data in $CLASS_INFO_CHANGE_ARRAY
if($_POST["classInfoChange"]){
	for($i=0;$i<$TABLE_KEY_NAMES_ARRAY_Count0;$i++){
		$CLASS_INFO_CHANGE_ARRAY[$i] = "'".$_POST["$TABLE_KEY_NAMES_ARRAY[$i]"]."'";
	}
}
//CREATE the TABLE if query result not avaliable 
if($_POST["semesterListSelected"]){
	databaseTable_Create($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
}
//ADD the class information from DB if POST 
if($_POST["classInfoAdd"]){
	tableData_Add($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $CLASS_INFO_ARRAY);
}
//DELETE the class information from DB if POST
if($_POST["classListDelete"]){
	tableData_Delete_ByID($TABLE_NAME, $TARGET_ID);
}
//CHANGE the class information from DB if POST 
if($_POST["classInfoChange"]){
	tableData_Change($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY, $TARGET_ID, $CLASS_INFO_CHANGE_ARRAY);
}

//REQUERY the list array for display
$CLASS_LIST_ARRAY = tableData_Query($TABLE_NAME, $TABLE_KEY_NAMES_ARRAY);
$SEMESTER_LIST_ARRAY = tableData_Query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY); 	
//Count the list array
$CLASS_LIST_ARRAY_Count0 = array_Counter($CLASS_LIST_ARRAY, 1);
$SEMESTER_LIST_ARRAY_Count0 = array_Counter($SEMESTER_LIST_ARRAY, 1);

//------  -[ Views Functions ]-  ------

//Print Main Title
mainTitle_Output($PAGE_SWITCH, $PAGE_TITLE_INFO);

//Print main form
divHead_Output_WithClassOption("form");
	//Print form Block
	formHead_Output($PAGE_NAME, "post");	
	//Print semesterList Block
	divHead_Output_WithClassOption("mainMiddleBlockLeft");
	semesterList_Output($PAGE_SWITCH, $SEMESTER_TARGET_ARRAY, $SEMESTER_LIST_ARRAY, $SEMESTER_LIST_ARRAY_Count0);
	divEnd_Output();
	//Print semesterInfo Block
	divHead_Output_WithClassOption("mainMiddleBlockRight");
	if($_POST"semesterListSelected"){
		
	}
	if(!$_POST["semesterListChange"]){
		//semesterInfo_Output();
	}elseif($_POST["semesterListChange"]){
		//semesterInfo_change_Output($SEMESTER_LIST_ARRAY, $TARGET_ARRAY);
	}
	divEnd_Output();
	formEnd_Output();
divEnd_Output();

//Fin.

?>