<?php
/**
*	Database connetcion function
*	
*	Serial:		120312
*	by:			M.Karminski
*
*/

//Include files
include('config.php');
include('databaseCreateFunctions.php');

//Define the global database connection var
$DB_CONNECTION = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);

//Database connect status
if($DB_CONNECTION){
		print "Database connect success.<br />";
}else{
		print "Error:Databse connect fail.<br />";
}

//Create the database if not exists
database_create($DB_NAME, $DB_CONNECTION);


//Fin.
?>