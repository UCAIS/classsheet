<?php
/**
*	Global vars Page
*	
*	Serial:		120429
*	by:			M.Karminski
*
*/

//------  -[ Global Page SWITCH Vars ]-  ------
//All these vars are used in switch page and array.
$SEMESTER_PAGE_SWITCH 				= 1;
$COURSE_TYPE_PAGE_SWITCH			= 2;
$COURSE_PAGE_SWITCH 				= 3;
$CLASS_PAGE_SWITCH					= 4;
$COURSE_PERIOD_PAGE_SWITCH			= 5;
$WEEKS_SCHEDULE_PAGE_SWITCH 		= 6;
$TOTAL_SCHEDULE_PAGE_SWITCH			= 7;
$CLASSROOM_PAGE_SWITCH				= 8;
$CLASSROOM_SCHEDULE_PAGE_SWITCH		= 9;

$COURSE_IMPORT_PAGE_SWITCH			= 12;
$CLASS_IMPORT_PAGE_SWITCH			= 13;
$SETTINGS_PAGE_SWITCH				= 14;

//TODO:Rewrite the $PAGE_NUMBER into this page.
//TODO:Replace all "THIS" method.[IMPORTANT]
//TODO:Chage the include files place.
//TODO:Add Table delete method.[IMPORTANT]
//TODO:COURSE_TYPE->COURSE_MODE, COURSE_STYLE->COURSE_TYPE.[IMPORTANT]

//------  -[ Course Day of a Week ]-  ------
//Course day of a week.
$COURSE_DAY_OF_A_WEEK = 5;

//------  -[ Course in a Day ]-  ------
//How much course in a day.
$COURSE_IN_A_DAY = 4;

//------  -[ One Course Period ]-  ------
//One course equal two course period
$ONE_COURSE_PERIOD = 2;

//------  -[ Max Take Course a Week ]-  ------
//The max value of take course in a week 
$MAX_TAKE_COURSE_A_WEEK = $COURSE_DAY_OF_A_WEEK * $COURSE_IN_A_DAY * $ONE_COURSE_PERIOD;

//------ -[ Global Page Informatin Vars ]-  ------
//
//[ WARING:All VALUES ARE USED IN GLOBAL, CHANGE IT CAREFULLY ]
//
//$PAGE_INFO_ARRAY			For global page infromations
//$TABLE_KEY_NAMES_ARRAY 	For database table key names and vars key names
//$TABLE_KEY_TYPES_ARRAY 	For database table key types, was only used in table creating 

$PAGE_INFO_ARRAY[0]['PAGE_NAME'] 				= "index";				
$PAGE_INFO_ARRAY[0]['PAGE_NAME_IN_CHINESE'] 	= "首页";		
$PAGE_INFO_ARRAY[0]['FILE_NAME']				= "index.php";

$PAGE_INFO_ARRAY[1]['PAGE_NAME']			 	= "Semester Manage";
$PAGE_INFO_ARRAY[1]['PAGE_NAME_IN_CHINESE'] 	= "学期管理";
$PAGE_INFO_ARRAY[1]['FILE_NAME'] 				= "semester_manage.php";
$PAGE_INFO_ARRAY[1]['TABLE_NAME'] 				= "SEMESTER";
$TABLE_KEY_NAMES_ARRAY[1]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[1]['SEMESTER'] 			= "SEMESTER";
$TABLE_KEY_NAMES_ARRAY[1]['PART'] 				= "PART";
$TABLE_KEY_NAMES_ARRAY[1]['START_YEAR'] 		= "START_YEAR";
$TABLE_KEY_NAMES_ARRAY[1]['START_MONTH'] 		= "START_MONTH";
$TABLE_KEY_NAMES_ARRAY[1]['START_DAY'] 			= "START_DAY";
$TABLE_KEY_NAMES_ARRAY[1]['WEEK_COUNT'] 		= "WEEK_COUNT";
$TABLE_KEY_TYPES_ARRAY[1]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[1]['SEMESTER'] 			= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[1]['PART'] 				= "int";
$TABLE_KEY_TYPES_ARRAY[1]['START_YEAR'] 		= "int";
$TABLE_KEY_TYPES_ARRAY[1]['START_MONTH'] 		= "int";
$TABLE_KEY_TYPES_ARRAY[1]['START_DAY'] 			= "int";
$TABLE_KEY_TYPES_ARRAY[1]['WEEK_COUNT'] 		= "int";

$PAGE_INFO_ARRAY[2]['PAGE_NAME'] 				= "Course Type Manage";
$PAGE_INFO_ARRAY[2]['PAGE_NAME_IN_CHINESE'] 	= "模块管理";
$PAGE_INFO_ARRAY[2]['FILE_NAME']				= "course_type_manage.php";
$PAGE_INFO_ARRAY[2]['TABLE_NAME']				= "COURSE_TYPE";
$TABLE_KEY_NAMES_ARRAY[2]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[2]['COURSE_TYPE']		= "COURSE_TYPE";
$TABLE_KEY_NAMES_ARRAY[2]['COURSE_TOTAL_PERIOD']= "COURSE_TOTAL_PERIOD";
$TABLE_KEY_TYPES_ARRAY[2]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[2]['COURSE_TYPE']		= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[2]['COURSE_TOTAL_PERIOD']= "int";

$PAGE_INFO_ARRAY[3]['PAGE_NAME'] 				= "Course Manage";
$PAGE_INFO_ARRAY[3]['PAGE_NAME_IN_CHINESE'] 	= "课程管理";
$PAGE_INFO_ARRAY[3]['FILE_NAME']				= "course_manage.php";
$PAGE_INFO_ARRAY[3]['TABLE_NAME']				= "COURSE";
$TABLE_KEY_NAMES_ARRAY[3]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_NAME']		= "COURSE_NAME";
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_KEY_NAME']	= "COURSE_KEY_NAME";
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_CAPABILITY']  = "COURSE_CAPABILITY";
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_STYLE']		= "COURSE_STYLE";
$TABLE_KEY_TYPES_ARRAY[3]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[3]['COURSE_NAME']		= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[3]['COURSE_KEY_NAME']	= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[3]['COURSE_CAPABILITY']	= "int";
$TABLE_KEY_TYPES_ARRAY[3]['COURSE_STYLE']		= "varchar(15)";

$PAGE_INFO_ARRAY[4]['PAGE_NAME'] 				= "Class Manage";
$PAGE_INFO_ARRAY[4]['PAGE_NAME_IN_CHINESE'] 	= "班级管理";
$PAGE_INFO_ARRAY[4]['FILE_NAME']				= "class_manage.php";
$PAGE_INFO_ARRAY[4]['TABLE_NAME']				= "CLASS";
$TABLE_KEY_NAMES_ARRAY[4]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[4]['CLASS_TYPE']			= "CLASS_TYPE";
$TABLE_KEY_NAMES_ARRAY[4]['CLASS_NAME']			= "CLASS_NAME";
$TABLE_KEY_NAMES_ARRAY[4]['CLASS_CAPABILITY']	= "CLASS_CAPABILITY";
//$TABLE_KEY_NAMES_ARRAY[4]['WEEK']				= "WEEK_";
//...
$TABLE_KEY_TYPES_ARRAY[4]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_TYPE']			= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_NAME']			= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_CAPABILITY']	= "int";
//$TABLE_KEY_TYPES_ARRAY[4]['WEEK']				= "varchar(15)";	//The type "varchar(15)" is requird
//...

$PAGE_INFO_ARRAY[5]['PAGE_NAME'] 				= "Course Period Manage";
$PAGE_INFO_ARRAY[5]['PAGE_NAME_IN_CHINESE'] 	= "学时管理";
$PAGE_INFO_ARRAY[5]['FILE_NAME']				= "course_period_manage.php";

$PAGE_INFO_ARRAY[6]['PAGE_NAME'] 				= "Weeks Schedule Manage";
$PAGE_INFO_ARRAY[6]['PAGE_NAME_IN_CHINESE'] 	= "周课表管理";
$PAGE_INFO_ARRAY[6]['FILE_NAME']				= "weeks_schedule_manage.php";

$PAGE_INFO_ARRAY[7]['PAGE_NAME'] 				= "Total Schedule Manage";
$PAGE_INFO_ARRAY[7]['PAGE_NAME_IN_CHINESE'] 	= "总课表管理";
$PAGE_INFO_ARRAY[7]['FILE_NAME']				= "total_schedule_manage.php";
$PAGE_INFO_ARRAY[7]['TABLE_NAME']				= "TOTAL_SCHEDULE";
$TABLE_KEY_NAMES_ARRAY[7]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[7]['SEMESTER_WEEK']		= "SEMESTER_WEEK";
$TABLE_KEY_NAMES_ARRAY[7]['WEEK']				= "WEEK";
//$TABLE_KEY_NAMES_ARRAY[7]['COURSE_0_0']		= "COURSE_0_0";
//...
$TABLE_KEY_TYPES_ARRAY[7]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[7]['SEMESTER_WEEK']		= "int";
$TABLE_KEY_TYPES_ARRAY[7]['WEEK']				= "int";
//$TABLE_KEY_TYPES_ARRAY[7]['COURSE_0_0']		= "varchar(15)";
//...

$PAGE_INFO_ARRAY[8]['PAGE_NAME'] 				= "Classroom Manage";
$PAGE_INFO_ARRAY[8]['PAGE_NAME_IN_CHINESE'] 	= "教室管理";
$PAGE_INFO_ARRAY[8]['FILE_NAME']				= "classroom_manage.php";
$PAGE_INFO_ARRAY[8]['TABLE_NAME']				= "CLASSROOM";
$TABLE_KEY_NAMES_ARRAY[8]['ID']					= "ID";
$TABLE_KEY_NAMES_ARRAY[8]['CLASSROOM_NAME']		= "CLASSROOM_NAME";
$TABLE_KEY_NAMES_ARRAY[8]['CLASSROOM_TYPE']		= "CLASSROOM_TYPE";
$TABLE_KEY_TYPES_ARRAY[8]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[8]['CLASSROOM_NAME']		= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[8]['CLASSROOM_TYPE']		= "varchar(15)";


$PAGE_INFO_ARRAY[12]['PAGE_NAME'] 				= "Course and Course type Import";
$PAGE_INFO_ARRAY[12]['PAGE_NAME_IN_CHINESE'] 	= "课程及模块导入";
$PAGE_INFO_ARRAY[12]['FILE_NAME']				= "course_import.php";

$PAGE_INFO_ARRAY[13]['PAGE_NAME'] 				= "Class and Weeks Schedule Import";
$PAGE_INFO_ARRAY[13]['PAGE_NAME_IN_CHINESE'] 	= "班级及周课表导入";
$PAGE_INFO_ARRAY[13]['FILE_NAME']				= "class_import.php";

//Fin.
?>