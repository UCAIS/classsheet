<?php
/**
*	Database and database tables create function
*	
*	Serial:		120312
*	by:			M.Karminski
*
*/

//------  -[ database_Create function ]-  ------
function database_Create($database_name, $database_connection){
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

//------  -[ database_table_Create Function ]-  ------
function database_table_Create($table_name, $table_key_names_array){
	$table_key_names_arrayCount0 = count($table_key_names_array);
	//make up the SQL Query
	$SQLTableCreate = "CREATE TABLE IF NOT EXISTS $table_name (ID int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
	for($i=0;$i<$table_key_names_arrayCount0;$i++){
		$SQLTableCreate = $SQLTableCreate.", ".$table_key_names_array[$i]." varchar(15)";
	}
	$SQLTableCreate = $SQLTableCreate.")";
	//print $SQLTableCreate;	//Just for DEBUG
    mysql_query($SQLTableCreate);
    return 0;
	//print "Table $table_name has been created successfully.<br />";
}



//Fin.
?>