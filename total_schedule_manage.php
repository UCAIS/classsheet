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


//// Arrange data preload

$SEMESTER_WEEK_SET = 0;//First semester week
	
//Load $appointedClassArray which has been appointed the week of semester
//
//Example:
//$appointedClassArray[0]['CLASS_NAME'] 	= "机设09-1";
//$appointedClassArray[0]['CLASS_TYPE'] 	= "A";
//$appointedClassArray[0]['COURSE_0'] 		= "2";

	$appointedClassArray = class_array_appoint($classListArray, $courseListArray, $SEMESTER_WEEK_SET);
	$appointedClassArrayCount0 = count($appointedClassArray);

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

//Greate course left capability array [$courseCapabilityArray]
//Example:
//$courseCapabilityArray[0]['COURSE_0_0'] = 2;	$courseCapabilityArray[0]['COURSE_0_1'] = 2;	$courseCapabilityArray[0]['COURSE_0_2'] = 2;	$courseCapabilityArray[0]['COURSE_0_3'] = 2;
//$courseCapabilityArray[0]['COURSE_1_0'] = 2;	$courseCapabilityArray[0]['COURSE_1_1'] = 2;	$courseCapabilityArray[0]['COURSE_1_2'] = 2;	$courseCapabilityArray[0]['COURSE_1_3'] = 2;
//...

	$courseCapabilityArray = course_capability_array_get($courseListArray, $COURSE_DAY_OF_A_WEEK, $COURSE_IN_A_DAY);

//Create Temp Schedule Array
//
//Example:
//$tempScheduleArray[0]['COURSE_0_0']			= "TI.电信08-1";

	$tempScheduleArray;


////	Arrabg start, arranged by CLASS

//Input data
	$courseListArray;				//Include all course info
	$courseListArrayCount0;			//Length of $courseListArray
	$courseCapabilityArray;			//Incude course capability info
	$appointedClassArray;			//Include class info in a semester week
	$appointedClassArrayCount0;		//length of $appointedClassArray
	$totalScheduleArray;			//Storage array
	$ONE_COURSE_PERIOD;				//Is 2
	$COURSE_IN_A_DAY;				//Is 4

//班级循环开始
for($classCounter=0;$classCounter<$appointedClassArrayCount0;$classCounter++){
	$classNameInArrange = $appointedClassArray[$classCounter]['CLASS_NAME'];//班级名
	$courseTakeCounter = 0;	//上课课程数计数器
	//课程循环开始
	for($courseCounter=0;$courseCounter<$courseListArrayCount0;$courseCounter++){
		$courseName = "COURSE_".$courseCounter;	//课程名[in course key name]
		$classType = $appointedClassArray[$classCounter]['CLASS_TYPE'];	//班级参加课程类型
		$coursePeriod = $courseListArray[$courseCounter][$classType];	//选定课程学时
		
		//获取当前课程课程可容纳班级量
		//TODO: 添加第二节课检测手段
		$dayCourseCounter = 0;//计数日课程循环
		$dayCounter = 0;//计数上课日
		for($courseTakeRound=0;$courseTakeRound<$$coursePeriod;$courseTakeRound++){
			if($dayCourseCounter >= $COURSE_IN_A_DAY){
				$dayCourseCounter = 0;//如果上完了当天的所有课程则置零
				$dayCounter ++;//上完当天课程进入下一天
			}
			$courseKeyName = $courseName."_".$dayCourseCounter;//组合$courseKeyName
			$courseCapabilityLeftCounter += $courseCapabilityArray[$dayCounter][$courseKeyName];
			$dayCourseCounter ++;
		}

		//排列课程并写入临时数组
		//可排课判断条件为 课程有可用量大于0 与 该班级有这门课且未曾上过 与 周课程量有剩余 
		if($!!!courseCapabilityLeftCounter > 0 && $appointedClassArray[$classCounter][$courseName] != 0 && $appointedClassArray[$classCounter][$courseName] != "F" && $totalCourseTakeQuantityInArrange < $MAX_TAKE_COURSE_A_WEEK){
			//检测是否为概论课
			if($courseListArray[$courseCounter]['COURSE_STYLE'] == "TI"){
				//周课程量-1 概论课占用实训课第一节
				$totalCourseTakeQuantityInArrange - 1;
			}
			//TODO:如果是工艺设计或考试   其他课程全部完成之前不可进行排列
			//上课名称及数量
			$courseTakeInArrangeArray[$courseTakeCounter]['COURSE_KEY_NAME'] = $courseName;
			//$!!!courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'] = $appointedClassArray[$classCounter][$courseName]/$ONE_COURSE_PERIOD;
			$courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'] = $coursePeriod;
			//从数组中填充排列完的课程
			$appointedClassArray[$classCounter][$courseName] = "F";//填充F.Finish
			//更新课程可用量
			//$courseListArray[$courseCounter]['COURSE_CAPABILITY'] --;
			//周课程量计数器
			$totalCourseTakeQuantityInArrange .= $courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'];
			//课程类型计数器
			$courseTakeCounter ++;
		}
	}
	//TODO:更新$courseCapabilityArray

	//将排列课程写入临时数组
	//$courseTakeInArrangeArray[0]['QUANTITY'] = 8;
	//$courseTakeInArrangeArray[0]['COURSE_KEY_NAME'] = 'COURSE_0'; 
	$courseTakeInArrangeCount0 = count($courseTakeInArrangeArray);
	$dayCourseCounter = 0;//计数日课程循环
	$dayCounter = 0;//计数上课日
	//循环$courseTakeInArrangeArray
	for($courseTakePart=0;$courseTakePart<$courseTakeInArrangeCount0;$courseTakePart++){
		$courseTakeKeyName = $courseTakeInArrangeArray[$courseTakePart]['COURSE_KEY_NAME'];
		$courseTakeQuantity = $courseTakeInArrangeArray[$courseTakePart]['QUANTITY'];
		//循环$courseTakeInArrangeArray[$courseTakePart]['QUANTITY']
		for($courseTakeRound=0;$courseTakeRound<$courseTakeQuantity;$courseTakeRound++){
			if($dayCourseCounter >= $COURSE_IN_A_DAY){
				$dayCourseCounter = 0;//如果上完了当天的所有课程则置零
				$dayCounter ++;//上完当天课程进入下一天
			}
			//TODO:班级名填充方式
			$courseKeyName = $courseTakeKeyName."_".$dayCourseCounter;//组合$courseKeyName			
			$tempScheduleArray[$dayCounter][$courseKeyName] = "T".$courseTakePart.".".$classNameInArrange;//班级名
			//如果为该课程的最后一节则填充为"TF"
			if($courseTakeRound == $courseTakeInArrangeCount0-1){
				$tempScheduleArray[$dayCounter][$courseKeyName] = "TF.".$classNameInArrange;//班级名
			}
			//如果上一节课是概论课 与 这节课是本课程的第一节 这节课的上个空课时将被填充成概论课
			if($courseTakeInArrangeArray[$courseTakePart-1]['COURSE_KEY_NAME'] == "COURSE_0" && $courseTakeRound == 0){
				$courseKeyName = $courseTakeKeyName."_".$dayCourseCounter-1;//组合$courseKeyName
				$tempScheduleArray[$dayCounter][$courseKeyName] = "TI.".$classNameInArrange;//班级名
			}
			//如果这节是概论课 则填充为"TI"
			if($courseTakeKeyName == "COURSE_0"){
				$tempScheduleArray[$dayCounter][$courseKeyName] = "TI.".$classNameInArrange;//班级名
			}
			$dayCourseCounter ++;
		}
	}

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