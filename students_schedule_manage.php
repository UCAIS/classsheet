<?php
/**
*	Students Schedule Manage Page
*	
*	Serial:		120528
*	by:			M.Karminski
*
*	This page loop the class and pickup the course of class to form a sheet.
*
*
*/
//Start session
session_start();


//Page switch
$PAGE_SWITCH = $STUDENTS_SCHEDULE_PAGE_SWITCH = 12;

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

//Load $classListArray
$CLASS_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASS_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASS_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($CLASS_TABLE_NAME);
$classListArray = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY);
//Load $totalScheduleArray
$TOTAL_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $TOTAL_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY = table_key_names_array_get($TOTAL_SCHEDULE_TABLE_NAME);
//$totalScheduleArray = table_data_query($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");

//Load $classroomScheduleArray

$CLASSROOM_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $CLASSROOM_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$CLASSROOM_SCHEDULE_PAGE_SWITCH];
//$classroomScheduleArray = table_data_query($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $SEMESTER_WEEK_SET");

//Load $studentsScheduleArray
$STUDENTS_SCHEDULE_TABLE_NAME = table_name_form($PAGE_INFO_ARRAY, $STUDENTS_SCHEDULE_PAGE_SWITCH, $semesterListArray, $semesterTargetArray);
$STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$STUDENTS_SCHEDULE_PAGE_SWITCH];
$STUDENTS_SCHEDULE_TABLE_KEY_TYPES_ARRAY = $TABLE_KEY_TYPES_ARRAY[$STUDENTS_SCHEDULE_PAGE_SWITCH];

//Database Query menthod
if($_POST['classListQuery']){
	$studentsScheduleArray = table_data_query($STUDENTS_TABLE_NAME, $STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY);
}
//Database Delete method
if($_POST['classListDelete']){
	
}

//Create the dataabse table.
database_table_create($STUDENTS_SCHEDULE_TABLE_NAME, $STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $STUDENTS_SCHEDULE_TABLE_KEY_TYPES_ARRAY);

//Reschedule determinative syntax
if($_POST['RESCHEDULE']){

//Load target class name.
$classTargetArray = $_POST['classList'];
$targetClassName = $classListArray[$classTargetArray]['CLASS_NAME'];

//Load $courseWeekArray
//$courseWeekArray structure describe
//$courseWeekArray[0] = 0;
//				  [1] = 2;
//...

$targetClassListArrayTemp = table_data_query($CLASS_TABLE_NAME, $CLASS_TABLE_KEY_NAMES_ARRAY, "CLASS_NAME = '$targetClassName'");
$targetClassListArrayTempCount0 = count($targetClassListArrayTemp);
for($i=0;$i<$targetClassListArrayTempCount0;$i++){
	if($targetClassListArrayTemp[$i]['CLASS_TYPE']){
		$targetClassListArray = $targetClassListArrayTemp[$i];
		break;
	}
}
for($i=0;$i<$SEMESTER_WEEK_NUMBER;$i++){
	$weekKeyName = "WEEK_".$i;
	if($targetClassListArray[$weekKeyName] == 1){
		$courseWeekArray[] = $i;
	}
}
$courseWeekArrayCount0 = count($courseWeekArray);

//Get class all course from TOTAL_SCHEDULE.
//$classAllCourseArray structure Describe
//$classAllCourseArray[0][0]['ID']	= 0;
//							['SEMESTER_WEEK'] = 0;
//							['WEEK'] = 0;
//							['COURSE_0_0'] = "G.机设09-1";
//							['COURSE_0_1'] = "";
//							['COURSE_0_2'] = "";
//							['COURSE_0_3'] = "";

for($i=0;$i<$courseWeekArrayCount0;$i++){
	$semesterWeekNumber = $courseWeekArray[$i];
	$classAllCourseArray[$i] = table_data_query($TOTAL_SCHEDULE_TABLE_NAME, $TOTAL_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $semesterWeekNumber");
	$classAllCourseArrayCount1[$i] = count($classAllCourseArray[$i]);
}

//Pick up the target class and load in $studentsScheduleArray.
$studentsScheduleSerial = 0;
$courseCounter = 0;
for($weekCounter=0;$weekCounter<$courseWeekArrayCount0;$weekCounter++){
	//vars_checkout($weekCounter, "weekCounter");
	for($allCourseCounter=0;$allCourseCounter<$classAllCourseArrayCount1[$weekCounter];$allCourseCounter++){
		//vars_checkout($allCourseCounter, "allCourseCounter");
		foreach($classAllCourseArray[$weekCounter][$allCourseCounter] as $key => $value){
			$explodeValue = explode(".", $value);
			$classTakeCourseTitleInfo = $explodeValue[0];
			if(!$explodeValue[1]){
				continue;
			}
			$className = $explodeValue[1];
			//vars_checkout($className, "className");
			if($className == $targetClassName){
				$explodeKey = explode("_", $key);
				$courseKeyName = "COURSE_".$explodeKey[1];
				//vars_checkout($courseKeyName, "courseKeyName");
				$coursePartKeyName = "COURSE_PART_".$explodeKey[2];
				//vars_checkout($coursePartKeyName, "coursePartKeyName");
				

				//Load in studentsScheduleArray
				if($courseCounter >= $COURSE_IN_A_DAY){
					$studentsScheduleSerial++;
					$courseCounter = 0;
				}
				$studentsScheduleArray[$studentsScheduleSerial]['CLASS_NAME'] = $targetClassName;
				$studentsScheduleArray[$studentsScheduleSerial]['SEMESTER_WEEK'] = $classAllCourseArray[$weekCounter][$allCourseCounter]['SEMESTER_WEEK'];
				$studentsScheduleArray[$studentsScheduleSerial]['WEEK'] = $classAllCourseArray[$weekCounter][$allCourseCounter]['WEEK'];
				$studentsScheduleArray[$studentsScheduleSerial][$coursePartKeyName] = $courseKeyName.".".$classTakeCourseTitleInfo;
				$courseCounter ++;
				//For COURSE_0 [WARING: hardcode]
				if($courseKeyName == "COURSE_0"){
					$courseCounter --;
					$studentsScheduleSerial++;
				}
			}
		}
	}
}
//Count the $studentsScheduleArray
$studentsScheduleArrayCount0 = count($studentsScheduleArray);
//var_dump($studentsScheduleArray);

//Follow the TOTAL_SCHEDULE data, Get CLASSROOM_NAME and TEACHER_NAME from CLASSROOM_TABLE.
//Get class all classroom info from CLASSROOM_SCHEDULE
//$classAllClassroomArray Structure Describe
//$classAllClassroomArray[0][0]['ID'] = 0;
//							   ['SEMESTER_WEEK'] = 0;
//							   ['WEEK'] = 0;
//							   ['CLASSROOM_NAME'] = "实124";
//							   ['CLASSROOM_TYPE'] = "J";
//							   ['COURSE_PART_0'] = "COURSE_0";
//...
//							   ['TEACHER_PART_0'] = "李文双";
//...
//							   ['CLASS_PART_0'] = "G.机设09-1";
//...

for($i=0;$i<$courseWeekArrayCount0;$i++){
	$semesterWeekNumber = $courseWeekArray[$i];
	$classAllClassroomArray[$i] = table_data_query($CLASSROOM_SCHEDULE_TABLE_NAME, $CLASSROOM_SCHEDULE_TABLE_KEY_NAMES_ARRAY, "SEMESTER_WEEK = $semesterWeekNumber");
	$classAllClassroomArrayCount1[$i] = count($classAllClassroomArray[$i]);
}

//Pick up the target classroom and load in $studentsScheduleArray.
for($weekCounter=0;$weekCounter<$courseWeekArrayCount0;$weekCounter++){
	for($allClassroomCounter=0;$allClassroomCounter<$classAllClassroomArrayCount1[$weekCounter];$allClassroomCounter++){
		$progressClassroomName = $classAllClassroomArray[$weekCounter][$allClassroomCounter]['CLASSROOM_NAME'];
		foreach($classAllClassroomArray[$weekCounter][$allClassroomCounter] as $classroomKey => $classroomValue){
			//Ignore the useless array key value
			if($classroomKey == "CLASS_PART_0" || $classroomKey == "CLASS_PART_1" || $classroomKey == "CLASS_PART_2" || $classroomKey == "CLASS_PART_3"){

			}else{
				continue;
			}
			//explode the class info
			$explodeClassroomKey = explode("_", $classroomKey);
			$courseKeyNameInclassroomArray = "COURSE_PART_".$explodeClassroomKey[2];
			$teacherNameInCLassroomArray = "TEACHER_PART_".$explodeClassroomKey[2];
			$explodeClassroomValue = explode(".", $classroomValue);
			$className = $explodeClassroomValue[1];
			$classTakeCourseTitleInfo = $explodeClassroomValue[0];
			//Loop the $studentsScheduleArray
			for($studentsScheduleCounter=0;$studentsScheduleCounter<$studentsScheduleArrayCount0;$studentsScheduleCounter++){
				foreach($studentsScheduleArray[$studentsScheduleCounter] as $studentsKey => $studentsValue){
					//Ignore the useless array key value
					if($studentsKey == 'ID' || $studentsKey == 'SEMESTER_WEEK' || $studentsKey == 'CLASS_NAME' || $studentsKey == 'WEEK'){
						continue;
					}
					$explodeStudentsValue = explode(".", $studentsValue);
					$classInClassroomScheduleTitleInfo = $explodeStudentsValue[1];
					$classInClassroomScheduleCourseKeyName = $explodeStudentsValue[0];
					//Pickup the target info of target class
					if($className == $targetClassName && $classTakeCourseTitleInfo == $classInClassroomScheduleTitleInfo ){
						$explodeStudentsKey = explode("_", $studentsKey);
						$coursePartKeyNameInStudentsSchedule = "COURSE_PART_".$explodeStudentsKey[2];
						
						//Load teacher name
						for($allClassroomCounterForTeacher=0;$allClassroomCounterForTeacher<$classAllClassroomArrayCount1[$weekCounter];$allClassroomCounterForTeacher++){
							$courseInClassroomArray = $classAllClassroomArray[$weekCounter][$allClassroomCounterForTeacher][$courseKeyNameInclassroomArray];
							$progressTeacherName = $classAllClassroomArray[$weekCounter][$allClassroomCounterForTeacher][$teacherNameInCLassroomArray];
							$targetClassroomName = $classAllClassroomArray[$weekCounter][$allClassroomCounterForTeacher]['CLASSROOM_NAME'];
							if($courseInClassroomArray == $classInClassroomScheduleCourseKeyName && $progressTeacherName && $progressClassroomName == $targetClassroomName){
								$targetTeacherName = $progressTeacherName;
							}
						}
						//Load in $sudetnsScheduleArray.
						$studentsScheduleArray[$studentsScheduleCounter][$coursePartKeyNameInStudentsSchedule] .= ".".$progressClassroomName.".".$targetTeacherName;
					}
				}
			}
				
		}
	}
}


//Update $studentsScheduleArray. 
for($i=0;$i<$studentsScheduleCounter;$i++){
	table_data_add($STUDENTS_SCHEDULE_TABLE_NAME, $STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $studentsScheduleArray[$i]);
}

}//Bracket for reschedule determinative syntax

//Load in session for global table name load.
$_SESSION['targetTableName'] = $STUDENTS_SCHEDULE_TABLE_NAME;
$_SESSION['targetPageSwitch'] = $PAGE_SWITCH;

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

		class_list_output($classListArray, $classTargetArray, $PAGE_SWITCH);
		table_info_output($STUDENTS_SCHEDULE_TABLE_KEY_NAMES_ARRAY, $studentsScheduleArray);//Temporary views output
		reschedule_button_output();
		editable_grid_output();//Editable grid output

		div_end_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print javascript blocks.
javascript_include_output();
print_conf_scripts_for_editable_grid($EDITABLE_GRID_UPDATE_PAGE_NAME, $EDITABLE_GRID_LOADDATA_PAGE_NAME, $STUDENTS_SCHEDULE_TABLE_NAME);
javascript_window_onload_output();

//Print HTML end
body_end_output();
html_end_output();



//Fin.
?>