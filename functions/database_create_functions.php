<?php
/**
*	Database and database tables create function
*	
*	Serial:		120312
*	by:			M.Karminski
*
*/

//------  -[ database_create function ]-  ------
function database_create($database_name, $database_connection){
	$database_name; 		//For database name
	$database_connection;	//For database connection
	
	if(!mysql_select_db($database_name, $database_connection)){
		print "Error:System could not select database and creating a new database now.<br />";
		if(mysql_query('Create database '.$database_name)){
			print "New database has been created.<br />";
		}else{
			die("Error:System can not create database, please check settings.<br />");
		}
	}else{
		print "Database ".$database_name." has been found.<br />";
	}
}

//------  -[ database_table_create Function ]-  ------
function database_table_create($table_name, $table_key_names_array, $table_key_types_array){
	$table_name;				//For database table name
	$table_key_names_array;		//For database table key names
	$table_key_types_array;		//For database table key types

	//Count the array
	$table_key_names_array_count0 = count($table_key_names_array);
	//Make up the SQL Query
	$counter = 0;	//Set the counter
	$sql_table_create = "CREATE TABLE IF NOT EXISTS $table_name ( ";
	foreach($table_key_names_array as $tableKeyNamesValue){
		if($counter < $table_key_names_array_count0 - 1){
			$sql_table_create .= $tableKeyNamesValue." ".$table_key_types_array[$tableKeyNamesValue].", ";
		}else{
			$sql_table_create .= $tableKeyNamesValue." ".$table_key_types_array[$tableKeyNamesValue]." )";
		}
		$counter++;
	}
	print($sql_table_create);
    mysql_query($sql_table_create);
    return 0;
	// print "Table $table_name has been created successfully.<br />";
}



//Fin.
?>