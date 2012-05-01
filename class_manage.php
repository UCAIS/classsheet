<?php
/**
*	Class Manage Page
*	
*	Serial:		120422
*	by:			M.Karminski
*
*/

//TODO: Add table name auto get method.
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
$COURSE_TYPE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_TYPE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$COURSE_TYPE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$COURSE_TYPE_PAGE_SWITCH];
$courseTypeListArray = table_data_query($COURSE_TYPE_TABLE_NAME, $COURSE_TYPE_TABLE_KEY_NAMES_ARRAY);

//QUERY the $courseListArray
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$COURSE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($COURSE_TABLE_NAME);
$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);

//Query the $classListArray
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASS_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$PAGE_SWITCH];	
$CLASS_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$PAGE_SWITCH];
$weekCount = $semesterListArray[$semesterTargetArray]['WEEK_COUNT'];
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_auto_fill($CLASS_TABLE_KEY_NAMES_ARRAY, "WEEK", $weekCount, 1);
$CLASS_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($CLASS_TABLE_KEY_TYPES_ARRAY, "WEEK", $weekCount, "varchar(15)", 1);
	//COURSE_LEFT TABLE structure create
	$trainCourseArray = train_course_array_form($courseListArray);
	$trainCourseArrayCount0 = count($trainCourseArray);
	for($i=0;$i<$trainCourseArrayCount0;$i++){
		$CLASS_TABLE_KEY_NAMES_ARRAY = array_key_insert($CLASS_TABLE_KEY_NAMES_ARRAY, $trainCourseArray[$i]['COURSE_KEY_NAME'], $trainCourseArray[$i]['COURSE_KEY_NAME']);
		$CLASS_TABLE_KEY_TYPES_ARRAY = array_key_insert($CLASS_TABLE_KEY_TYPES_ARRAY, $trainCourseArray[$i]['COURSE_KEY_NAME'], "varchar(15)");
	}

//QUERY $classListArray	
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);

//Load the target array ID number
$targetId = $classListArray[$classTargetArray][$CLASS_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $CLASS_TABLE_KEY_TYPES_ARRAY);
}
//ADD the information to database if POST 
if($_POST["classInfoAdd"]){
	//Load the POST info array
	foreach($CLASS_TABLE_KEY_NAMES_ARRAY as $value){
		$classInfoArray[$value] = "'".$_POST[$CLASS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	unset($value);
		//COURSE_LEFT auto fill method
		$courseListArrayCount0 = count($courseListArray);
		$classType = $_POST[$CLASS_TABLE_KEY_NAMES_ARRAY['CLASS_TYPE']];
		for($i=0;$i<$courseListArrayCount0;$i++){
			$classInfoArray[$courseListArray[$i]['COURSE_KEY_NAME']] = $courseListArray[$i][$classType];
		}
	table_data_add($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $classInfoArray);
}
//DELETE the information to database if POST
if($_POST["classListDelete"]){
	table_data_delete_by_id($CLASS_TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["classInfoChanged"]){
	foreach($CLASS_TABLE_KEY_NAMES_ARRAY as $value){
		$classInfoChangeArray[$value] = "'".$_POST[$CLASS_TABLE_KEY_NAMES_ARRAY[$value]]."'";
	}
	unset($value);
	table_data_change($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $targetId, $classInfoChangeArray);
}

//REQUERY the $classListArray for display
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);

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
			class_info_output($courseTypeListArray, $CLASS_TABLE_KEY_NAMES_ARRAY);
		}else{
			class_info_change_output($classListArray, $courseTypeListArray, $CLASS_TABLE_KEY_NAMES_ARRAY, $classTargetArray);
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
