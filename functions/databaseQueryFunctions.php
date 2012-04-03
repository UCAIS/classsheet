<?php
/**
*	Database query functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ table_data_query Function ]-  ------
function table_data_query($table_name, $table_key_names_array){
    //Select ID and get ID array
    $sql_select_id = "SELECT ID FROM $table_name";
    $query_result = mysql_query($sql_select_id);
    $id_numbers = mysql_num_rows($query_result);
    for($i=0;$i<$id_numbers;$i++){
        $id_array_temp[$i] = mysql_fetch_row($query_result);
        $id_array[] = $id_array_temp[$i][0];
    }
    //Form the SQL Query
    $sql_select_table_key_values_by_id = "SELECT ";
    foreach ($table_key_names_array as $value) {
        $sql_select_table_key_values_by_id .= $value.", "; 
    }
    $sql_select_table_key_values_by_id .= "ID FROM $table_name WHERE ID = ";
    //Query tableData By ID Array
    for($i=0;$i<$id_numbers;$i++){
        $sql_select_table_key_values_by_id_formed = $sql_select_table_key_values_by_id.$id_array[$i];
        $query_result = mysql_query($sql_select_table_key_values_by_id_formed);
        $fetch_row_result = mysql_fetch_row($query_result);
        $table_data_array[$i] = $fetch_row_result;
    }
    return $table_data_array;
}

//------  -[ tableData_Delete_ByID Function]-  ------
function tableData_Delete_ByID($tableName, $targetID){
    $sql_TableData_Delete = "DELETE FROM $tableName WHERE ID = $targetID";
    mysql_query($sql_TableData_Delete);
    return 0;
}

//------  -[ tableData_Add Function]-  ------
function tableData_Add($tableName, $tableKeyNamesArray, $tableDataInput){
    $tableKeyNamesArrayCount0 = count($tableKeyNamesArray);
    $sql_TableData_Add = "INSERT INTO $tableName (";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $sql_TableData_Add = $sql_TableData_Add.$tableKeyNamesArray[$i];
        if($i<$tableKeyNamesArrayCount0-1){
            $sql_TableData_Add = $sql_TableData_Add.",";
        }
    }
    $sql_TableData_Add = $sql_TableData_Add.") VALUES (";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $sql_TableData_Add = $sql_TableData_Add.$tableDataInput[$i];
        if($i<$tableKeyNamesArrayCount0-1){
            $sql_TableData_Add = $sql_TableData_Add.",";
        }
    }
    $sql_TableData_Add = $sql_TableData_Add.")";
    mysql_query($sql_TableData_Add);
    return 0;
}

//------  -[ tableData_Change Function ]-  ------
function tableData_Change($tableName, $tableKeyNamesArray, $targetID, $tableDataChangeInput){
    $tableKeyNamesArrayCount0 = count($tableKeyNamesArray);
    $sql_TableData_Change = "UPDATE $tableName SET ";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $sql_TableData_Change = $sql_TableData_Change.$tableKeyNamesArray[$i]." = ".$tableDataChangeInput[$i];
        if($i<$tableKeyNamesArrayCount0-1){
            $sql_TableData_Change = $sql_TableData_Change.", ";
        }
    }
    $sql_TableData_Change = $sql_TableData_Change." WHERE ID = ".$targetID;
    mysql_query($sql_TableData_Change);
    return 0;
}
















//Fin.

?>