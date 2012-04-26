<?php
/**
*	Course and Course Type Import Page
*	
*	Serial:		120424
*	by:			M.Karminski
*
*/


//Page number
$PAGE_SWITCH = 12;
$SEMESTER_PAGE_SWITCH = 1;
$COURSE_TYPE_PAGE_SWITCH = 2;
$COURSE_PAGE_SWITCH = 3;

//Include files
include('settings.php');
include('html_head.php');
include('functions/database_connection_functions.php');
include('functions/database_query_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');

//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//QUERY the $semesterListArray
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

//Load the Upload file into vars 
if(is_uploaded_file($_FILES['uploadFiles']['tmp_name'])){
	$uploadedFileTempName = $_FILES['uploadFiles']['tmp_name'];
	$uploadedFiles = file($uploadedFileTempName);
	$loopCounter = 0;
	foreach($uploadedFiles as $fileContents){
		print "<br />".$fileContents."<br />";
		$courseImportInfoArray[$loopCounter] = explode(",", $fileContents);
		$loopCounter ++;
	}
}

//Form the import data 
//TODO: Example method.
$loopCounter = 0;
$courseImportInfoArrayCount1 = count($courseImportInfoArray[0]);
foreach($courseImportInfoArray[0] as $value){
	$COURSE_TABLE_KEY_NAMES_ARRAY[] = $value;
	for($i=0;$i<$courseImportInfoArrayCount1;$i++){
		$courseInsertInfoArray[$i][$value] = "'".$courseImportInfoArray[$i+1][$loopCounter]."'";
	}
	$loopCounter ++; 
}

//Write import data into database
$COURSE_TABLE_NAME = $PAGE_INFO_ARRAY[$COURSE_PAGE_SWITCH]['TABLE_NAME'];
$COURSE_TABLE_NAME .= "_".$semesterListArray[$semesterTargetArray]['SEMESTER']."_".$semesterListArray[$semesterTargetArray]['PART'];
$tableKeyNumbersCount = $courseImportInfoArrayCount1 - 1;//Drop the array title line
for($i=0;$i<$tableKeyNumbersCount;$i++){
	table_data_add($COURSE_TABLE_NAME, $COURSE_TABLE_KEY_NAMES_ARRAY, $courseInsertInfoArray[$i]);
}

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
		files_upload_output();
		form_end_output();
	div_end_output();
div_end_output();

//Print HTML end
body_end_output();
html_end_output();

//Fin.
?>