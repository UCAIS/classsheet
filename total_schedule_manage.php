<?php
/**
*	Total Schedule Manage Page
*	
*	Serial:		120430
*	by:			M.Karminski
*
*/


//Page switch
$PAGE_SWITCH = $TOTAL_SCHEDULE_PAGE_SWITCH = 7;
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


//Preload all info array
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$COURSE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($COURSE_TABLE_NAME);
$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($CLASS_TABLE_NAME);
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);


//CREATE the TOTAL_SCHEDULE_TABLE
//TODO: add "if" phrase.	
$TOTAL_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TOTAL_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
//Form the $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY and $TOTAL_SCHEDULE_TABLE_KEY_TYPES_ARRAY
$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$TOTAL_SCHEDULE_PAGE_SWITCH];
$TOTAL_SCHEDULE_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$TOTAL_SCHEDULE_PAGE_SWITCH];
$courseListArrayCount0 = count($courseListArray);
for($i=0;$i<$courseListArrayCount0;$i++){
	$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY = table_key_names_auto_fill($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $courseListArray[$i]['COURSE_KEY_NAME'], $COURSE_IN_A_DAY, 1);
	$TOTAL_SCHEDULE_TABLE_KEY_TYPES_ARRAY = table_key_types_auto_fill($TOTAL_SCHEDULE_TABLE_KEY_TYPES_ARRAY, $courseListArray[$i]['COURSE_KEY_NAME'], $COURSE_IN_A_DAY, "varchar(15)", 1);
}
database_table_create($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $TOTAL_SCHEDULE_TABLE_KEY_TYPES_ARRAY);

//// //// //// //// //// //// //// //// //// //// //// //// - A -

$SEMESTER_WEEK_SET = 0;//First semester week
	
//Load $appointedClassArray which has been appointed the week of semester
//
//Example:
//$appointedClassArray[0]['CLASS_NAME'] 	= "机设09-1";
//$appointedClassArray[0]['CLASS_TYPE'] 	= "A";
//$appointedClassArray[0]['COURSE_0'] 		= "2";

	$appointedClassArray = class_array_appoint($classListArray, $courseListArray, $SEMESTER_WEEK_SET);

//Create basic storage array [$totalScheduleArray]
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
//$totalScheduleArray[0]['SEMESTER_WEEK'] 	= 1;
//$totalScheduleArray[0]['WEEK'] 			= 1;
//$totalScheduleArray[0]['COURSE_0_0'] 		= "TI.电信08-1";
//$totalScheduleArray[0]['COURSE_0_1'] 		= "T0.电信08-1";
//$totalScheduleArray[0]['COURSE_0_2'] 		= "T1.电信08-1";
//$totalScheduleArray[0]['COURSE_0_3'] 		= "TF.电信08-1";
	
	$totalScheduleArray;

//Create Temp Schedule Array
//
//Example:
//$tempScheduleArray[0]['COURSE_0_0']			= "TI.电信08-1";

	$tempScheduleArray;

//Arranged by CLASS
	$courseListArray;			//Include all course info
	$courseListArrayCount0;
	$appointedClassArray;		//Include class info in a semester week
	$appointedClassArrayCount0 = count($appointedClassArray);
	$totalScheduleArray;		//Storage array
	$ONE_COURSE_PERIOD;			//Equal 2

for($classCounter=0;$classCounter<$appointedClassArrayCount0;$classCounter++){

	//1.计算课程排列量
	$classNameInArrange = $appointedClassArray[$classCounter]['CLASS_NAME'];//课程名
	//循环并计算可利用课程
	$courseTakeCounter = 0;
	for($courseCounter=0;$courseCounter<$courseListArrayCount0;$courseCounter++){
		$courseName = "COURSE_".$courseCounter;
		//获取课程可用量
		//TODO: [BUG] 可用量应达到每节课精度
		$courseLeftNumber = $courseListArray[$courseCounter]['COURSE_CAPABILITY'];
		//可排课判断条件为 课程有可用量大于0 与 该班级有这门课且未曾上过 与 周课程量有剩余 
		if($courseLeftNumber > 0 && $appointedClassArray[$classCounter][$courseName] != 0 && $appointedClassArray[$classCounter][$courseName] != "F" && $totalCourseTakeQuantityInArrange < $MAX_TAKE_COURSE_A_WEEK){
			//上课数量及名称
			$courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'] = $appointedClassArray[$classCounter][$courseName]/$ONE_COURSE_PERIOD;
			$courseTakeInArrangeArray[$courseTakeCounter]['COURSE_KEY_NAME'] = $courseName;
			//从数组中填充排列完的课程
			$appointedClassArray[$classCounter][$courseName] = "F";
			//更新课程可用量
			$courseListArray[$courseCounter]['COURSE_CAPABILITY'] --;
			//周课程量计数器
			$totalCourseTakeQuantityInArrange .= $courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'];
			$courseTakeCounter ++;
		}
		
	}
	//将排列课程写入临时数组
	$courseTakeInArrangeCount0 = count($courseTakeInArrangeArray);
	for($courseTakePart=0;$courseTakePart<$courseTakeInArrangeCount0;$courseTakePart++){
		$courseTakeQuantityIntPart = floor($courseTakeInArrange[$courseTakePart]['QUANTITY'] / $COURSE_IN_A_DAY)+1;// +1 for float part
		$courseTakeQuantityFloatPart = $courseTakeInArrange[$courseTakePart]['QUANTITY'] % $COURSE_IN_A_DAY;
		for($courseTakeRound=0;$courseTakeRound<$courseTakeQuantityIntPart;$courseTakeRound++){
			$tempScheduleArray[$courseTakeRound]['COURSE_0_0'] = $classNameInArrange;
		}
	}
	//写入衔接处检测
}
//将$appointedClassArray写入数据库



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
		table_info_output($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $totalScheduleArray);
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();


//Fin.
?>