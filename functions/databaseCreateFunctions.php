<?php
/**
*	Database and database tables create function
*	
*	Serial:		120312
*	by:			M.Karminski
*
*/

//------  -[ database_Create function ]-  ------
function database_Create($databaseName, $databaseConnection){
	if(!mysql_select_db($databaseName, $databaseConnection)){
		print "Error:System could not select database and creating a new database now.<br />";
		if(mysql_query('Create database '.$databaseName)){
			print "New database has been created.<br />";
		}else{
			die("Error:System can not create database, please check settings.<br />");
		}
	}else{
		print "Database ".$databaseName." has been found.<br />";
	}
}

//------  -[ databaseTable_Create Function ]-  ------
function databaseTable_Create($tableName, $tableKeyNamesArray){
	$tableKeyNamesArrayCount0 = count($tableKeyNamesArray);
	//make up the SQL Query
	$SQLTableCreate = "CREATE TABLE IF NOT EXISTS $tableName (ID int NOT NULL AUTO_INCREMENT, PRIMARY KEY(ID)";
	for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
		$SQLTableCreate = $SQLTableCreate.", ".$tableKeyNamesArray[$i]." varchar(15)";
	}
	$SQLTableCreate = $SQLTableCreate.")";
	//print $SQLTableCreate;	//Just for DEBUG
    mysql_query($SQLTableCreate);
    return 0;
	//print "Table $tableName has been created successfully.<br />";
}



//Fin.
?>