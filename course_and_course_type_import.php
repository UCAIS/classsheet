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
//Semester page number
$SEMESTER_PAGE_SWITCH = 1;

//Include files
include('settings.php');
include('html_head.php');
include('functions/database_connection_functions.php');
include('functions/database_query_functions.php');
include('functions/global_functions.php');
include('functions/views_output_functions.php');
//Define the upload root
define("FILTEREPOSITORY", "/temp/");

//Load the file name for post
$FILE_NAME = $_SERVER['PHP_SELF'];
//Semeter List information
$SEMESTER_TABLE_NAME = $PAGE_INFO_ARRAY[$SEMESTER_PAGE_SWITCH]['TABLE_NAME'];
$SEMESTER_TABLE_KEY_NAMES_ARRAY = $TABLE_KEY_NAMES_ARRAY[$SEMESTER_PAGE_SWITCH];
$semesterListArray = table_data_query($SEMESTER_TABLE_NAME, $SEMESTER_TABLE_KEY_NAMES_ARRAY);
$semesterTargetArray = $_POST['semesterList'];

//Upload file 
print $_FILES['uploadFiles']['name'];
print $_FILES['uploadFiles']['type'];
print $_FILES['uploadFiles']['tmp_name'];
if(is_uploaded_file($_FILES['uploadFiles']['tmp_name'])){
	$uploadedFileTempName = $_FILES['uploadFiles']['tmp_name'];
	$uploadedFiles = file($uploadedFileTempName);
	$loopCounter = 0;
	foreach($uploadedFiles as $fileContents){
		print $fileContents."<br />";
		if($loopCounter == 0){
			$courseType = explode(",", $uploadedFiles);
		}

	}
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