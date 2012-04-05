<?php
/**
 *  Global function setting page
 *  @author Linfcstmr
 *  110729
 * 
 * 
 */ 
 
 
// ----- [Query the semester information] ---------
//This function return the array $semesterSet 
//Example:
//$semesterSet[0][0] = ("2010-2011");   //semesster
//$semesterSet[0][1] = 2;               //part
//$semesterSet[0][2] = 20;              //weekCount
//$semesterSet[0][3] = 2011;            //startYear
//$semesterSet[0][4] = 02;              //startMonth
//$semesterSet[0][5] = 28;              //startDay
//$semesterSet[0][6] = 1;               //ID

function querySemesterInfo(){
    //Require the database to count the number of item
    $SQLSelectID = "SELECT ID FROM semester";
    $queryResult = mysql_query($SQLSelectID);
    //Count numbers of ID
    $countIDNumbers = mysql_num_rows($queryResult);
    //Get one-dimensions array
    for($i=0;$i<$countIDNumbers;$i++){
        $IDArrayTemp[$i] = mysql_fetch_row($queryResult);
        $IDArray[] = $IDArrayTemp[$i][0];
    }
    $countIDArray = count($IDArray);
    //Query the semester information 
    for($i=0;$i<$countIDArray;$i++){
        $SQLSelectSemesterArray = ("SELECT semester,part,weekCount,startYear,startMonth,startDay,ID FROM semester WHERE ID=$IDArray[$i]");
        $queryResult = mysql_query($SQLSelectSemesterArray);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $semesterSet[$i] = $fetchRowResult;    
    }
    return $semesterSet;
}


// ----- [Query the class information] ---------
//This function return the array $classArray 
//Example:
//$classArray[0][0] = "A";              //classType
//$classArray[0][1] = 160;              //classTime
//$classArray[0][2] = "机设09-1"；      //className
//$classArray[0][3] = 30;               //classPeople
//$classArray[0][4]~[23] = 0,1,2;       //week0~week19
//$classArray[0][24] = 1;               //ID

function queryClassInfo($semesterNameInput,$weekCountInput){
    //Require the database to count the number of item
    $SQLSelectID = "SELECT ID FROM $semesterNameInput";
    $queryResult = mysql_query($SQLSelectID);
    //Count numbers of ID
    $countIDNumbers = mysql_num_rows($queryResult);
    //Get one-dimensions array
    for($i=0;$i<$countIDNumbers;$i++){
        $IDArrayTemp[$i] = mysql_fetch_row($queryResult);
        $IDArray[] = $IDArrayTemp[$i][0];
    }
    $countIDArray = count($IDArray);
    //Query the class information
     $SQLSelectClassArrayTemp = ("SELECT classType,classTime,className,classPeople");
    for($j=0;$j<$weekCountInput;$j++){
        $SQLSelectClassArrayTemp = $SQLSelectClassArrayTemp.",week$j";
    }
    
    for($i=0;$i<$countIDArray;$i++){
        $SQLSelectClassArray = ($SQLSelectClassArrayTemp.",ID FROM $semesterNameInput WHERE ID=$IDArray[$i]");
        $queryResult = mysql_query($SQLSelectClassArray);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $classArray[$i] = $fetchRowResult;    
    }
    return $classArray; 
    
    
}

// ----- [Query the class type information] ---------
//This function return the array $classTypeArray 
//Example:
//$classTypeArray[0] = A;                //classType
//$classTypeArray[1] = B;                //classType
//$classTypeArray[2] = C1；              //classType

function queryClassType($semesterNameInput){
    //Require the database to count the number of item
    $SQLSelectClassType = "SELECT classType FROM $semesterNameInput";
    $queryResult = mysql_query($SQLSelectClassType);
    //Count numbers of classType
    $countClassTypeNumbers = mysql_num_rows($queryResult);
    //Get one-dimensions array
    for($i=0;$i<$countClassTypeNumbers;$i++){
        $classTypeArrayTemp[$i] = mysql_fetch_row($queryResult);
        $classTypeArray[] = $classTypeArrayTemp[$i][0];
    }
    $countClassTypeArray = count($classTypeArray);
    sort($classTypeArray);//Sort the array
    //Pickup the type and get a no-repeat classType array
    for($i=0;$i<$countClassTypeArray;$i++){
        $classTypeTempA = $classTypeArray[$i];
        $classTypeTempB = $classTypeArray[$i+1];
        if($classTypeTempA != $classTypeTempB){
            $classTypeTempC[] = $classTypeArray[$i];
        }    
    }
    $classTypeArray = $classTypeTempC;
    return $classTypeArray;
}

// ----- [Query the course information] ---------
//This function return the array $courseArray 
//Example:
//$courseArray[0][0] = "铸造";           //courseName
//$courseArray[0][1] = 2;                //courseAccept
//$courseArray[0][2] = 0;                //courseType ["" Not Exam; "on" Exam or Design]
//$courseArray[0][3] = 24；              //A [Course time]
//$courseArray[0][4] = 16;               //B
//$courseArray[0][5] = 16;               //C1
//$courseArray[0][6] = 1;                //ID [Follow the final array count]

function queryCourseInfo($courseNameInput,$classTypeArrayInput){
    //Require the database to count the number of item
    $SQLSelectID = "SELECT ID FROM $courseNameInput";
    $queryResult = mysql_query($SQLSelectID);
    //Count numbers of ID
    $countIDNumbers = mysql_num_rows($queryResult);
    //Get one-dimensions array
    for($i=0;$i<$countIDNumbers;$i++){
        $IDArrayTemp[$i] = mysql_fetch_row($queryResult);
        $IDArray[] = $IDArrayTemp[$i][0];
    }
    $countIDArray = count($IDArray);
    //Query the course information
     $SQLSelectCourseArrayTemp = ("SELECT courseName,courseAccept,courseType");    
     $classTypeArrayCount = count($classTypeArrayInput);
    for($j=0;$j<$classTypeArrayCount;$j++){
        $SQLSelectCourseArrayTemp = $SQLSelectCourseArrayTemp.",".$classTypeArrayInput[$j];
    }
    for($i=0;$i<$countIDArray;$i++){
        $SQLSelectCourseArray = ($SQLSelectCourseArrayTemp.",ID FROM $courseNameInput WHERE ID=$IDArray[$i]");
        $queryResult = mysql_query($SQLSelectCourseArray);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $courseArray[$i] = $fetchRowResult;    
    }
    return $courseArray;     
}


// ----- [Query the class select information] ---------
//This function return the array $classSelectArray 
//Example:
//$classSelectArray[0][0] = "机设09-1";       //classNameSelected
//$classSelectArray[0][1] = "A";              //classTypeSelected

function queryClassSelect($semesterNameInput,$semesterWeekInput){
    $semesterWeekInput = "week".($semesterWeekInput-1);//Form week input to the database form set
    $SQLSelectClassName = "SELECT className,classType FROM $semesterNameInput WHERE $semesterWeekInput = 1";
    $queryResult = mysql_query($SQLSelectClassName);
    $countClassNameNumbers = mysql_num_rows($queryResult);
    for($i=0;$i<$countClassNameNumbers;$i++){
        $fetchRowResult = mysql_fetch_row($queryResult);
        $classSelectArray[$i] = $fetchRowResult; 
    }
   return $classSelectArray;
  
}


// ----- [Query the course form information] ---------
//This function return the array $courseFormArray 
//Example:
//$courseFormArray[0][0] = 1;               //semesterWeek
//$courseFormArray[0][1] = 1;               //week
//$courseFormArray[0][2-17] = "机设09-1";   //course0AM-course7PM
//$courseFormArray[0][18] = 1;              //ID

function queryCourseForm($semesterNameInput,$courseArrayCountInput,$semesterWeekInput){
    $SQLSelectCourseForm = "SELECT semesterWeek,week,";
    for($i=0;$i<$courseArrayCountInput;$i++){
        $SQLSelectCourseForm = $SQLSelectCourseForm."course$i"."AM,course$i"."PM,";
    }
    $SQLSelectCourseForm = $SQLSelectCourseForm."ID FROM $semesterNameInput WHERE semesterWeek = $semesterWeekInput";
    $queryResult = mysql_query($SQLSelectCourseForm);
    $queryResultCount0 = mysql_num_rows($queryResult);
    for($i=0;$i<$queryResultCount0;$i++){
        $fetchRowResult[$i] = mysql_fetch_row($queryResult);
    }
    $courseFormArray = $fetchRowResult;
    
    return $courseFormArray;
}


// ----- [Query the userArray from user ] ---------
//This function return the array $userArray 
//Example:
//$userArray[0][0] = "admin";               //userName
//$userArray[0][1] = 1;                     //ID

function queryUserArray(){
    $SQLQueryUser = "SELECT username,ID FROM user";
    $queryResult = mysql_query($SQLQueryUser);
    $queryResultCount0 = mysql_num_rows($queryResult);
    print("\$queryResultCount0 = ".$queryResultCount0."<br />");
    //Load user in array
    for($i=0;$i<$queryResultCount0;$i++){
        $fetchRowResult = mysql_fetch_row($queryResult);
        $userArray[$i][0] = $fetchRowResult[0];
        $userArray[$i][1] = $fetchRowResult[1];
    }
    return $userArray;
}


// ----- [Query the classRoom information ] ---------
//This function return the array $classRoomArray
//Example:
//$classRoomArray[0][0] = "实216[车工]";       //classRoomName
//$classRoomArray[0][1] = "4";                 //classRoomAccept
//$classRoomArray[0][2] = "0";                 //classRoomType [0]classRoom [1]workshop
//$classRoomArray[0][3] = "2";                 //classRoomPriorityProcessing [courseID]
//$classRoomArray[0][4] = "0";                 //ID

function queryClassRoomArray($semesterNameInput){
    $SQLQueryclassRoom = "SELECT classRoomName,classRoomAccept,classRoomType,classRoomPriorityProcessing,ID FROM $semesterNameInput";
    $queryResult = mysql_query($SQLQueryclassRoom);
    $queryResultCount = mysql_num_rows($queryResult);
    for($i=0;$i<$queryResultCount;$i++){
        $fetchRouResult = mysql_fetch_row($queryResult);
        for($j=0;$j<5;$j++){
            $classRoomArray[$i][$j] = $fetchRouResult[$j]; 
        }
    }
    return $classRoomArray;
    
}
?>

















