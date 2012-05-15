<?php
/**
*	Class Room Schedule Manage Page
*	
*	Serial:		120512
*	by:			M.Karminski
*
*	Classroom Schedule Manage
*			
*	Classroom assign method
*		The 'Simple Classroom[Si]' access all [TI], [T0] title course, and as the first choice.
*		Other speical classroom only access original course, and as the second choice.
*	Teachers assign method
*		Teachers arranged by data list. One teacher only take one course in same time.
*		Create the 'TEACH_FREQUENCY' table to mark the teacher's take course frequency.
*		The teacher's take course number base on average value of 'TEACH_FREQUENCY'.
*/


//Page switch
$PAGE_SWITCH = $CLASSROOM_SCHEDULE_PAGE_SWITCH = 10;

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

//Load semester week
$SEMESTER_WEEK_SET = 0;

////Load cloassroom schedule source data

//Load $classroomListArray
$CLASSROOM_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASSROOM_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASSROOM_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$CLASSROOM_PAGE_SWITCH];
$classroomListArray = table_data_query($CLASSROOM_TABLE_NAME, $CLASSROOM_TABLE_KEY_NAMES_ARRAY);
//Load $teacherListArray
$TEACHER_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TEACHER_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$TEACHER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$TEACHER_PAGE_SWITCH];
$teacherListArray = table_data_query($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY);
//Load $totalScheduleArray
$TOTAL_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TOTAL_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($TOTAL_SCHEDULE_TABLE_NAME);
$totalScheduleArray = table_data_query($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");

//$classroomListArray Structure Describe
//
//$classroomListArray[0]['ID'] 					= 0
//$classroomListArray[0]['CLASSROOM_NAME'] 		= "实216";
//$classroomListArray[0]['CLASSROOM_TYPE'] 		= "Si";
//$classroomListArray[0]['CLASSROOM_CAPABILITY']= "4";

	$classroomListArray;

//$teacherListArray Structure Describe
//
//$teacherListArray[0]['ID']					= 0;					
//$teacherListArray[0]['TEACHER_NAME']			= "李文双";		
//$teacherListArray[0]['TEACHER_TYPE_INTRO']	= "T";	
//$teacherListArray[0]['TEACHER_TYPE_DESIGN']	= "T";		
//$teacherListArray[0]['TEACHER_TYPE_EXAM']		= "T";		
//$teacherListArray[0]['TEACHER_TYPE_TRAIN']	= "";			
//$teacherListArray[0]['TEACH_FREQUENCY']		= "";		

	$teacherListArray;

//$totalScheduleArray Structure Describe
//
//The course title info list:
//TI.Training		.I Introduction		[概论课]
//T0.Training		.0 Theory			[理论课]
//T1.Training		.1 First 			[实训]
//TF.Training		.F Finish 			[完结]
//D1.Design			.1 First			[工艺设计]
//E .Examination	.					[考试]
//
//Example:
//$totalScheduleArray[0]['SEMESTER_WEEK'] 	= 0;
//$totalScheduleArray[0]['WEEK'] 			= 1;
//$totalScheduleArray[0]['COURSE_0_0'] 		= "TI.电信08-1";
//$totalScheduleArray[0]['COURSE_0_1'] 		= "T0.电信08-1";
//$totalScheduleArray[0]['COURSE_0_2'] 		= "T1.电信08-1";
//$totalScheduleArray[0]['COURSE_0_3'] 		= "TF.电信08-1";
//...

	$totalScheduleArray;


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

		//week_select_output($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $SEMESTER_WEEK_SET);//Temporary views output
		table_info_output($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $totalScheduleArray);//Temporary views output

		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>