<?php     
/*
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */ 

//Start session
session_start();

require_once('../etc/config.php');
require_once('../etc/global_vars.php');      
require_once('../functions/editable_grid.php');

/**
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}
         
//Load target page info by SESSION.
$targetDatabaseTableName = $_SESSION['targetTableName'];
$targetPageSwitch = $_SESSION['targetPageSwitch'];
$targetSemesterWeek = $_SESSION['targetSemesterWeek'];

// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                    
// create a new EditableGrid object
$grid = new EditableGrid();

/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*  The third argument is the display type of grid
*/

foreach($GRID_KEY_NAMES_ARRAY[$targetPageSwitch] as $key => $value){
	if($key == 'id'){
		$grid->addColumn('id', 'ID', 'integer', NULL, false);
		continue;
	}
	$grid->addColumn($key, $value, 'string');
}

//Create the SQL Syntax.
if($targetPageSwitch != $COURSE_PERIOD_PAGE_SWITCH && $targetPageSwitch != $WEEKS_SCHEDULE_PAGE_SWITCH){
	$sqlSelectSyntax = "SELECT * FROM $targetDatabaseTableName WHERE SEMESTER_WEEK = $targetSemesterWeek";
}else{
	$sqlSelectSyntax = "SELECT * FROM $targetDatabaseTableName";
}

$result = $mysqli->query($sqlSelectSyntax);
$mysqli->close();
// send data to the browser
$grid->renderXML($result);

