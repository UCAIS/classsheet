<?php     
/*
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */   
require_once('../etc/config.php');      
require_once('../functions/editable_grid.php');
require_once('../functions/global_functions.php');            

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

$gridKeyNamesArrayCount0 = count($GRID_KEY_NAMES_ARRAY[$COURSE_PERIOD_PAGE_SWITCH]);
foreach($GRID_KEY_NAMES_ARRAY[5] as $key => $value){
	if($key == 'id'){
		$grid->addColumn('id', 'ID', 'integer', NULL, false);
		continue;
	}
	$grid->addColumn($key, $value, 'string');
}

$result = $mysqli->query('SELECT * FROM course_2010_2011_1'); WHAT THE FUCK !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$mysqli->close();
// send data to the browser
$grid->renderXML($result);

