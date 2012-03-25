<?php
/**
*	Database query functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ semesterArrayList_Query Function ]-  ------
//This function return the array $semesterArrayList 
//Example:
//$semesterArrayList[0][0] = "2010-2011";       //semesster
//$semesterArrayList[0][1] = 2;                 //part
//$semesterArrayList[0][2] = 20;                //weekCount
//$semesterArrayList[0][3] = 2011;              //startYear
//$semesterArrayList[0][4] = 02;                //startMonth
//$semesterArrayList[0][5] = 28;                //startDay
//$semesterArrayList[0][6] = 1;                 //ID

function semesterArrayList_Query(){
    //Select ID
    $SQLSelectID = "SELECT ID FROM semester";
    $queryResult = mysql_query($SQLSelectID);
    //Count numbers of ID
    $ID_Numbers = mysql_num_rows($queryResult);
    //Get one-dimensions array
    for($i=0;$i<$ID_Numbers;$i++){
        $IDArrayTemp[$i] = mysql_fetch_row($queryResult);
        $IDArray[] = $IDArrayTemp[$i][0];
    }
    //Query the semester information 
    for($i=0;$i<$ID_Numbers;$i++){
        $SQLSelectSemesterArray = ("SELECT semester,part,weekCount,startYear,startMonth,startDay,ID FROM semester WHERE ID=$IDArray[$i]");
        $queryResult = mysql_query($SQLSelectSemesterArray);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $semesterArrayList[$i] = $fetchRowResult;    
    }
    return $semesterArrayList;
}

//------  -[ tableData_Query Function ]-  ------
function tableData_Query($tableName, $tableKeyNamesArray){

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