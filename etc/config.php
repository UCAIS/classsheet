<?php
/**
*	Default database connection settings 
*	
*	Serial:		120312
*	by:			M.Karminski
*
*/

//Define the global database auth vars.

$DB_NAME = 'classsheet';	//database name
$DB_HOST = 'localhost';		//database host location
$DB_USER = 'root';			//database user name
$DB_PASS = 'root';			//database user password

/**
 * This part for EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
        

// Define here you own values
$config = array(
	"db_name" => $DB_NAME,
	"db_user" => $DB_USER,
	"db_password" => $DB_PASS,
	"db_host" => $DB_HOST
);                
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
