<?php
/**
*	Class Manage Page
*	
*	Serial:		120422
*	by:			M.Karminski
*
*/
//TODO: Fix table name get method.
//TODO: Upgrade the CLASS_TYPE method and database structure [INPORTANT].
//Page number
$PAGE_SWITCH = 4;
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;
//Course type page number
$COURSE_TYPE_PAGE_SWITCH = 2;

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
$classTargetArray = $_POST['classList'];

//QUERY the $courseTypeListArray
$COURSE_TYPE_TABLE_NAME = $PAGE_INFO_ARRAY[$COURSE_TYPE_PAGE_SWITCH]['TABLE_NAME'];
$COURSE_TYPE_TABLE_NAME .= "_".$semesterListArray[$semesterTargetArray]['SEMESTER']."_".$semesterListArray[$semesterTargetArray]['PART'];
$COURSE_TYPE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$COURSE_TYPE_PAGE_SWITCH];
$courseTypeListArray = table_data_query($COURSE_TYPE_TABLE_NAME, $COURSE_TYPE_TABLE_KEY_NAMES_ARRAY);

//Query the $classListArray
$TABLE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['TABLE_NAME'];
$THIS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];	
$THIS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];
$weekCount = $semesterListArray[$semesterTargetArray]['WEEK_COUNT'];
$TABLE_NAME .= "_".$semesterListArray[$semesterTargetArray]['SEMESTER']."_".$semesterListArray[$semesterTargetArray]['PART']; 
$THIS_TABLE_KEY_NAMES_ARRAY = table_key_names_auto_fill($THIS_TABLE_KEY_NAMES_ARRAY, "WEEK", $weekCount, 1);
$THIS_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($THIS_TABLE_KEY_TYPES_ARRAY, "WEEK", $weekCount, "varchar(15)", 1);	
$classListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);

//Load the target array ID number
$targetId = $classListArray[$classTargetArray][$THIS_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $THIS_TABLE_KEY_TYPES_ARRAY);
}
//ADD the information to database if POST 
if($_POST["classInfoAdd"]){
	//Load the POST info array
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$classInfoArray[$value] = "'".$_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	unset($value);
	table_data_add($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $classInfoArray);
}
//DELETE the information to database if POST
if($_POST["classListDelete"]){
	table_data_delete_by_id($TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["classInfoChanged"]){
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$classInfoChangeArray[$value] = "'".$_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	unset($value);
	table_data_change($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $targetId, $classInfoChangeArray);
}

//REQUERY the $classListArray for display
$classListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);

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
		//Print semesterInfo Block
		div_head_output_with_class_option("mainMiddleBlockRight");
		class_list_output($classListArray, $classTargetArray);
		if(!$_POST['classListChange']){
			class_info_output($courseTypeListArray, $THIS_TABLE_KEY_NAMES_ARRAY);
		}else{
			class_info_change_output($classListArray, $courseTypeListArray, $THIS_TABLE_KEY_NAMES_ARRAY, $classTargetArray);
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
