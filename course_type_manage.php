<?php
/**
*	Course Type Manage Page
*	
*	Serial:		120425
*	by:			M.Karminski
*
*/
//TODO: Fix table name get method.
//Load page number
$PAGE_SWITCH = 2;
$SEMESTER_PAGE_SWITCH = 1;
$COURSE_PAGE_SWITCH = 3;


//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//TODO: set the default POST value to disable the php notice. 
//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];

//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);

//Load the target array number
$semesterTargetArray = $_POST['semesterList'];
$courseTypeTargetArray = $_POST['courseTypeList'];

//QUERY the $courseTypeListArray
$TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$THIS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];
$THIS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];	
$courseTypeListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);



//Load the target array ID number
$targetId = $courseTypeListArray[$courseTypeTargetArray][$THIS_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $THIS_TABLE_KEY_TYPES_ARRAY);
}
//QUERY the $courseTypeInfoArray from database.COURSE and ADD it into database.
if(!is_array($courseTypeListArray)){
	//Get all course type and load in $courseTypeArray.
	$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
	$COURSE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($COURSE_TABLE_NAME);
	$courseTypeArray = $COURSE_TABLE_KEY_NAMES_ARRAY;
	unset($courseTypeArray['COURSE_NAME'], $courseTypeArray['COURSE_CAPABILITY'], $courseTypeArray['COURSE_STYLE'], $courseTypeArray['ID']);
	//Get all course course type total period and load in $courseTypeTotalPeriodArray and ADD into database.COURSE_TYPE.
	$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);
	$courseListArrayCount0 = count($courseListArray);
	foreach($courseTypeArray as $type){
		//LOAD into an array
		for($i=0;$i<$courseListArrayCount0;$i++){
			$courseTypeTotalPeriodArray[$type] += $courseListArray[$i][$type];
		}
		//ADD into adtabase
		$courseTypeInfoArray[$THIS_TABLE_KEY_NAMES_ARRAY['COURSE_TYPE']] = $type;
		$courseTypeInfoArray[$THIS_TABLE_KEY_NAMES_ARRAY['COURSE_TOTAL_PERIOD']] = $courseTypeTotalPeriodArray[$type]; 
		table_data_add($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $courseTypeInfoArray);
	}
}
//ADD the information to database if POST 
if($_POST["courseTypeInfoAdd"]){
	//Load the POST info array
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$courseTypeInfoArray[$value] = $_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]];
	}
	unset($value);
	table_data_add($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $courseTypeInfoArray);
}
//DELETE the information to database if POST
if($_POST["courseTypeListDelete"]){
	table_data_delete_by_id($TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["courseTypeInfoChanged"]){
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$courseTypeInfoChangeArray[$value] = $_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]];
	}
	unset($value);
	table_data_change($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $targetId, $courseTypeInfoChangeArray);
}

//REQUERY the $courseTypeListArray for display
$courseTypeListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);

//------  -[ Views Functions ]-  ------
//TODO: Create views functions.
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
		//Print semesterInfo Block
		div_head_output_with_class_option("mainMiddleBlockRight");
		course_type_list_output($courseTypeListArray, $courseTypeTargetArray);
		if(!$_POST['courseTypeListChange']){
			course_type_info_output($THIS_TABLE_KEY_NAMES_ARRAY);
		}else{
			course_type_info_change_output($courseTypeListArray, $THIS_TABLE_KEY_NAMES_ARRAY, $courseTypeTargetArray);
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