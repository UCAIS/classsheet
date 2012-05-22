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

//TODO: Optimize the classroom schedule method.
//TODO: Rrewrite the comment.

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
$SEMESTER_WEEK_SET = 1;

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
//TODO: $classroomScheduleArray = table_data_query($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");;

//$courseListArray Structure Describe
//
//$courseListArray[0]['COURSE_NAME'] 				= "概论课";
//$courseListArray[0]['COURSE_STYLE']				= "G";

	$courseListArray;
	$courseListArrayCount0 = count($courseListArray);
	//Load the special course name
	for($i=0;$i<$courseListArrayCount0;$i++){
		if($courseListArray[$i]['COURSE_STYLE'] == "G"){
			$courseNameG = $courseListArray[$i]['COURSE_NAME'];
		}
		if($courseListArray[$i]['COURSE_STYLE'] == "GY"){
			$courseNameGY = $courseListArray[$i]['COURSE_NAME'];
		}
		if($courseListArray[$i]['COURSE_STYLE'] == "K"){
			$courseNameK = $courseListArray[$i]['COURSE_NAME'];
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


////Classroom Schedule Arrange Start

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
		$classroomScheduleArrayCount0 = count($classroomScheduleArray);
		$serial = $classroomScheduleArrayCount0;
		$serial = serial_counter($classroomScheduleArray, $serial, $classPartName);

		//概论课
		if($classTitleInfo == "G" && $underlineExplodeArray[1] == 0){ //For "COURSE_0". [WARING:Hardcode]
			//.vars_checkout($value, "value");
			//Load the teacher averange TEACH_FREQUENCY
			$teachFrequencyAverange = teach_frequency_averange($teacherListArray);
			vars_checkout($teachFrequencyAverange, "teachFrequencyAverange");
			//Load teacher info
			$activeTeacher = "";
			for($teacherCounter=0;$teacherCounter<$teacherListArrayCount0;$teacherCounter++){
				if($teacherListArray[$teacherCounter]['TEACHER_TYPE_INTRO'] == "G" && $teacherListArray[$teacherCounter]['TEACH_FREQUENCY']<=$teachFrequencyAverange){
					$activeTeacher = $teacherListArray[$teacherCounter]['TEACHER_NAME'];
					//Auto increase TEACH_FREQUENCY
					$teacherListArray[$teacherCounter]['TEACH_FREQUENCY'] ++;
					//Update the teacher teach status [DISCARD]
					//	$teacherStatusArray[$teacherCounter]['WEEK'] = "";
					//	$teacherStatusArray[$teacherCounter]['COURSE_PART'] = "";
					//Break loop
					break;
				}
			}
			//Load in classroom schedule
			for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
				if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameG || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
					//Load data in $classroomScheduleArray
					$classroomScheduleArray[$serial]['SEMESTER_WEEK'] = $SEMESTER_WEEK_SET;
					$classroomScheduleArray[$serial]['WEEK'] = $week;
					$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
					$classroomScheduleArray[$serial][$coursePartName] = $courseNameG;
					$classroomScheduleArray[$serial][$teacherPartName] = $activeTeacher;
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
		}

		//考试
		if($classTitleInfo == "K"){
			vars_checkout($value, "value");
		}
		
		//工种理论课判断条件
		unset($isTheoryCourse);
		if($classTitleInfo == "Z0" || $classTitleInfo == "H0" || $classTitleInfo == "C0" || $classTitleInfo == "Q0" || $classTitleInfo == "S0" || $classTitleInfo == "T0" || $classTitleInfo == "X0" || $classTitleInfo == "D0"){
			$isTheoryCourse = TRUE;
		}
		//工种理论课
		if($isTheoryCourse){
			vars_checkout($value, "value");
			/*
			//Load the teacher averange TEACH_FREQUENCY
			$teachFrequencyAverange = teach_frequency_averange($teacherListArray);
			vars_checkout($teachFrequencyAverange, "teachFrequencyAverange");
			//Load teacher info
			$activeTeacher = "";
			for($teacherCounter=0;$teacherCounter<$teacherListArrayCount0;$teacherCounter++){
				if($teacherListArray[$teacherCounter]['TEACHER_TYPE_INTRO'] == "T0" && $teacherListArray[$teacherCounter]['TEACH_FREQUENCY']<=$teachFrequencyAverange){
					$activeTeacher = $teacherListArray[$teacherCounter]['TEACHER_NAME'];
					//Auto increase TEACH_FREQUENCY
					$teacherListArray[$teacherCounter]['TEACH_FREQUENCY'] ++;
					//Break loop
					break;
				}
			}*/

			///Load in classroom schedule
			//Classroom loop for course: [C]车工 [Q]钳工 [X]铣刨磨
			if($classTitleInfo == "C0" || $classTitleInfo == "Q0" || $classTitleInfo == "X0"){
				for($classroomCounter=0;$classroomCounter<$classroomListArrayCount0;$classroomCounter++){
					if($classroomListArray[$classroomCounter]['CLASSROOM_TYPE'] == "J" && $classroomCapabilityArray[$classroomCounter][$week][$capabilityPartName]>0 && ($classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == $courseNameT || $classroomCapabilityArray[$classroomCounter][$week][$progressPartName] == "")){
						//Load data in $classroomScheduleArray
						$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
						//Break loop
						break;
					}
				}
			}
			//Classroom for course: [T]特加
			if($classTitleInfo == "T0"){
				$classroomCounter = $classroomKeyNameSerialArray['T'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
			}
			//Classroom for course: [Z]铸造
			if($classTitleInfo == "Z0"){
				$classroomCounter = $classroomKeyNameSerialArray['Z'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
			}
			//Classroom for course: [D]锻压
			if($classTitleInfo == "D0"){
				$classroomCounter = $classroomKeyNameSerialArray['D'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
			}
			//Classroom for course: [H]焊接
			if($classTitleInfo == "H0"){
				$classroomCounter = $classroomKeyNameSerialArray['H'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
			}
			//Classroom for course: [S]数控
			if($classTitleInfo == "S0"){
				$classroomCounter = $classroomKeyNameSerialArray['S'];// Load the classroom serial number.
				$classroomScheduleArray[$serial]['CLASSROOM_NAME'] = $classroomListArray[$classroomCounter]['CLASSROOM_NAME'];
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

////$classroomScheduleArray Optimize
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

for($classroomScheduleCounter=0;$classroomScheduleCounter<$classroomScheduleArrayCount0;$classroomScheduleCounter++){
	
}





//TODO: Upload $teacherListArray TEACH_FREQUENCY data.


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
		table_info_output($CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $classroomScheduleArray);//Temporary views output

		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>