<?php
/**
*	Global functions and vars page
*	
*	Serial:		120422
*	by:			M.Karminski
*
*/

//TODO: Change the HTML form tag action
//TODO: Add the user input filter
//------ -[ Page  Informatin Vars ]-  ------
//
//[ WARING:All VALUES ARE USED IN GLOBAL, CHANGE IT CAREFULLY ]
//
//$PAGE_INFO_ARRAY			For global page infromations
//$TABLE_KEY_NAMES_ARRAY 	For database table key names and vars key names
//$TABLE_KEY_TYPES_ARRAY 	For database table key types, was only used in table creating 

GLOBAL $PAGE_INFO_ARRAY, $TABLE_KEY_NAMES_ARRAY, $TABLE_KEY_TYPES_ARRAY;

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
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_CAPABILITY']  = "COURSE_CAPABILITY";
$TABLE_KEY_NAMES_ARRAY[3]['COURSE_STYLE']		= "COURSE_STYLE";
$TABLE_KEY_TYPES_ARRAY[3]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[3]['COURSE_NAME']		= "varchar(15)";
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
$TABLE_KEY_TYPES_ARRAY[4]['ID']					= "int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_TYPE']			= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_NAME']			= "varchar(15)";
$TABLE_KEY_TYPES_ARRAY[4]['CLASS_CAPABILITY']	= "int";
//$TABLE_KEY_TYPES_ARRAY[4]['WEEK']				= "varchar(15)";	//The type "varchar(15)" is requird

$PAGE_INFO_ARRAY[5]['PAGE_NAME'] 				= "Class Manage";
$PAGE_INFO_ARRAY[5]['PAGE_NAME_IN_CHINESE'] 	= "班级管理";
$PAGE_INFO_ARRAY[5]['FILE_NAME']				= "class_manage.php";
$PAGE_INFO_ARRAY[5]['TABLE_NAME']				= "CLASS";

$PAGE_INFO_ARRAY[12]['PAGE_NAME'] 				= "Course and Course type Import";
$PAGE_INFO_ARRAY[12]['PAGE_NAME_IN_CHINESE'] 	= "课程及模块导入";
$PAGE_INFO_ARRAY[12]['FILE_NAME']				= "course_import.php";





/* These function not in use
//------  -[ array_Counter Function ]-  ------
//This function count the array and return the item numbers of array
//Notes:
//The $dimension optionally value is only 1 or 2.
function array_Counter($inputArray, $dimension){
	if($dimension == 1){
		$inputArrayCount0 = count($inputArray);
		return $inputArrayCount0;
	}elseif ($dimension == 2){
		$inputArrayCount0 = count($inputArray);
		for($i=0;$i<$inputArrayCount0;$i++){
			$inputArrayCount1[$i] = count($inputArray[$i]);
		}
		return $inputArrayCount1;
	}else{
		return 0;
	}

}



//------  -[ post_auto_fill Function ]-  ------
function post_auto_fill($post_value){
	$post_value;	//Target to fill

	if($post_value == ""){
		$post_value = "auto_filled";
	}
	print $post_value;
	return $post_value;
} 
*/

//------  -[ table_key_names_auto_fill Function ]-  ------
//This function auto fill table key names by input char or char array.
function table_key_names_auto_fill($target_array, $filled_keywords, $filled_length, $filled_position = 1){
	$target_array;			//Target array to fill
	$filled_keywords;		//Define chars to fill array keys and array values 
	$filled_length;			//Item numbers to fill
	$filled_position;		//0,Head 1,Tail[DEFAULT]

	$arrayKeyNamesFormed;	//To storage the formed array key names
	if(is_array($filled_keywords)){
		for($i=0;$i<$filled_length;$i++){
			$arrayKeyNamesFormed = $filled_keywords[$i];
			$target_array[$arrayKeyNamesFormed] = $arrayKeyNamesFormed;
		}
	}elseif(is_string($filled_keywords)){
		if($filled_position == 1){
			for($i=0;$i<$filled_length;$i++){
				$arrayKeyNamesFormed = $filled_keywords."_".$i;
				$target_array[$arrayKeyNamesFormed] = $arrayKeyNamesFormed;
			}
		}elseif($filled_position == 0){
			for($i=0;$i<$filled_length;$i++){
				$arrayKeyNamesFormed = $i."_".$filled_keywords;
				$target_array[$arrayKeyNamesFormed] = $arrayKeyNamesFormed;
			}
		}
	}
	return $target_array;
}

//------  -[ table_key_types_auto_fill Function ]-  ------
//This fucntion return a key-values array for storage table key types.
function table_key_types_auto_fill($target_array, $filled_keywords, $filled_length = 0, $filled_type, $filled_position = 1){
	$target_array;			//Target array to fill
	$filled_keywords;		//Define chars to fill array keys and array values 
	$filled_length;			//Item numbers to fill
	$filled_type;			//Key types to fill
	$filled_position;		//0,Head 1,Tail[DEFAULT]

	$arrayKeyNamesFormed;	//To storage the formed array key names
	if($filled_length == 0){
		$filled_length = count($filled_keywords);
	}
	if(is_array($filled_keywords)){
		for($i=0;$i<$filled_length;$i++){
			$arrayKeyNamesFormed = $filled_keywords[$i];
			$target_array[$arrayKeyNamesFormed] = $filled_type;
		}
	}elseif(is_string($filled_keywords)){
		if($filled_position == 1){
			for($i=0;$i<$filled_length;$i++){
				$arrayKeyNamesFormed = $filled_keywords."_".$i;
				$target_array[$arrayKeyNamesFormed] = $filled_type;
			}
		}elseif($filled_position == 0){
			for($i=0;$i<$filled_length;$i++){
				$arrayKeyNamesFormed = $i."_".$filled_keywords;
				$target_array[$arrayKeyNamesFormed] = $filled_type;
			}
		}
	}
	return $target_array;
}

//------  -[ uploaded_file_data_load Function ]-  ------
//This function load cvs file contents into an array.
function uploaded_file_data_load(){
	if(is_uploaded_file($_FILES['uploadFiles']['tmp_name'])){
		$uploadedFileTempName = $_FILES['uploadFiles']['tmp_name'];
		$uploadedFiles = file($uploadedFileTempName);
		$loopCounter = 0;
		foreach($uploadedFiles as $fileContents){
			$importDataArray[$loopCounter] = explode(",", $fileContents);
			$loopCounter ++;
		}
	}
	return $importDataArray;
}

//------  -[ import_data_form Function ]-  ------
//Convert an array to key-values structure.
function import_data_form($import_data_array){
	$import_data_array;			//Target array

	$loopCounter = 0;
	$import_data_array_count1 = count($import_data_array[0]);
	foreach($import_data_array[0] as $value){
		for($i=0;$i<$import_data_array_count1;$i++){
			$formedArray[$i][$value] = "'".$import_data_array[$i+1][$loopCounter]."'";
		}
		$loopCounter ++; 
	}
	return $formedArray;
}

//------  -[ array_picker Function ]-  ------
//This fucntion return an import array's first line.
function array_picker($import_data_array, $target_line = 0){
	$import_data_array;			//
	$target_line;				//

	foreach($import_data_array[$target_line] as $value){
		$formedArray[] = $value; 
	}
	return $formedArray;
}

//------  -[ array_partitioner Function ]-  ------
//This function cut the array and return the part what your need.
//Notes:
//The $return_location optionally value is only 0 or 1, "0" means HEAD and "1" means TAIL. 
function array_partitioner($input_array, $return_location, $drop_length){
	$input_array;				//Target array.
	$return_location;			//0->HEAD;1->TAIL.
	$drop_length;				//The length of drop part. 

	$inputArrayCount0 = count($input_array);
	$loopLength = $inputArrayCount0 - $drop_length;
	if($return_location == 0){
		for($i=0;$i<$loopLength;$i++){
			$outputArray[] = $input_array[$i];
		}
		return $outputArray; 
	}elseif($return_location == 1){
		for($i=$dropLength;$i<$inputArrayCount0;$i++){
			$outputArray[] = $input_array[$i];
		}
		return $outputArray;
	}else{
		return 0;
	}
}

//------  -[ table_name_form Function ]-  ------
//This function return a formed table name.
function table_name_form($PAGE_INFO_ARRAY, $PAGE_SWITCH, $semester_list_array, $semester_target_array){
	$PAGE_INFO_ARRAY;			//
	$PAGE_SWITCH;				//
	$semester_list_array;		//
	$semester_target_array;		//

	$tableName = $PAGE_INFO_ARRAY[$PAGE_SWITCH]['TABLE_NAME'];
	$tableName .= "_".$semester_list_array[$semester_target_array]['SEMESTER']."_".$semester_list_array[$semester_target_array]['PART'];
	return $tableName;
}

//------  -[ array_add_key Function ]-  ------
//Thsi function add an array keys-values pair.
function array_add_key($target_array, $key_names, $key_values){
	$target_array;				//
	$key_names;					//
	$key_values;				//

	if(is_array($key_names)){
		$keyNamesCount0 = count($key_names);
		for($i=0;$i<$keyNamesCount0;$i++){
			$target_array[$key_names[$i]] = $key_values[$i];
		}
	}elseif(is_string($key_names)){
		$target_array[$key_names] = $key_values;
	}
	return $target_array;
}
//Fin.
?>