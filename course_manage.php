<?php
/**
*	Course Manage Page
*	
*	Serial:		120425
*	by:			M.Karminski
*
*/

//TODO: Upgrade the $TABLE_KEY_NAMES_ARRAY get method.[IMPORTANT]
//Page number
$PAGE_SWITCH = 3;
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;
//Course Type page number
$COURSE_TYPE_PAGE_SWITCH = 2;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//TODO: Fix table name get method.
//TODO: set the default POST value to disable the php notice. 
//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];

//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);

//Load the target array number
$semesterTargetArray = $_POST['semesterList'];
$courseTargetArray = $_POST['courseList'];

//QUERY the $courseTypeListArray
$COURSE_TYPE_TABLE_NAME = $PAGE_INFO_ARRAY[$COURSE_TYPE_PAGE_SWITCH]['TABLE_NAME'];
$COURSE_TYPE_TABLE_NAME .= "_".$semesterListArray[$semesterTargetArray]['SEMESTER']."_".$semesterListArray[$semesterTargetArray]['PART'];
$COURSE_TYPE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$COURSE_TYPE_PAGE_SWITCH];
$courseTypeListArray = table_data_query($COURSE_TYPE_TABLE_NAME, $COURSE_TYPE_TABLE_KEY_NAMES_ARRAY);

//Query the $courseListArray
$TABLE_NAME = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['TABLE_NAME'];
$THIS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];	
$THIS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];
$TABLE_NAME .= "_".$semesterListArray[$semesterTargetArray]['SEMESTER']."_".$semesterListArray[$semesterTargetArray]['PART'];
$courseTypeCount0 = count($courseTypeListArray);
for($i=0;$i<$courseTypeCount0;$i++){
	$courseTypeNamesArray[$i] = $courseTypeListArray[$i]['COURSE_TYPE'];
}
$THIS_TABLE_KEY_NAMES_ARRAY = table_key_names_auto_fill($THIS_TABLE_KEY_NAMES_ARRAY, $courseTypeNamesArray, $courseTypeCount0);
$THIS_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($THIS_TABLE_KEY_TYPES_ARRAY, $courseTypeNamesArray, $courseTypeCount0, "varchar(15)");
$courseListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);


//Load the target array ID number
$targetId = $courseListArray[$courseTargetArray][$THIS_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $THIS_TABLE_KEY_TYPES_ARRAY);
}
//ADD the information to database if POST 
if($_POST["courseInfoAdd"]){
	//Load the POST info array
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$courseInfoArray[$value] = $_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]];
	}
	unset($value);
	//COURSE_KEY_NAME add method.
	//TODO: Create this function
	$courseListArrayCount0 = count($courseListArray);// This function for COURSE_KEY_NAME add method.
	$courseInfoArray[$THIS_TABLE_KEY_NAMES_ARRAY['COURSE_KEY_NAME']] = "COURSE_".$courseListArrayCount0;
	table_data_add($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $courseInfoArray);
}



//DELETE the information to database if POST
if($_POST["courseListDelete"]){
	table_data_delete_by_id($TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["courseInfoChanged"]){
	foreach($THIS_TABLE_KEY_NAMES_ARRAY as $value){
		$courseInfoChangeArray[$value] = $_POST[$THIS_TABLE_KEY_NAMES_ARRAY[$value]];
	}
	unset($value);
	table_data_change($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY, $targetId, $courseInfoChangeArray);
}

//REQUERY the $courseListArray for display
$courseListArray = table_data_query($TABLE_NAME, $THIS_TABLE_KEY_NAMES_ARRAY);

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
		course_list_output($courseListArray, $courseTargetArray);
		if(!$_POST['courseListChange']){
			course_info_output($THIS_TABLE_KEY_NAMES_ARRAY);
		}else{
			course_info_change_output($courseListArray, $THIS_TABLE_KEY_NAMES_ARRAY, $courseTargetArray);
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
