<?php
/**
*	Database query functions page
*	
*	Serial:		120404
*	by:			M.Karminski
*
*/


//------  -[ table_data_query Function ]-  ------
function table_data_query($table_name, $table_key_names_array){
    $table_name;                //For lock on the table
    $table_key_names_array;     //For from the SQL query

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
        $sql_select_values_by_id_formed = $sql_select_values_by_id.$idArray[$i];
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
function table_data_add($table_name, $table_key_names_array, $table_data_input){
    $table_name;                //For lock on the table
    $table_key_names_array;     //For form the SQL query
    $table_data_input;          //For form the SQL query value

    //TODO change the array from "serial number" to "key-value" mode, and add the tips

    $table_key_names_array_count0 = count($table_key_names_array);
    $counter = 0;
    $sql_table_data_add = "INSERT INTO $table_name (";
    foreach($table_key_names_array as $value){
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
            $sql_table_data_add .= $table_data_input[$value].", ";
        }else{
            $sql_table_data_add .= $table_data_input[$value].")";
        }
        $counter ++;
    }
    print($sql_table_data_add);
    mysql_query($sql_table_data_add);
    return 0;
}

//------  -[ table_data_change Function ]-  ------
function table_data_change($table_name, $table_key_names_array, $target_id, $table_data_change_input){
    $table_key_names_array_count0 = count($table_key_names_array);
    $sql_table_data_change = "UPDATE $table_name SET ";
    for($i=0;$i<$table_key_names_array_count0;$i++){
        $sql_table_data_change .= $table_key_names_array[$i]." = ".$table_data_change_input[$i];
        if($i<$table_key_names_array_count0-1){
            $sql_table_data_change .= ", ";
        }
    }
    $sql_table_data_change = $sql_table_data_change." WHERE ID = ".$target_id;
    mysql_query($sql_table_data_change);
    return 0;
}
















//Fin.

?>