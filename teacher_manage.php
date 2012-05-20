<?php
/**
*	Teacher Manage Page
*	
*	Serial:		120512
*	by:			M.Karminski
*
* 	Teacher Manage Method
*	$TEACHER_TABLE_KEY_NAMES_ARRAY
*		['ID']
*		['TEACHER_NAME']
*		The teacher type data access from course type, and storage in course key names format.
*		Teacher Type include:概论课教师, 工种理论课教师, 工种组教师, 工艺设计教师, 监考教师.
*		['TEACHER_TYPE_INTRO'] "T" means true
*		['TEACHER_TYPE_DESIGN']
*		['TEACHER_TYPE_EXAM']
*		['TEACHER_TYPE_TRAIN'] Include [Z]铸造 [H]焊接 [C]车工 [Q]钳工 [S]数控 [T]特加 [X]铣刨磨 [D]锻压
*		['TEACH_FREQUENCE']	Default value is 0.
*
*	$TEACHER_TABLE_KEY_TYPES_ARRAY
*
*	Database structure
*	| ID | TEACHER_NAME | TEACHER_TYPE_INTRO | TEACHER_TYPE_DESIGN | TEACHER_TYPE_EXAM | TEACHER_TYPE_TRAIN | TEACH_FREQUENCY |
*
*	Possible Bug: One teacher sometimes can take two or more course, so it can jam in course taking.  
*/ 


//Page switch
$PAGE_SWITCH = $TEACHER_PAGE_SWITCH = 9;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

//Load target teacher
$teacherTargetArray = $_POST['teacherList'];

//QUERY the $teacherListArray
$TEACHER_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TEACHER_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$TEACHER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$TEACHER_PAGE_SWITCH];
$TEACHER_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$TEACHER_PAGE_SWITCH];
$teacherListArray = table_data_query($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY);

//Load the target array ID number
$targetId = $teacherListArray[$teacherTargetArray][$TEACHER_TABLE_KEY_NAMES_ARRAY['ID']];

//TODO : rewrite the database_table_create "if" phrase
//CREATE the TABLE if not avaliable 
if($semesterTargetArray != ""){
	database_table_create($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY, $TEACHER_TABLE_KEY_TYPES_ARRAY);
}

//ADD the information to database if POST
print $_POST['TEACHER_NAME']; 
if($_POST["teacherInfoAdd"]){
	//Load the POST info array
	foreach($TEACHER_TABLE_KEY_NAMES_ARRAY as $value){
		$teacherInfoArray[$value] = $_POST[$value];
	}
	var_dump($teacherInfoArray);
	unset($value);
	table_data_add($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY, $teacherInfoArray);
}
//DELETE the information from database if POST
if($_POST["teacherListDelete"]){
	table_data_delete_by_id($TEACHER_TABLE_NAME, $targetId);
}
//CHANGE the information to database if POST 
if($_POST["teacherInfoChanged"]){
	foreach($TEACHER_TABLE_KEY_NAMES_ARRAY as $value){
		$teacherInfoChangeArray[$value] = $_POST[$value];
	}
	unset($value);
	table_data_change($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY, $targetId, $teacherInfoChangeArray);
}

//REQUERY the $teacherListArray for display
$teacherListArray = table_data_query($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY);



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
		div_head_output_with_class_option("mainMiddleBlockRight");
		teacher_list_output($teacherListArray, $teacherTargetArray);
		if(!$_POST['teacherListChange']){
			teacher_info_output($TEACHER_TABLE_KEY_NAMES_ARRAY);
		}else{
			teacher_info_change_output($teacherListArray, $TEACHER_TABLE_KEY_NAMES_ARRAY, $teacherTargetArray);
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