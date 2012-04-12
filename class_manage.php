<?php
/**
*	Class Manage Page
*	
*	Serial:		120328
*	by:			M.Karminski
*
*/


//Page number
$PAGE_SWITCH = 2;
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;

//Include files
include('settings.php');
include('html_head.php');
include('functions/database_connection_functions.php');
include('functions/database_query_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//TODO: set the default POST value to disable the php notice. 

//Load the file name for post
$FILE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['FILE_NAME'];
//Load the database table name
$TABLE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
//Load this and semester page database table key names array 
$THIS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];	
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
//Load this and semester page database table key types array
$THIS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];
$SEMESTER_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$SEMESTER_PAGE_SWITCH];
//QUERY the $semesterListArray
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
//Load the target array number
$targetArray = $_POST['semesterList'];
//Reform the classList global vars
if($targetArray != ""){
	$weekCount = $semesterListArray[$targetArray]['WEEK_COUNT'];
	$TABLE_NAME .= "_".$semesterListArray[$targetArray]['SEMESTER']."_".$semesterListArray[$targetArray]['PART']; 
	$THIS_TABLE_KEY_NAMES_ARRAY = table_key_names_auto_fill($THIS_TABLE_KEY_NAMES_ARRAY, "WEEK", $weekCount, 1);	
}
//Query the $classListArray
$classListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);
//Load the target array ID number
//$targetId = $semesterListArray[$targetArray][$THIS_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($targetArray != ""){
	$THIS_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($THIS_TABLE_KEY_TYPES_ARRAY, "WEEK", $weekCount, "int", 1);
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
if($_POST["semesterListDelete"]){
	table_data_delete_by_id($TABLE_NAME, $targetId);
}
//CHANGE the semester information to database if POST 
if($_POST["semesterInfoChange"]){
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$SEMESTER_INFO_CHANGE_ARRAY[$value] = "'".$_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	table_data_change($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $targetId, $SEMESTER_INFO_CHANGE_ARRAY);
}

//REQUERY the $semesterListArray for display
//$semesterListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);


//------  -[ Views Functions ]-  ------

div_head_output_with_class_option("mainMiddle");
	//Print Main Title
	main_title_output($PAGE_SWITCH, $PAGE_INFO_ARRAY);
	//Print main form
	div_head_output_with_class_option("form");
		//Print form Block
		form_head_output($FILE_NAME, "post");	
		//Print semesterList Block
		div_head_output_with_class_option("mainMiddleBlockLeft");
		semester_list_output($PAGE_SWITCH, $targetArray, $semesterListArray, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
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
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>