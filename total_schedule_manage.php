<?php
/**
*	Total Schedule Manage Page
*	
*	Serial:		120430
*	by:			M.Karminski
*
*/


//TODO: Add views functions [Include total schedule table]. 

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


//Load semester week
$SEMESTER_WEEK_SET = $_POST[$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY['SEMESTER_WEEK']];//Load semester week

//QUERY the $totalScheduleArray
$totalScheduleArray = table_data_query($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");

//Reschedule determinative syntax
if($_POST['RESCHEDULE']){
	
//Load $appointedClassArray which has been appointed the week of semester
//
//Example:
//$appointedClassArray[0]['CLASS_NAME'] 	= "机设09-1";
//$appointedClassArray[0]['CLASS_TYPE'] 	= "A";
//$appointedClassArray[0]['ID'] 			= "0";
//$appointedClassArray[0]['COURSE_0'] 		= "2";
//$appointedClassArray[0]['COURSE_1'] 		= "24";

	$appointedClassArray = class_array_appoint($classListArray, $SEMESTER_WEEK_SET);
	$appointedClassArrayCount0 = count($appointedClassArray);

//Greate course left capability array [$courseCapabilityArray]
//Example:
//$courseCapabilityArray[0]['COURSE_0_0'] = 4;	$courseCapabilityArray[0]['COURSE_0_1'] = 4;	$courseCapabilityArray[0]['COURSE_0_2'] = 4;	$courseCapabilityArray[0]['COURSE_0_3'] = 4;
//$courseCapabilityArray[0]['COURSE_1_0'] = 2;	$courseCapabilityArray[0]['COURSE_1_1'] = 2;	$courseCapabilityArray[0]['COURSE_1_2'] = 2;	$courseCapabilityArray[0]['COURSE_1_3'] = 2;
//...

	$courseCapabilityArray = course_capability_array_get($courseListArray, $COURSE_DAY_OF_A_WEEK, $COURSE_IN_A_DAY);
	$courseCapabilityArrayCount0 = count($courseCapabilityArray);
	//var_dump($courseCapabilityArray);

//Create Temp Schedule Array
//
//Example:
//$tempScheduleArray[$classCounter][0]['COURSE_0_0']			= "TI.电信08-1";
//$tempScheduleArray[$classCounter][0]['COURSE_1_0']			= "TI.电信08-1";
//$tempScheduleArray[$classCounter][0]['COURSE_1_1']			= "T0.电信08-1";
//...

	$tempScheduleArray[$classCounter];

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
//...
	
	$totalScheduleArray;
	unset($totalScheduleArray);//Unset for reschedule.
	

////	Arrange start, arranged by CLASS

//Input data
	$courseListArray;					//Include all course info
	$courseListArrayCount0;				//Length of $courseListArray
	$courseCapabilityArray;				//Incude course capability info 
	$courseCapabilityArrayCount0;		//Length of $courseCapabilityArray
	$appointedClassArray;				//Include class info in a semester week
	$appointedClassArrayCount0;			//length of $appointedClassArray
	$totalScheduleArray;				//Total schedule storage array
	$tempScheduleArray[$classCounter];	//Schedule array for one class
	$ONE_COURSE_PERIOD;					//Is 2
	$COURSE_IN_A_DAY;					//Is 4

//班级循环开始
for($classCounter=0;$classCounter<$appointedClassArrayCount0;$classCounter++){
	//vars_checkout($classCounter, "classCounter");
	unset($courseTakeInArrangeArray);
	$classNameInArrange = $appointedClassArray[$classCounter]['CLASS_NAME'];//班级名
	$courseTakeCounter = 0;	//上课课程数计数器
	$totalCourseTakeQuantityInArrange = 0;//已参加课程计数器
	//课程循环开始
	for($courseCounter=0;$courseCounter<$courseListArrayCount0;$courseCounter++){
		//vars_checkout($courseCounter, "courseCounter");
		$courseName = "COURSE_".$courseCounter;	//课程名[in course key name]
		$courseStyle = $courseListArray[$courseCounter]['COURSE_STYLE'];//课程标志
		$classType = $appointedClassArray[$classCounter]['CLASS_TYPE'];	//班级参加课程类型
		$coursePeriod = $courseListArray[$courseCounter][$classType];	//选定课程学时
		$allTrainCourseLeft = course_period_left_get($appointedClassArray[$classCounter], $courseListArray, 0);
		$allCourseLeft = course_period_left_get($appointedClassArray[$classCounter], $courseListArray, 1);
		
		//获取当前课程课程可容纳班级量
		$courseCapabilityLeftCounter = 0;//Reset the course capability left counter
		$totalCourseTakeQuantityInArrange;//已参加课程计数器
		$dayCourseCounter = 0;//计数日课程循环
		$dayCounter = 0;//计数上课日
		for($courseTakeRound=0;$courseTakeRound<($coursePeriod+$totalCourseTakeQuantityInArrange)/2;$courseTakeRound++){
			if($dayCourseCounter >= $COURSE_IN_A_DAY){
				$dayCourseCounter = 0;//如果上完了当天的所有课程则置零
				$dayCounter ++;//上完当天课程进入下一天
			}
			//周上课日检测,超过5天[每周上课天数]自动终止循环
			if($dayCounter>$COURSE_DAY_OF_A_WEEK-1){
				break;
			}
			$courseKeyName = $courseName."_".$dayCourseCounter;//组合$courseKeyName
			if($courseTakeRound>=($totalCourseTakeQuantityInArrange/2)){
				$courseCapabilityLeftCounter += $courseCapabilityArray[$dayCounter][$courseKeyName];
			}
			$dayCourseCounter ++;
		}

		//排列课程并写入临时数组
		//可排课判断条件为 课程有可用量 与 该班级有这门课且未曾上过 与 周课程量有剩余 
		if($courseCapabilityLeftCounter >= $coursePeriod/2 && $appointedClassArray[$classCounter][$courseName] != 0 && $appointedClassArray[$classCounter][$courseName] != "F" && $totalCourseTakeQuantityInArrange < $MAX_TAKE_COURSE_A_WEEK){
			//检测是否为概论课
			vars_checkout($courseListArray[$courseCounter]['COURSE_STYLE'], "courseListArray[\$courseCounter]['COURSE_STYLE']");
			if($courseListArray[$courseCounter]['COURSE_STYLE'] == "G"){
				//周课程量-1 概论课占用实训课第一节
				$totalCourseTakeQuantityInArrange - 2;
			}
			//检测是否为工艺设计, 所有训练科目结束之后方可进行工艺设计
			vars_checkout($allTrainCourseLeft, "allTrainCourseLeft");
			if($courseListArray[$courseCounter]['COURSE_STYLE'] == "GY" && $allTrainCourseLeft != 0){
				continue;
			}
			//检测是否为考试
			if($courseListArray[$courseCounter]['COURSE_STYLE'] == "K" && $allCourseLeft != 0){
				continue;
			}
			//上课名称及数量
			$courseTakeInArrangeArray[$courseTakeCounter]['COURSE_KEY_NAME'] = $courseName;
			$courseTakeInArrangeArray[$courseTakeCounter]['COURSE_STYLE'] = $courseStyle;
			//$!!!courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'] = $appointedClassArray[$classCounter][$courseName]/$ONE_COURSE_PERIOD;
			$courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'] = $coursePeriod;
			//从数组中填充排列完的课程
			$appointedClassArray[$classCounter][$courseName] = "F";//填充F.Finish
			//更新课程可用量[Ready to remove]
			//$courseListArray[$courseCounter]['COURSE_CAPABILITY'] --;
			//周课程量计数器
			$totalCourseTakeQuantityInArrange += $courseTakeInArrangeArray[$courseTakeCounter]['QUANTITY'];
			//课程类型计数器
			$courseTakeCounter ++;
		}
	}

	//将排列课程写入临时数组并更新$courseCapabilityArray
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
		for($courseTakeRound=0;$courseTakeRound<($courseTakeQuantity/2);$courseTakeRound++){
			if($dayCourseCounter >= $COURSE_IN_A_DAY){
				$dayCourseCounter = 0;//如果上完了当天的所有课程则置零
				$dayCounter ++;//上完当天课程进入下一天
			}
			$courseKeyName = $courseTakeKeyName."_".$dayCourseCounter;//组合$courseKeyName			
			//更新$courseCapabilityArray
			$courseCapabilityArray[$dayCounter][$courseKeyName] --;
			///填充开始
			//如果这节是概论课 则填充为"G"
			if($courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] == "G"){
				$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = "G.".$classNameInArrange;//班级名
			}
			//如果为实训课程则正常填充
			if($courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] != "G" && $courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] != "GY" && $courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] != "K" ){
				//如果上一节课是概论课 与 这节课是本课程的第一节 这节课的上个空课时将被填充成概论课
				if($courseTakeInArrangeArray[$courseTakePart-1]['COURSE_STYLE'] == "G" && $courseTakeRound == 0){
					$dayCourseCounter = 0;
					$courseKeyNameTemp = $courseTakeKeyName."_".$dayCourseCounter;//组合$courseKeyName	
					$tempScheduleArray[$classCounter][$dayCounter][$courseKeyNameTemp] = "G.".$classNameInArrange;//班级名
				}
				//按序列填充
				$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = $courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'].$courseTakeRound.".".$classNameInArrange;
				//填充被概论课占用的理论课
				if($courseTakeInArrangeArray[$courseTakePart-1]['COURSE_STYLE'] == "G" && $courseTakeRound != 0){
					$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = $courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'].($courseTakeRound-1).".".$classNameInArrange;
				}
				//如果为该课程的最后一节则填充为"TF"
				if($courseTakeRound == ($courseTakeQuantity/2-1)){
					$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = $courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE']."W.".$classNameInArrange;//班级名
				}
			}
			//TODO: The Design and Exam course have no effect.[IMPORTANT]
			//课程设计 Title Info 填充
			if($courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] == "GY"){
				if($courseTakeRound == $courseTakeInArrangeCount0-1){
					$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = "GYW.".$classNameInArrange;
				}else{
					$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = "GY".($courseTakeQuantity-1).".".$classNameInArrange;
				}
			}
			//考试 Title Info 填充
			if($courseTakeInArrangeArray[$courseTakePart]['COURSE_STYLE'] == "K"){
				$tempScheduleArray[$classCounter][$dayCounter][$courseKeyName] = "K.".$classNameInArrange;
			}
			//
			$dayCourseCounter ++;
		}
	}

}

//将$tempScheduleArray[$classCounter]载入$totalScheduleArray

	//$tempScheduleArray
	//
	//Example:			[班级序列号]   [星期][课程]
	//$tempScheduleArray[$classCounter][0]['COURSE_0_0']			= "TI.电信08-1";
	//$tempScheduleArray[$classCounter][0]['COURSE_1_0']			= "TI.电信08-1";
	//$tempScheduleArray[$classCounter][0]['COURSE_1_1']			= "T0.电信08-1";
	//...
	$tempScheduleArray[$classCounter];

	//$totalScheduleArray
	//
	//Example:
	//$totalScheduleArray[0]['SEMESTER_WEEK'] 	= 1;
	//$totalScheduleArray[0]['WEEK'] 			= 1;
	//$totalScheduleArray[0]['COURSE_0_0'] 		= "TI.电信08-1";
	//$totalScheduleArray[0]['COURSE_0_1'] 		= "T0.电信08-1";
	//$totalScheduleArray[0]['COURSE_0_2'] 		= "T1.电信08-1";
	//$totalScheduleArray[0]['COURSE_0_3'] 		= "TF.电信08-1";
	//...
	$totalScheduleArray;

//var_dump($tempScheduleArray);
//按周循环
$appointedClassArrayCount0 = count($tempScheduleArray);
$weekTemp = 0;
$totalScheduleArrayCount0 = 0;
for($week=0;$week<5;$week++){
	for($classCounter=0;$classCounter<$appointedClassArrayCount0;$classCounter++){
		if($tempScheduleArray[$classCounter][$week]){
			foreach($tempScheduleArray[$classCounter][$week] as $key => $value){
				if($week != $weekTemp){
					$totalScheduleArrayCount0 = count($totalScheduleArray);
					$weekTemp ++;
				}
				$serial = $totalScheduleArrayCount0;
				$serial = serial_counter($totalScheduleArray, $serial, $key);
				$totalScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;//1
				$totalScheduleArray[$serial]['WEEK'] = $week;
				$totalScheduleArray[$serial][$key] = $value;
			}
		}
	}
}

//将$appointedClassArray写入数据库
$appointedClassArrayCount0 = count($appointedClassArray);
for($i=0;$i<$appointedClassArrayCount0;$i++){
	$targetId = $appointedClassArray[$i]['ID'];
	//table_data_change($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, $targetId, $appointedClassArray[$i]);
}

//将$totalScheduleArray写入数据库
$totalScheduleArrayCount0 = count($totalScheduleArray);
for($i=0;$i<$totalScheduleArrayCount0;$i++){
	//table_data_add($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $totalScheduleArray[$i]);
}

//var_dump($totalScheduleArray);
}//Bracket for reschedule determinative syntax

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
		week_select_output($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $SEMESTER_WEEK_SET);
		table_info_output($TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $totalScheduleArray);
		reschedule_button_output();
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();


//Fin.
?>