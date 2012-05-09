<?php
/**
*	Database Functions
*	
*	Serial:		120429
*	by:			M.Karminski
*
*/

//Include files
include('etc/config.php');

//Define the global database connection var
$DB_CONNECTION = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);

//Database connect status
if($DB_CONNECTION){
		//print "Database connect success.<br />";
}else{
		print "Error:Databse connect fail.<br />";
}

//Create the database if not exists
database_create($DB_NAME, $DB_CONNECTION);

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
		//print "Database ".$database_name." has been found.<br />";
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

//------  -[ table_data_query Function ]-  ------
function table_data_query($table_name, $table_key_names_array, $sql_arguments = ""){
    $table_name;                //For lock on the table
    $table_key_names_array;     //For from the SQL query
    $sql_arguments;             //Fro sql query arguments(Optional) 

    //Select ID and get ID array
    $sql_select_id = "SELECT ID FROM $table_name";
    $queryResult = mysql_query($sql_select_id);
    $idNumbers = mysql_num_rows($queryResult);
    for($i=0;$i<$idNumbers;$i++){
        $idArrayTemp[$i] = mysql_fetch_row($queryResult);
        $idArray[] = $idArrayTemp[$i][0];
    }
    //Set the counter
    $table_key_names_array_count0 = count($table_key_names_array);
    $counter = 0;
    //Form the SQL Query
    $sql_select_values_by_id = "SELECT ";
    foreach($table_key_names_array as $value){
        if($counter < $table_key_names_array_count0 - 1){
            $sql_select_values_by_id .= $value.", ";
        }else{
            $sql_select_values_by_id .= $value." FROM $table_name WHERE ID = ";
        }
        $counter ++;
    }
    //Query tableData By ID Array
    for($i=0;$i<$idNumbers;$i++){
        if($sql_arguments != ""){
            $queryAddArguments = " AND ".$sql_arguments;
        }
        $sql_select_values_by_id_formed = $sql_select_values_by_id.$idArray[$i].$queryAddArguments;
        vars_checkout($sql_select_values_by_id_formed, "sql_select_values_by_id_formed");
        //print $sql_select_values_by_id_formed;
        $queryResult = mysql_query($sql_select_values_by_id_formed);
        $table_data_array[$i] = mysql_fetch_assoc($queryResult);
    }
    return $table_data_array;
}

//------  -[ table_data_delete_by_id Function]-  ------
function table_data_delete_by_id($table_name, $target_id){
    $table_name;    //For lock on the table
    $target_id;     //For lock on the mark which need to delete

    $sql_table_data_delete = "DELETE FROM $table_name WHERE ID = $target_id";
    mysql_query($sql_table_data_delete);
    return 0;
}

//------  -[ table_data_add Function]-  ------
//TODO: chage the data insert method "'".
function table_data_add($table_name, $table_key_names_array, $table_data_input){
    $table_name;                //For lock on the table
    $table_key_names_array;     //For form the SQL query
    $table_data_input;          //For form the SQL query value

    unset($table_key_names_array['ID'], $table_data_input['ID']);//Drop 'ID' key
    $table_key_names_array_count0 = count($table_key_names_array);
    $counter = 0;
    $sql_table_data_add = "INSERT INTO $table_name (";
    foreach($table_key_names_array as $value){
        //print $counter."<br />";
        if($counter<$table_key_names_array_count0-1){
            $sql_table_data_add .= $value.", ";
        }else{
            $sql_table_data_add .= $value.") VALUES (";
        }
        $counter ++;
    }
    $counter = 0;
    foreach($table_key_names_array as $value){
        if($counter<$table_key_names_array_count0-1){
            $sql_table_data_add .= "'".$table_data_input[$value]."', ";
        }else{
            $sql_table_data_add .= "'".$table_data_input[$value]."')";
        }
        $counter ++;
    }
    print $sql_table_data_add;
    mysql_query($sql_table_data_add);
    return 0;
}

//------  -[ table_data_change Function ]-  ------
function table_data_change($table_name, $table_key_names_array, $target_id, $table_data_change_input){
    $table_name;                //For locate on the table
    $table_key_names_array;     //For form the SQL query
    $target_id;                 //For form the SQL query
    $table_data_change_input;   //For form the SQL query

    $sql_table_data_change = "UPDATE $table_name SET ";
    $table_key_names_array_count0 = count($table_key_names_array);
    $counter = 0;
    foreach($table_key_names_array as $value){
        if($value == "ID"){
            $counter ++;
            continue;
        }elseif($counter<$table_key_names_array_count0-1){
            $sql_table_data_change .= $table_key_names_array[$value]." = '".$table_data_change_input[$value]."', ";
        }else{
            $sql_table_data_change .= $table_key_names_array[$value]." = '".$table_data_change_input[$value]."' WHERE ID = ".$target_id;
        }
        $counter ++;
    }
    print $sql_table_data_change;
    mysql_query($sql_table_data_change);
    return 0;
}

//------  -[ table_key_names_array_get ]-  ------
//This function return target database table key names.
function table_key_names_array_get($target_table_name){
    $target_table_name;         //target database table name.

    $sql_select_id = "SELECT ID FROM $target_table_name";
    $queryResult = mysql_query($sql_select_id);
    $fetchAssocResult = mysql_fetch_assoc($queryResult);
    $targetId = $fetchAssocResult['ID'];
    $sqlSelectAll = "SELECT * FROM $target_table_name WHERE ID = $targetId";
    $queryResult = mysql_query($sqlSelectAll);
    $fetchAssocResult = mysql_fetch_assoc($queryResult);
    $fetchAssocResultCount0 = count($fetchAssocResult);
    $arrayKeyNamesArray = array_keys($fetchAssocResult); 
    for($i=0;$i<$fetchAssocResultCount0;$i++){
        $table_key_names_array[$arrayKeyNamesArray[$i]] = $arrayKeyNamesArray[$i]; 
    }
    return $table_key_names_array;
}




//Fin.
?>
