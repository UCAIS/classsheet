<?php
/**
*	Database query functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ tableData_Query Function ]-  ------
function tableData_Query($tableName, $tableKeyNamesArray){
    //Select ID and get ID array
    $SQL_Select_ID = "SELECT ID FROM $tableName";
    $SQL_Select_ID_QueryResult = mysql_query($SQL_Select_ID);
    $ID_Numbers = mysql_num_rows($SQL_Select_ID_QueryResult);
    for($i=0;$i<$ID_Numbers;$i++){
        $ID_Array_Temp[$i] = mysql_fetch_row($SQL_Select_ID_QueryResult);
        $ID_Array[] = $ID_Array_Temp[$i][0];
    }
    //Form the SQL Query
    $tableKeyNamesArrayCount0 = count($tableKeyNamesArray);
    $SQL_Select_tableKeyValues_ByID = "SELECT ";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $SQL_Select_tableKeyValues_ByID = $SQL_Select_tableKeyValues_ByID.$tableKeyNamesArray[$i].", ";
    }
    $SQL_Select_tableKeyValues_ByID = $SQL_Select_tableKeyValues_ByID."ID FROM $tableName WHERE ID = ";
    //Query tableData By ID Array
    for($i=0;$i<$ID_Numbers;$i++){
        $SQL_Select_tableKeyValues_ByID_Formed = $SQL_Select_tableKeyValues_ByID.$ID_Array[$i];
        $SQL_Select_tableKeyValues_ByID_QueryResult = mysql_query($SQL_Select_tableKeyValues_ByID_Formed);
        $SQL_Select_tableKeyValues_ByID_FetchRowResult = mysql_fetch_row($SQL_Select_tableKeyValues_ByID_QueryResult);
        $tableKeyValuesArray[$i] = $SQL_Select_tableKeyValues_ByID_FetchRowResult;
    }
    return $tableKeyValuesArray;
}

//------  -[ tableData_Delete_ByID Function]-  ------
function tableData_Delete_ByID($tableName, $ID){
    $SQL_TableData_Delete = "DELETE FROM $tableName WHERE ID = $ID";
    mysql_query($SQL_TableData_Delete);
}

//------  -[ tableData_Add Function]-  ------
function tableData_Add($tableName, $tableKeyNamesArray, $tableDataInput){
    $tableKeyNamesArrayCount0 = count($tableKeyNamesArray);
    $SQL_TableData_Add = "INSERT INTO $tableName (";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $SQL_TableData_Add = $SQL_TableData_Add.$tableKeyNamesArray[$i];
        if($i<$tableKeyNamesArrayCount0-1){
            $SQL_TableData_Add = $SQL_TableData_Add.",";
        }

    }
    $SQL_TableData_Add = $SQL_TableData_Add.") VALUES (";
    for($i=0;$i<$tableKeyNamesArrayCount0;$i++){
        $SQL_TableData_Add = $SQL_TableData_Add.$tableDataInput[$i];
        if($i<$tableKeyNamesArrayCount0-1){
            $SQL_TableData_Add = $SQL_TableData_Add.",";
        }
    }
    $SQL_TableData_Add = $SQL_TableData_Add.")";
    print($SQL_TableData_Add);
    mysql_query($SQL_TableData_Add);
}


















//Fin.

?>