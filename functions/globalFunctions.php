<?php
/**
*	Global functions and vars page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/

//------ -[ Page Title Informatin Vars ]-  ------
//Notes:
//The 0-1 for page info, 2-n for database information [ WARING:All VALUES ARE USED IN GLOBAL, CHAGE IT CAREFULLY ]
//Example:
//$PAGE_TITLE_INFO[0][0] = "index";			//PAGE NAME for LOCATE PHP PAGE
//$PAGE_TITLE_INFO[0][1] = "首页";			//PAGE NAME in CHINESE
//$PAGE_TITLE_INFO[0][2] = "semester";		//TABLE NAME in DATABSE
//$PAGE_TITLE_INFO[0][3] = "ID";			//ID [STATIC]
//$PAGE_TITLE_INFO[0][4] = "key"			//KEY NAME in DATABSE

$PAGE_TITLE_INFO[0][0] = "index";				
$PAGE_TITLE_INFO[0][1] = "首页";		

$PAGE_TITLE_INFO[1][0] = "semesterManage";		
$PAGE_TITLE_INFO[1][1] = "学期管理";	
	$PAGE_TITLE_INFO[1][2] = "semester";
		$PAGE_TITLE_INFO[1][3] = "ID";					
		$PAGE_TITLE_INFO[1][4] = "semester";			
		$PAGE_TITLE_INFO[1][5] = "part";			
		$PAGE_TITLE_INFO[1][6] = "weekCount";			
		$PAGE_TITLE_INFO[1][7] = "startYear";			
		$PAGE_TITLE_INFO[1][8] = "startMonth";			
		$PAGE_TITLE_INFO[1][9] = "startDay";

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


//Fin.
?>