<?php
/**
*	Global functions and vars page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/

//------ -[ Page  Informatin Vars ]-  ------
//
//[ WARING:All VALUES ARE USED IN GLOBAL, CHANGE IT CAREFULLY ]
//
//$PAGE_INFO_ARRAY			For global page infromations
//$TABLE_KEY_NAMES_ARRAY 	For database table key names and vars key names
//$TABLE_KEY_TYPES_ARRAY 	For database table key types, was only used in table creating 

global $PAGE_INFO_ARRAY, $TABLE_KEY_NAMES_ARRAY, $TABLE_KEY_TYPES_ARRAY;

$PAGE_INFO_ARRAY[0][0] = "index";				
$PAGE_INFO_ARRAY[0][1] = "首页";		

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

$PAGE_TITLE_INFO[2][0] = "classManage";
$PAGE_TITLE_INFO[2][1] = "班级管理";
	$PAGE_TITLE_INFO[2][2] = "class";
		$PAGE_TITLE_INFO[2][3] = "ID";
		$PAGE_TITLE_INFO[2][4] = "classType";
		$PAGE_TITLE_INFO[2][5] = "classTime";
		$PAGE_TITLE_INFO[2][6] = "className";
		$PAGE_TITLE_INFO[2][7] = "classPeople";
		/*
		$PAGE_TITLE_INFO[2][8] = "week0";//These value are used in semester weeks
		$PAGE_TITLE_INFO[2][9] = "week1";
		$PAGE_TITLE_INFO[2][10] = "week2";
		$PAGE_TITLE_INFO[2][11] = "week3";
		$PAGE_TITLE_INFO[2][12] = "week4";
		$PAGE_TITLE_INFO[2][13] = "week5";
		$PAGE_TITLE_INFO[2][14] = "week6";
		$PAGE_TITLE_INFO[2][15] = "week7";
		$PAGE_TITLE_INFO[2][16] = "week8";
		$PAGE_TITLE_INFO[2][17] = "week9";
		$PAGE_TITLE_INFO[2][18] = "week10";
		$PAGE_TITLE_INFO[2][19] = "week11";
		$PAGE_TITLE_INFO[2][20] = "week12";
		$PAGE_TITLE_INFO[2][21] = "week13";
		$PAGE_TITLE_INFO[2][22] = "week14";
		$PAGE_TITLE_INFO[2][23] = "week15";
		$PAGE_TITLE_INFO[2][24] = "week16";
		$PAGE_TITLE_INFO[2][25] = "week17";
		$PAGE_TITLE_INFO[2][26] = "week18";
		$PAGE_TITLE_INFO[2][27] = "week19";
		*/ 


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

//------  -[ array_Partitioner Function ]-  ------
//This function cut the array and return the part what your need
//Notes:
//The $returnLocation optionally value is only 0 or 1, "0" means HEAD and "1" means TAIL 
function array_Partitioner($inputArray, $returnLocation, $dropLength){
	$inputArrayCount0 = count($inputArray);
	$loopLength = $inputArrayCount0 - $dropLength;
	if($returnLocation == 0){
		for($i=0;$i<$loopLength;$i++){
			$outputArray[] = $inputArray[$i];
		}
		return $outputArray; 
	}elseif($returnLocation == 1){
		for($i=$dropLength;$i<$inputArrayCount0;$i++){
			$outputArray[] = $inputArray[$i];
		}
		return $outputArray;
	}else{
		return 0;
	}
}

//------  -[ post_auto_fill function ]-  ------
function post_auto_fill($post_value){
	$post_value;	//Target to fill

	if($post_value == ""){
		$post_value = "auto_filled";
	}
	print $post_value;
	return $post_value;
} 

//Fin.
?>