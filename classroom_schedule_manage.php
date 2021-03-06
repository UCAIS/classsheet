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
*		The 'Simple Classroom[Si]' access all [G], [T0] title course, and as the first choice.
*		Other speical classroom only access original course, and as the second choice.
*	Teachers assign method
*		Teachers arranged by data list. One teacher only take one course in same time.
*		Create the 'TEACH_FREQUENCY' table to mark the teacher's take course frequency.
*		The teacher's take course number base on average value of 'TEACH_FREQUENCY'.
*/
//Start session
session_start();

//TODO: Rrewrite the comment.

//Page switch
$PAGE_SWITCH = $CLASSROOM_SCHEDULE_PAGE_SWITCH = 10;

//Include files
include('settings.php');
include('html_head.php');
include('etc/global_vars.php');
include('functions/database_functions.php');
include('functions/global_functions.php');
include('functions/editable_grid_conf.php');
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

//Load $courseListArray
$COURSE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $COURSE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$COURSE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$COURSE_PAGE_SWITCH];
$courseListArray = table_data_query($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY);
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
//Load $classroomScheduleArray
$CLASSROOM_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASSROOM_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$CLASSROOM_SCHEDULE_PAGE_SWITCH];
$CLASSROOM_SCHEDULE_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$CLASSROOM_SCHEDULE_PAGE_SWITCH];
//TODO: Rewrite this method for ONE week
$classroomScheduleArray = table_data_query($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");
//If TABLE 'CLASSROOM_SCHEDULE' does not exist, create it.
database_table_create($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $CLASSROOM_SCHEDULE_TABLE_KEY_TYPES_ARRAY);


//// Arrange data preload


//Load semester week
$SEMESTER_WEEK_SET = $_POST[$CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY['SEMESTER_WEEK']];//Load semester week

//Reschedule determinative syntax
if($_POST['RESCHEDULE']){

//$courseListArray Structure Describe
//
//$courseListArray[0]['COURSE_NAME'] 				= "概论课";
//$courseListArray[0]['COURSE_STYLE']				= "G";

	$courseListArray;
	$courseListArrayCount0 = count($courseListArray);
	//Load the special course name
	for($i=0;$i<$courseListArrayCount0;$i++){
		if($courseListArray[$i]['COURSE_STYLE'] == "G"){
			$courseNameG = $courseListArray[$i]['COURSE_KEY_NAME'];
		}
		if($courseListArray[$i]['COURSE_STYLE'] == "GY"){
			$courseNameGY = $courseListArray[$i]['COURSE_KEY_NAME'];
		}
		if($courseListArray[$i]['COURSE_STYLE'] == "K"){
			$courseNameK = $courseListArray[$i]['COURSE_KEY_NAME'];
		}
	}

//$classroomListArray Structure Describe
//
//$classroomListArray[0]['ID'] 					= 0
//$classroomListArray[0]['CLASSROOM_NAME'] 		= "实216";
//$classroomListArray[0]['CLASSROOM_TYPE'] 		= "J";
//$classroomListArray[0]['CLASSROOM_CAPABILITY']= "4";

	$classroomListArray;
	$classroomListArrayCount0 = count($classroomListArray);

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
	$teacherListArrayCount0 = count($teacherListArray);

//$totalScheduleArray Structure Describe
//
//The course title info list:
//G.Training		.I Introduction		[概论课]
//T0.Training		.0 Theory			[理论课]
//T1.Training		.1 First 			[实训]
//TF.Training		.F Finish 			[完结]
//D1.Design			.1 First			[工艺设计]
//E .Examination	.					[考试]
//
//Example:
//$totalScheduleArray[0]['SEMESTER_WEEK'] 	= 0;
//$totalScheduleArray[0]['WEEK'] 			= 1;
//$totalScheduleArray[0]['COURSE_0_0'] 		= "G.电信08-1";
//$totalScheduleArray[0]['COURSE_0_1'] 		= "T0.电信08-1";
//$totalScheduleArray[0]['COURSE_0_2'] 		= "T1.电信08-1";
//$totalScheduleArray[0]['COURSE_0_3'] 		= "TF.电信08-1";
//...

	$totalScheduleArray;
	$totalScheduleArrayCount0 = count($totalScheduleArray);

//$classroomScheduleArray Structure Describe
//
//$classroomScheduleArray[0]['ID']				= 0;		
//$classroomScheduleArray[0]['SEMESTER_WEEK']	= 0;
//$classroomScheduleArray[0]['WEEK']			= 0;
//$classroomScheduleArray[0]['CLASSROOM_NAME']	= "实216";
//$classroomScheduleArray[0]['COURSE_PART_0']	= "COURSE_0";
//$classroomScheduleArray[0]['TEACHER_PART_0']	= "李文双";
//$classroomScheduleArray[0]['CLASS_PART_0']	= "G.机设09-1";

	$classroomScheduleArray;

//$classroomKeyNameSerialArray Structure Describe
//
//$classroomKeyNameSerialArray[J] = 0;
//$classroomKeyNameSerialArray[T] = 3;

	$classroomKeyNameSerialArray;
	for($i=0;$i<$classroomListArrayCount0;$i++){
		$key = $classroomListArray[$i]['CLASSROOM_TYPE'];
		$classroomKeyNameSerialArray[$key] = $i;	//[WARING: The classroom type "J" will be rewrite, because array have more than one "J" classroom.]
	}

//$classroomCapabilityArray Structure Describe
//
//$classroomScheduleArray[CLASSROOM_SERIAL][WEEK][COURSE_PART];
//$classroomCapabilityArray[0][0]['CAPABILITY_COURSE_PART_0'] = 4;
//$classroomCapabilityArray[0][0]['PROGRESS_COURSE_PART_0'] = "COURSE_0";

	$classroomCapabilityArray;
	for($i=0;$i<$classroomListArrayCount0;$i++){
		$classroomCapability = $classroomListArray[$i]['CLASSROOM_CAPABILITY'];
		for($j=0;$j<$COURSE_DAY_OF_A_WEEK;$j++){
			$classroomCapabilityArray[$i][$j]['CAPABILITY_COURSE_PART_0'] = $classroomCapability;
			$classroomCapabilityArray[$i][$j]['CAPABILITY_COURSE_PART_1'] = $classroomCapability;
			$classroomCapabilityArray[$i][$j]['CAPABILITY_COURSE_PART_2'] = $classroomCapability;
			$classroomCapabilityArray[$i][$j]['CAPABILITY_COURSE_PART_3'] = $classroomCapability;
		}
	}


/**
*	Classroom Schedule Arrange Start
*
*	This code block make the classroom schedule array.
*
*/

for($totalScheduleCounter=0;$totalScheduleCounter<$totalScheduleArrayCount0;$totalScheduleCounter++){
	foreach($totalScheduleArray[$totalScheduleCounter] as $key => $value){
		//Ignore the "SEMESTER_WEEK", "WEEK", "COURSE_0_1"~"COURSE_0_3"(The "G" course never take in course part 1,2,3).
		if($key == "SEMESTER_WEEK" || $key == "WEEK" || $key == "COURSE_0_1" || $key == "COURSE_0_2" || $key == "COURSE_0_3"){
			continue;
		}
		//Class itile info explode
		$dotExplodeArray = explode(".", $value); //$dotExplodeArray[0] is class title info. Example: "G.机设09-1".
		$classTitleInfo = $dotExplodeArray[0];
		$underlineExplodeArray = explode("_", $key); //underlineExplodeArray[1] is course key name serial number, [2] is course part serial number. Example: "COURSE_0_0".
		//Load COURSE_TYPE_TRAIN course name
		$courseNameT = "COURSE_".$underlineExplodeArray[1];
		//Load $classroomScheduleArray key names
		$coursePartName = "COURSE_PART_".$underlineExplodeArray[2];
		$capabilityPartName = "CAPABILITY_".$coursePartName;
		$progressPartName = "PROGRESS_".$coursePartName;
		$teacherPartName = "TEACHER_PART_".$underlineExplodeArray[2];       
		$classPartName = "CLASS_PART_".$underlineExplodeArray[2];
		//Load week number
		$week = $totalScheduleArray[$totalScheduleCounter]['WEEK'];
		//Load $classroomScheduleArray serial number
		//TODO: Optimize the serial method.
		$classroomScheduleArrayCount0 = count($classroomScheduleArray);
		$serial = $classroomScheduleArrayCount0;
		$serial = serial_counter($classroomScheduleArray, $serial, $classPartName);

		//概论课
		if($classTitleInfo == "G" && $underlineExplodeArray[1] == 0){ //For "COURSE_0". [WARING:Hardcode]
			//Load in classroom schedule
			for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
				if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameG || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
					//Load data in $classroomScheduleArray
					$classroomScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;
					$classroomScheduleArray[$serial]['WEEK'] = $week;
					$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
					$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "J";
					$classroomScheduleArray[$serial][$coursePartName] = $courseNameG;
					$classroomScheduleArray[$serial][$classPartName] = $value;
					//Classroom capability info update
					$classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName] --;
					//classroom progress info update
					$classroomCapabilityArray[$classroomCounter][$week][$progressPartName] = $courseNameG;
					//Break loop
					break;
				}
			}
		}

		//工艺设计
		if($classTitleInfo == "GY1" || $classTitleInfo == "GY2"){
			vars_checkout($value, "value");
			//Load in classroom schedule
			for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
				if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameGY || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
					//Load data in $classroomScheduleArray
					$classroomScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;
					$classroomScheduleArray[$serial]['WEEK'] = $week;
					$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
					$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "J";
					$classroomScheduleArray[$serial][$coursePartName] = $courseNameGY;
					$classroomScheduleArray[$serial][$classPartName] = $value;
					//Classroom capability info update
					$classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName] --;
					//classroom progress info update
					$classroomCapabilityArray[$classroomCounter][$week][$progressPartName] = $courseNameGY;
					//Break loop
					break;
				}
			}
		}

		//考试
		if($classTitleInfo == "K"){
			vars_checkout($value, "value");
			//Load in classroom schedule
			for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
				if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameK || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
					//Load data in $classroomScheduleArray
					$classroomScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;
					$classroomScheduleArray[$serial]['WEEK'] = $week;
					$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
					$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "J";
					$classroomScheduleArray[$serial][$coursePartName] = $courseNameK;
					$classroomScheduleArray[$serial][$classPartName] = $value;
					//Classroom capability info update
					$classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName] --;
					//classroom progress info update
					$classroomCapabilityArray[$classroomCounter][$week][$progressPartName] = $courseNameK;
					//Break loop
					break;
				}
			}
		}
		
		//工种理论课判断条件
		unset($isTheoryCourse);
		if($classTitleInfo == "Z0" || $classTitleInfo == "H0" || $classTitleInfo == "C0" || $classTitleInfo == "Q0" || $classTitleInfo == "S0" || $classTitleInfo == "T0" || $classTitleInfo == "X0" || $classTitleInfo == "D0"){
			$isTheoryCourse = TRUE;
		}
		//工种理论课
		if($isTheoryCourse){
			///Load in classroom schedule
			//Classroom loop for course: [C]车工 [Q]钳工 [X]铣刨磨
			if($classTitleInfo == "C0" || $classTitleInfo == "Q0" || $classTitleInfo == "X0"){
				for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
					if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameT || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
						//Load data in $classroomScheduleArray
						$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
						$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "J";
						//Break loop
						break;
					}
				}
			}
			//Classroom for course: [T]特加
			if($classTitleInfo == "T0"){
				$classroomCounter = $classroomKeyNameSerialArray['T'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
				$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "T";
			}
			//Classroom for course: [Z]铸造
			if($classTitleInfo == "Z0"){
				$classroomCounter = $classroomKeyNameSerialArray['Z'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
				$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "Z";
			}
			//Classroom for course: [D]锻压
			if($classTitleInfo == "D0"){
				$classroomCounter = $classroomKeyNameSerialArray['D'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
				$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "D";
			}
			//Classroom for course: [H]焊接
			if($classTitleInfo == "H0"){
				$classroomCounter = $classroomKeyNameSerialArray['H'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
				$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "H";
			}
			//Classroom for course: [S]数控
			if($classTitleInfo == "S0"){
				$classroomCounter = $classroomKeyNameSerialArray['S'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
				$classroomScheduleArray[$serial]['CLASSROOM_TYPE'] = "S";
			}
			//Load data in $classroomScheduleArray
			$classroomScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;
			$classroomScheduleArray[$serial]['WEEK'] = $week;
			$classroomScheduleArray[$serial][$coursePartName] = $courseNameT;
			$classroomScheduleArray[$serial][$teacherPartName] = $activeTeacher;
			$classroomScheduleArray[$serial][$classPartName] = $value;
			//Classroom capability info update
			$classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName] --;
			//classroom progress info update
			$classroomCapabilityArray[$classroomCounter][$week][$progressPartName] = $courseNameT;
			//Unset the $classroomCounter.
			unset($classroomCounter);

		}

	//
	}
}

/**
*	$classroomScheduleArray Optimize 
*
*	This code block optimize the public classroom in $classroomScheduleArray. 
*	Move the Train theory course into blank public classroom.
*
*/

//$classroomScheduleArray Structure Describe
//
//$classroomScheduleArray[0]['ID']				= 0;		
//$classroomScheduleArray[0]['SEMESTER_WEEK']	= 0;
//$classroomScheduleArray[0]['WEEK']			= 0;
//$classroomScheduleArray[0]['CLASSROOM_NAME']	= "实216";
//$classroomScheduleArray[0]['COURSE_PART_0']	= "COURSE_0";
//$classroomScheduleArray[0]['TEACHER_PART_0']	= "李文双";
//$classroomScheduleArray[0]['CLASS_PART_0']	= "G.机设09-1";

	$classroomScheduleArray;
	$classroomScheduleArrayCount0 = count($classroomScheduleArray);

//$classroomCapabilityArray Structure Describe
//
//$classroomScheduleArray[CLASSROOM_SERIAL][WEEK][COURSE_PART];
//$classroomCapabilityArray[0][0]['CAPABILITY_COURSE_PART_0'] = 4;
//$classroomCapabilityArray[0][0]['PROGRESS_COURSE_PART_0'] = "COURSE_0";

	$classroomCapabilityArray;

//Public classroom [TYPE "J"] and Theory classroom [TYPE NOT "J"] serial pickup method.
for($i=0;$i<$classroomListArrayCount0;$i++){
	if($classroomListArray[$i]['CLASSROOM_TYPE'] == "J"){
		$publicClassroomSerialArray[] = $i;
	}else{
		$theoryClassroomSerialArray[] = $i;
	}

}
$publicClassroomSerialArrayCount0 = count($publicClassroomSerialArray);
$theoryClassroomSerialArrayCount0 = count($theoryClassroomSerialArray);

//Loop the public classroom [TYPE "J"] and fill the blank classroom
for($courseCounter=0;$courseCounter<$COURSE_IN_A_DAY;$courseCounter++){
	for($weekCounter=0;$weekCounter<$COURSE_DAY_OF_A_WEEK;$weekCounter++){
		$progressCourseKeyName = "PROGRESS_COURSE_PART_".$courseCounter;
		$capabilityPartName = "CAPABILITY_COURSE_PART_".$courseCounter;
		$coursePartName = "COURSE_PART_".$courseCounter;
		$pastTheoryClassroomType = 0;
		$blankPublicClassroomName = 0;
		for($publicClassroomCounter=0;$publicClassroomCounter<$publicClassroomSerialArrayCount0;$publicClassroomCounter++){
			$publicClassroomSerial = $publicClassroomSerialArray[$publicClassroomCounter];//Load classroom serial
			if($classroomCapabilityArray[$publicClassroomSerial][$weekCounter][$progressCourseKeyName] == ""){
				$blankPublicClassroomName = $classroomListArray[$publicClassroomSerial]['CLASSROOM_NAME'];
				$optimizedClassroomCounter = 0;
				for($classroomScheduleCounter=0;$classroomScheduleCounter<$classroomScheduleArrayCount0;$classroomScheduleCounter++){
					if($classroomScheduleArray[$classroomScheduleCounter]['WEEK'] != $weekCounter){
						continue;
					}
					if($classroomScheduleArray[$classroomScheduleCounter]['WEEK'] == $weekCounter				//确定为当天课程
						&& $classroomScheduleArray[$classroomScheduleCounter][$coursePartName] != $courseNameG 	//确定该课程不是概论课
						&& $classroomScheduleArray[$classroomScheduleCounter][$coursePartName] != "" 			//确定是该节课程
						&& $classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'] != "J" 			//确定该课程使用的不是概论课教室
						&& $optimizedClassroomCounter == 0)
					{													
							$pastTheoryClassroomType = $classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'];
							$classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_NAME'] = $blankPublicClassroomName;
							$classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'] = "J";
							$classroomCapabilityArray[$publicClassroomSerial][$weekCounter][$progressCourseKeyName] = $classroomScheduleArray[$classroomScheduleCounter][$coursePartName];
							$optimizedClassroomCounter ++;
					}
					elseif($classroomScheduleArray[$classroomScheduleCounter]['WEEK'] == $weekCounter 
						&& $classroomScheduleArray[$classroomScheduleCounter][$coursePartName] != $courseNameG 
						&& $classroomScheduleArray[$classroomScheduleCounter][$coursePartName] != "" 
						&& $classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'] != "J" 
						&& $optimizedClassroomCounter != 0 
						&& $classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'] == $pastTheoryClassroomType)
					{
						$classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_NAME'] = $blankPublicClassroomName;
						$classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_TYPE'] = "J";
						$optimizedClassroomCounter ++;
					}
				}
			}
		}
	}
}

/**
*	Add theacher into $classroomScheduleArray
*
*	This code block add theacher into $classroomScheduleArray
*
*/

//load the $courseStyleArray
$courseStyleArray = course_style_array_get($courseListArray);
//print "<br />".crc32("实214")."<br />";
for($courseCounter=0;$courseCounter<$COURSE_IN_A_DAY;$courseCounter++){
	for($weekCounter=0;$weekCounter<$COURSE_DAY_OF_A_WEEK;$weekCounter++){
		$coursePartName = "COURSE_PART_".$courseCounter;
		$teacherPartName = "TEACHER_PART_".$courseCounter;
		unset($classroomStatusArray);
/////	
		for($classroomScheduleCounter=0;$classroomScheduleCounter<$classroomScheduleArrayCount0;$classroomScheduleCounter++){
			if($classroomScheduleArray[$classroomScheduleCounter]['WEEK'] != $weekCounter){
				continue;
			}
			if($classroomScheduleArray[$classroomScheduleCounter]['WEEK'] == $weekCounter
				&& $classroomScheduleArray[$classroomScheduleCounter][$coursePartName] != ""){
				//Load course Title info
				$courseKeyName = $classroomScheduleArray[$classroomScheduleCounter][$coursePartName];
				//Load teacher teach averange frequency
				$teachFrequencyAverangeArray = teach_frequency_averange($teacherListArray);
				for($teacherCounter=0;$teacherCounter<$teacherListArrayCount0;$teacherCounter++){
					//laod course style
					$courseStyle = $courseStyleArray[$courseKeyName];
					//Load teacher name 
					$activeTeacherName = $teacherListArray[$teacherCounter]['TEACHER_NAME'];
					//Load process classroom name and CRC value [CRC for non-latin1 encode character]
					$progressClassroomName = $classroomScheduleArray[$classroomScheduleCounter]['CLASSROOM_NAME'];
					$progressClassroomNameWithCRCEncode = "C_".abs(crc32($progressClassroomName)); //Change it to char type. 
					//概论课教师 [G]
					if($teacherListArray[$teacherCounter]['TEACHER_TYPE_INTRO'] == $courseStyle //Teacher type is "G"
					&& $teacherListArray[$teacherCounter]['TEACH_FREQUENCY_INTRO'] <= $teachFrequencyAverangeArray['INTRO'] //Teacher teach frequency <= everange
					&& $classroomStatusArray[$progressClassroomNameWithCRCEncode] == ""	//Selected classroom already have teacher in it
					){
						//Load in array
						$classroomScheduleArray[$classroomScheduleCounter][$teacherPartName] = $activeTeacherName;
						//Load classroom status
						$classroomStatusArray[$progressClassroomNameWithCRCEncode] = $activeTeacherName;
						//Update the teach frequency data
						$teacherListArray[$teacherCounter]['TEACH_FREQUENCY_INTRO'] ++;
						//Break loop
						break;
					}
					//工艺设计教师 [GY]
					if($teacherListArray[$teacherCounter]['TEACHER_TYPE_DESIGN'] == $courseStyle
					&& $teacherListArray[$teacherCounter]['TEACH_FREQUENCY_DESIGN'] <= $teachFrequencyAverangeArray['DESIGN'] //Teacher teach frequency <= everange
					&& $classroomStatusArray[$progressClassroomNameWithCRCEncode] == ""	//Selected classroom already have teacher in it
					){
						//Load in array
						$classroomScheduleArray[$classroomScheduleCounter][$teacherPartName] = $activeTeacherName;
						//Load classroom status
						$classroomStatusArray[$progressClassroomNameWithCRCEncode] = $activeTeacherName;
						//Update the teach frequency data
						$teacherListArray[$teacherCounter]['TEACH_FREQUENCY_DESIGN'] ++;
						//Break loop
						break;
					}
					//监考教师 [K]
					//TODO: EXAM needs two teacher
					if($teacherListArray[$teacherCounter]['TEACHER_TYPE_EXAM'] == $courseStyle
					&& $teacherListArray[$teacherCounter]['TEACH_FREQUENCY_EXAM'] <= $teachFrequencyAverangeArray['EXAM'] //Teacher teach frequency <= everange
					&& $classroomStatusArray[$progressClassroomNameWithCRCEncode] == ""	//Selected classroom already have teacher in it
					){
						//Load in array
						$classroomScheduleArray[$classroomScheduleCounter][$teacherPartName] = $activeTeacherName;
						//Load classroom status
						$classroomStatusArray[$progressClassroomNameWithCRCEncode] = $activeTeacherName;
						//Update the teach frequency data
						$teacherListArray[$teacherCounter]['TEACH_FREQUENCY_EXAM'] ++;
						//Break loop
						break;
					}
					//理论课教师 [C, X, S, H, T, Z, Q, D]
					//TODO:理论课教师参加概论课教学问题
					if($teacherListArray[$teacherCounter]['TEACHER_TYPE_TRAIN'] == $courseStyle
					&& $teacherListArray[$teacherCounter]['TEACH_FREQUENCY'] <= $teachFrequencyAverangeArray['TRAIN'] 	//Teacher teach frequency <= everange
					&& $classroomStatusArray[$progressClassroomNameWithCRCEncode] == ""						//Selected classroom already have teacher in it){
					){
						//Load in array
						$classroomScheduleArray[$classroomScheduleCounter][$teacherPartName] = $activeTeacherName;
						//Load classroom status
						$classroomStatusArray[$progressClassroomNameWithCRCEncode] = $activeTeacherName;
						//Update the teach frequency data
						$teacherListArray[$teacherCounter]['TEACH_FREQUENCY_TRAIN'] ++;
						//Break loop
						break;
					}
					
				}
			}

		}

/////
	}
}

//Write $teacherListArray into database
$teacherListArrayCount0 = count($teacherListArray);
for($i=0;$i<$teacherListArrayCount0;$i++){
	$targetId = $teacherListArray[$i]['ID'];
	table_data_change($TEACHER_TABLE_NAME, $TEACHER_TABLE_KEY_NAMES_ARRAY, $targetId, $teacherListArray[$i]);
}

//Write $classroomScheduleArray into database
$classroomScheduleArrayCount0 = count($classroomScheduleArray);
vars_checkout($classroomScheduleArrayCount0, "classroomScheduleArrayCount0");
for($i=0;$i<$classroomScheduleArrayCount0;$i++){
	table_data_add($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $classroomScheduleArray[$i]);
}

}//Bracket for reschedule determinative syntax

//Load in session for global table name load.
$_SESSION['targetTableName'] = $CLASSROOM_SCHEDULE_TABLE_NAME;
$_SESSION['targetPageSwitch'] = $PAGE_SWITCH;

//Load the course key-name union array
$courseKeyNameUnionArray = course_key_name_union_array_get($courseListArray);
//Reunion the classroom array for classroom schedule output
$classroomScheduleArrayReunion = classroom_schedule_array_reunion($classroomScheduleArray, $courseKeyNameUnionArray);

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

		week_select_output($CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $SEMESTER_WEEK_SET);//Temporary views output
		table_info_output($CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $classroomScheduleArray);//Temporary views output
		reschedule_button_output();
		classroom_schedule_output($classroomScheduleArrayReunion, $classroomListArray);
		editable_grid_output();//Editable grid output
		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print javascript blocks.
javascript_include_output();
print_conf_scripts_for_editable_grid($EDITABLE_GRID_UPDATE_PAGE_NAME, $EDITABLE_GRID_LOADDATA_PAGE_NAME, $CLASSROOM_SCHEDULE_TABLE_NAME);
javascript_window_onload_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>