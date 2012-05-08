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
		$loopCounter = 0;
		foreach($input_array as $value){
			if($loopCounter<$loopLength){
				$outputArray[$value] = $input_array[$value];
			}
			$loopCounter++;
		}
		return $outputArray; 
	}elseif($return_location == 1){
		$loopCounter = $drop_length;
		foreach($input_array as $value){
			if($loopCounter<$inputArrayCount0){
				$outputArray[$value] = $input_array[$value];
			}
			$loopCounter++;
		}
		return $outputArray;
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

//------  -[ vars_checkout Function ]- ------
function vars_checkout($vars, $vars_name){
	print "<br />\$".$vars_name." = ".$vars;
}

//------  -[ table_key_names_auto_fill Function ]-  ------
//This function auto fill table key names by input char or char array.
function table_key_names_auto_fill($target_array, $filled_keywords, $filled_length = 0, $filled_position = 1){
	$target_array;			//Target array to fill
	$filled_keywords;		//Define chars to fill array keys and array values 
	$filled_length;			//Item numbers to fill
	$filled_position;		//0,Head 1,Tail[DEFAULT]

	$arrayKeyNamesFormed;	//To storage the formed array key names
	if($filled_length == 0){
		$filled_length = count($filled_keywords);
	}
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
	$import_data_array_count1 = count($import_data_array);
	foreach($import_data_array[0] as $value){
		for($i=0;$i<$import_data_array_count1;$i++){
			$formedArray[$i][$value] = "'".$import_data_array[$i+1][$loopCounter]."'";
		}
		$loopCounter ++; 
	}
	return $formedArray;
}

//------  -[ array_picker Function ]-  ------
//This fucntion return an import array's target line.
function array_picker($import_data_array, $target_line = 0){
	$import_data_array;			//
	$target_line;				//

	foreach($import_data_array[$target_line] as $value){
		$formedArray[] = $value; 
	}
	return $formedArray;
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

//------  -[ array_key_insert Function ]-  ------
//This function add an array keys-values pair.
function array_key_insert($target_array, $key_names, $key_values){
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

//------  -[ data_picker Function ]-  ------
//This function pick data from a mixed data
function data_picker($syntax, $data){
	$outputData = explode($syntax, $data);
	return $outputData[1];
}

////  ////  ////  ////  ////  [ TOTAL SCHEDULE FUNCTIONS ]  ////  ////  ////  ////  ////

//------  -[ class_array_appoint Function ]-  ------
//This function load queue of CLASS_NAME & CLASS_TYPE in $appointedClassArray which has been appointed the week of semester
function class_array_appoint($class_list_array, $course_list_array, $semester_week_set){
	$class_list_array;			//
	$course_list_array;			//
	$semester_week_set;			//

	$classListArrayCount0 = count($class_list_array);
	$courseListArrayCount0 = count($course_list_array);
	$weekName = "WEEK_".$semester_week_set;
	$loopCounter = 0;
	for($i=0;$i<$classListArrayCount0;$i++){
		if($class_list_array[$i][$weekName] == 1){
			$appointedClassArray[$loopCounter]['CLASS_NAME'] = $class_list_array[$i]['CLASS_NAME'];
			$appointedClassArray[$loopCounter]['CLASS_TYPE'] = $class_list_array[$i]['CLASS_TYPE'];
			$appointedClassArray[$loopCounter]['ID'] = $class_list_array[$i]['ID'];
			for($j=0;$j<$courseListArrayCount0;$j++){
				$courseName = "COURSE_".$j;
				$appointedClassArray[$loopCounter][$courseName] = $class_list_array[$i][$courseName];
			}
			$loopCounter ++;
		}
	}
	return $appointedClassArray;
}

//------  -[ course_capability_array_get Function ]-  ------
//This function get a course capability left array
//[WARING]Hardcode.
function course_capability_array_get($course_list_array, $COURSE_DAY_OF_A_WEEK, $COURSE_IN_A_DAY){
	$course_list_array;			//Course information array
	$COURSE_DAY_OF_A_WEEK;		//
	$COURSE_IN_A_DAY;			//

	$courseListArrayCount0 = count($course_list_array);
	for($i=0;$i<$COURSE_DAY_OF_A_WEEK;$i++){
		for($j=0;$j<$courseListArrayCount0;$j++){
			for($k=0;$k<$COURSE_IN_A_DAY;$k++){
				$courseKeyName = "COURSE_".$j."_".$k;
				$courseCapabilityArray[$i][$courseKeyName] = $course_list_array[$j]['COURSE_CAPABILITY'];
			}
		}
	}
	return $courseCapabilityArray;
}

//------  -[ course_period_left_get Function ]-  ------
//This function return appointed class array left course period quantity
function course_period_left_get($appointed_class_array, $course_list_array, $return_type){
	$appointed_class_array;		//
	$course_list_array;			//
	$return_type;				//"0" return all train course, "1" return all course

	$courseListArrayCount0 = count($course_list_array);
	for($i=0;$i<$courseListArrayCount0;$i++){
		$courseKeyName = "COURSE_".$i;
		$courseStyle = $course_list_array[$i]['COURSE_STYLE'];
		if(($courseStyle == "D" || $courseStyle == "E") && $return_type == 0){
			continue;
		}
		if($courseStyle == "E" && $return_type == 1){
			continue;
		}
		$coursePeriodLeft += $appointed_class_array[$courseKeyName];
	}
	return $coursePeriodLeft;
}

//------  -[ serial_counter Function ]-  ------
//
function serial_counter($total_schedule_array, $serial, $key){
	$total_schedule_array;		//
	$serial;					//
	$key;						//

	if($total_schedule_array[$serial][$key] != "" || $total_schedule_array[$serial][$key] != 0){
		$serial ++;
		$serial = serial_counter($total_schedule_array, $serial, $key);
	}
	return $serial;
}

////  ////  ////  ////  ////  [ TOTAL SCHEDULE FUNCTIONS ]  ////  ////  ////  ////  /////



//Fin.
?>