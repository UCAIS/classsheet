<?php

/**
 * Course Form output page
 * @author Linfcstmr
 * @copyright 2011
 * Serial:110729
 */
 
 $pageSwich =6;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

//Load the semesterName which semester selected
$semesterSelected = $_POST["semesterList"];
$semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
$semesterNameForCourseForm = "courseform".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
$semesterWeekSelected = $_POST["semesterWeekSelected"];
$semesterWeek = $semesterSet[$semesterSelected][2];

//Load the $classTypeArray
$classTypeArray = queryClassType($semesterName);

//Load the $courseArray
if($_POST["semesterListSelected"] == "yes"){
    //Create table of course about semester if NOT exists
    $semesterSelected = $_POST["semesterList"];
    $courseName = "course".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $courseName (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),courseName varchar(15),courseAccept int";
    //Cal the classType add to database
    $classTypeArrayCount = count($classTypeArray);
    for($i=0;$i<$classTypeArrayCount;$i++){
        $SQLCreateTable = $SQLCreateTable.",".$classTypeArray[$i]." int";
    }
    $SQLCreateTable = $SQLCreateTable.")";
    mysql_query($SQLCreateTable,$DBConnect);
    
    //Load the $courseArray
    $courseArray = queryCourseInfo($courseName,$classTypeArray);
    //Unset the Exam and Design
    $courseArrayCount0 = count($courseArray);
    for($i=0;$i<$courseArrayCount0;$i++){
        if($courseArray[$i][2]== "on"){
            unset($courseArray[$i]);
        }
    }
    if($courseArray){print("\$courseArray Load over_<br />");}

}





//Count the $semesterSet array
$semesterSetCount0 = count($semesterSet);
for($i=0;$i<$semesterSetCount0;$i++){
    $semesterSetCount1[$i] = count($semesterSet[$i]);
}

// count the $courseArray array
$courseArrayCount0 = count($courseArray);
for($i=0;$i<$courseArrayCount0;$i++){
    $courseArrayCount1[] = count($courseArray[$i]);
    
}

//Load the $classSelectArray


//Load the $courseFormArray
if($_POST["semesterListSelected"] == "yes"){
    //Create table of courseForm if NOT exists
    $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $semesterNameForCourseForm (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),semesterWeek int,week int";
    for($i=0;$i<$courseArrayCount0;$i++){
        $SQLCreateTable = $SQLCreateTable.",course$i"."AM varchar(15),course$i"."PM varchar(15)";
    }
    $SQLCreateTable = $SQLCreateTable.",courseAM varchar(15), coursePM varchar(15))";
    mysql_query($SQLCreateTable);
    //Load the $courseFormArray
    $courseFormArray = queryCourseForm($semesterNameForCourseForm,$courseArrayCount0,$semesterWeekSelected);
    if($courseFormArray){print("\$courseFormArray Load over_<br />");}

}

//Count the $courseFormArray array
$courseFormArrayCount0 = count($courseFormArray);

//Turn the courseTime to courseNumber
//Exp:24学时=6节课;
$courseArray;
for($i=0;$i<$courseArrayCount0;$i++){
    for($j=0;$j<$courseArrayCount1[$i];$j++){
        if($j>2&&$j<($courseArrayCount1[$i]-1)){
            $courseArray[$i][$j] = $courseArray[$i][$j]/4;
        }
    }
}
//Delete courseForm
if($_POST["deleteCourseForm"]){
    $SQLDeleteItem = "DELETE FROM classsheet.$semesterNameForCourseForm WHERE semesterWeek = $semesterWeekSelected";
    mysql_query($SQLDeleteItem);
    //Load the $courseFormArray
    $courseFormArray = queryCourseForm($semesterNameForCourseForm,$courseArrayCount0,$semesterWeekSelected);
}

//Update Manual set courseForm
if($_POST["saveManualCourseForm"]){
    //Count manualCourseForm 
    for($i=0;$i<20;$i++){
        $postValue = "i$i"."j1fin";
        if(!$_POST[$postValue]){
            break;
        }
        $manualCourseFormCount0 ++;
    }
    //Delete old data 
    $SQLDeleteItem = "DELETE FROM classsheet.$semesterNameForCourseForm WHERE semesterWeek = $semesterWeekSelected";
    mysql_query($SQLDeleteItem);
    //Insert into database
    for($i=0;$i<$manualCourseFormCount0;$i++){
        $SQLInsertManualData = 0;
        $SQLInsertManualData = "INSERT INTO $semesterNameForCourseForm (semesterWeek,week";
        for($j=0;$j<$courseArrayCount0;$j++){
            $SQLInsertManualData = $SQLInsertManualData.",course$j"."AM,course$j"."PM";
        }
        $SQLInsertManualData = $SQLInsertManualData.") VALUES ('$semesterWeekSelected'";
        for($j=1;$j<$courseArrayCount0+$courseArrayCount0+2;$j++){
            $tempPostValues = "i".$i."j".$j."fin";
            $SQLInsertManualData = $SQLInsertManualData.",'".$_POST["$tempPostValues"]."'";
        }
        $SQLInsertManualData = $SQLInsertManualData.")";
        mysql_query($SQLInsertManualData);
        
    }
    //Load the $courseFormArray
    $courseFormArray = queryCourseForm($semesterNameForCourseForm,$courseArrayCount0,$semesterWeekSelected);
}
//////// Main CAL-Mathord //////
print '<div style="width:100%;border:1px dashed;margin:2px;padding:2px;color:#09C;font-size:12px;">';
if($_POST["reformCourse"]){
    
    $semesterWeek;//教学周数=20
    $courseArray;//课程、可接纳班级、不同类型学时排布[已转换为课节]
    $courseArrayTemp = $courseArray;
    $courseArrayCount0;//课程数目=8
    $formArrayTempA;//临时课表排布储存A
    $classTypeArrayCount;//课程类型计数器
    $courseTotalTimeAWeek = 10;//一周最多上课节数//



    $weekControl = $semesterWeekSelected;//Temp
    $classSelectArray = queryClassSelect($semesterName,$weekControl);

    $classSelectArrayCount0 = count($classSelectArray);
    print "[]\$classSelectArrayCount0 = ".$classSelectArrayCount0."<br />";
    
    //--------------------------------------------------------------[ Clean database for select
    print("//--------------------------------------------------------------[ Clean database for select<br />");
        $SQLDeleteItem = "DELETE FROM classsheet.$semesterNameForCourseForm WHERE semesterWeek = $weekControl";
        mysql_query($SQLDeleteItem);
        
    
    
    for($classRound=0;$classRound<$classSelectArrayCount0;$classRound++){ //Round Start !
        
    //--------------------------------------------------------------[ ClassPickUP
    print("//--------------------------------------------------------------[ ClassPickUP<br />");
        
        $classSelectArrayCount0 = count($classSelectArray);
        $pickUpClassNumber = $classRound;

        print "[选择的班级号码]pickUpClassNumber = ".$pickUpClassNumber."<br />";
        
        $pickUpClass = $classSelectArray[$pickUpClassNumber];
        
        print "[选择的班级信息]pickUpClass = ".$pickUpClass[0]."&nbsp;Type_&nbsp;".$pickUpClass[1]."<br />";
        //Fit the ClassType[Convert ClassType from char to number]
        for($i=0;$i<$classTypeArrayCount;$i++){
            if($pickUpClass[1] == $classTypeArray[$i]){
            $classTypeSelected = $i;
            }
        }
        //Load the $classTypeTimeArray
        unset($classTypeTimeArray);//Reset Array 
        for($i=0;$i<$courseArrayCount0;$i++){
            $classTypeTimeArray[$i] = $courseArray[$i][$classTypeSelected+3];//+2 Notice the DB class
            print "\$classTypeTimeArray[$i] = ".$classTypeTimeArray[$i]."<br />";
        }
        $classTypeTimeArrayCount0 = count($classTypeTimeArray);
    //--------------------------------------------------------------[ Repeat check and dorp
        print ("----------------------Repeat Check Debug Start-------------------------<br />");
        //Dorp the repeat in This semester and Last semester
        for($semesterSwich=0;$semesterSwich<2;$semesterSwich++){
            $semesterNameForCheckRepeat[0] = $semesterNameForCourseForm;//This semester DB class name
            $semesterNameForCheckRepeat[1] = "courseform".$semesterSet[$semesterSelected-1][0]."_".$semesterSet[$semesterSelected-1][1];
            
            for($i=0;$i<$courseArrayCount0;$i++){
                $SQLCheckRepeatInThisSemester = "SELECT course".$i."AM FROM ".$semesterNameForCheckRepeat[$semesterSwich]." WHERE course".$i."AM = 'W.$pickUpClass[0]'";
                print $SQLCheckRepeatInThisSemester."<br />";
                $queryResult = mysql_query($SQLCheckRepeatInThisSemester);
                $fetchRowResult = mysql_fetch_row($queryResult);
                print "[重复班级查找结果".$i."AM]fetchRowResult = ".$fetchRowResult."<br />";
                if($fetchRowResult){
                    unset($classTypeTimeArray[$i]);
                }
                $SQLCheckRepeatInThisSemester = "SELECT course".$i."PM FROM ".$semesterNameForCheckRepeat[$semesterSwich]." WHERE course".$i."PM = 'W.$pickUpClass[0]'";
                //print $SQLCheckRepeatInThisSemester."<br />";
                $queryResult = mysql_query($SQLCheckRepeatInThisSemester);
                $fetchRowResult = mysql_fetch_row($queryResult);
                print "[重复班级查找结果".$i."PM]fetchRowResult = ".$fetchRowResult."<br />";
                if($fetchRowResult){
                    unset($classTypeTimeArray[$i]);
                }
            }
        }
        print ("<br />----------------------Repeat Check Debug End-------------------------<br />");
        //Dorp the courseAccept runout course 
        //55555555555555555555555555555555555555555555555555555555555555555555555555555555555555...
    
    //--------------------------------------------------------------[ CoursePickUp and Optimize
    print("//--------------------------------------------------------------[ CoursePickUp and Optimize<br />");
   
        //StaticArray is Static and not unset Array
        $classTypeTimeArrayStatic = $classTypeTimeArray;
        //$classTypeTimeArrayDorpA = $classTypeTimeArray;//For first number
        //$classTypeTimeArrayDorpB = $classTypeTimeArray;//For second number
        //$classTypeTimeArrayDorpC = $classTypeTimeArray;//For third number
        //$classTypeTimeArrayCount = count($classTypeTimeArray); //It cause BugD
        $classTypeTimeArrayCount = $classTypeArrayCount;
     
        //rand for First select number
        unset ($firstNumber);
        print '[课程类型学时数组总计]classTypeTimeArrayCount = '.$classTypeTimeArrayCount."<br />";
        print("[课程类型学时数组内容]<br />");
        for($i=0;$i<$classTypeTimeArrayCount0;$i++){
            print "[$i] _ ".$classTypeTimeArray[$i]."<br />";
        }
        print("<br />[第一循环开始_随机选择第一课程]<br />");
        for($i=0;$i<$classTypeTimeArrayCount;$i++){
            print $classTypeTimeArrayCount."<br />";
            $firstNumberForSelect = mt_rand(0,($classTypeTimeArrayCount-1));
            print("<br />[第一随机数]\$firstNumberForSelect =". $firstNumberForSelect."<br />");
            //Repeat check AM
            $SQLQueryForAcceptFullAM = "SELECT course".$firstNumberForSelect."AM FROM ".$semesterNameForCourseForm." WHERE week = '1' && semesterWeek = '$weekControl'";
            print "[重复检测SQL语句] [".$SQLQueryForAcceptFullAM."] <br />";
            $queryResultAM = mysql_query($SQLQueryForAcceptFullAM);
            $queryResultAMCount0 = mysql_num_rows($queryResultAM);
            $dorpCounterAM = 0;
            for($DCC=0;$DCC<$queryResultAMCount0;$DCC++){
                $fetchRowResultAM = mysql_fetch_row($queryResultAM);
                if($fetchRowResultAM[0][$DCC] != ""){
                    $dorpCounterAM ++;
                }
            }
            //Repeat check PM
            $SQLQueryForAcceptFullPM = "SELECT course".$firstNumberForSelect."PM FROM ".$semesterNameForCourseForm." WHERE week = '1' && semesterWeek = '$weekControl'";
            print "[重复检测SQL语句] [".$SQLQueryForAcceptFullPM."] <br />";
            $queryResultPM = mysql_query($SQLQueryForAcceptFullPM);
            $queryResultPMCount0 = mysql_num_rows($queryResultPM);
            $dorpCounterPM = 0;
            for($DCC=0;$DCC<$queryResultPMCount0;$DCC++){
                $fetchRowResultPM = mysql_fetch_row($queryResultPM);
                if($fetchRowResultPM[0][$DCC] != ""){
                    $dorpCounterPM ++;
                }
            }
            
            print "[课程占位累加器]dorpCounterAM = ".$dorpCounterAM."<br />";
            print "[SQL请求结果计数器]\$queryResultAMCount0 = ".$queryResultAMCount0."<br />";
            print "[课程占位累加器]dorpCounterAM = ".$dorpCounterPM."<br />";
            print "[SQL请求结果计数器]\$queryResultPMCount0 = ".$queryResultPMCount0."<br />";
            if($classTypeTimeArray[$firstNumberForSelect]>0&&$dorpCounterAM<$courseArray[$firstNumberForSelect][1]&&$dorpCounterPM<$courseArray[$firstNumberForSelect][1]){
                $firstNumber = $firstNumberForSelect;
            }
        }
        print "<p style='color:#cc3333'>[\$firstNumber = ".$firstNumber."]</p><br />";
        unset($classTypeTimeArray[$firstNumber]);//Dorp first number
        //Output the optimize form for a week $selectNumberArray
        unset($selectNumberArray);//Reset Array
        $endSign = 0;
        $selectNumberArray[0] = $firstNumber; //Load firstNumber into SNA
        print("[第二次循环开始_建立区间标记]<br />");
        for($i=0;$i<$classTypeTimeArrayCount;$i++){
            print("<br />[第二循环数]\$secondNumber =". $i."<br />");
            if($endSign == 1){
                break;
            }
            //Repeat check
            //AM/PM Stamp
            if($classTypeTimeArrayStatic[$firstNumber]%2 == 0){
                $partStamp = "AM";
                $weekStamp = $classTypeTimeArrayStatic[$firstNumber]/2+1;
            }else{
                $partStamp ="PM";
                $weekStamp = floor($classTypeTimeArrayStatic[$firstNumber]/2)+1;
            }
            
            print "[区间标记]\$partStamp = ".$partStamp."<br />";
            $SQLQueryForAcceptFull = "SELECT course".$i."$partStamp FROM ".$semesterNameForCourseForm." WHERE week = '$weekStamp' && semesterWeek = '$weekControl'";
            print "[重复检测SQL语句][".$SQLQueryForAcceptFull."]<br />";
            $queryResult = mysql_query($SQLQueryForAcceptFull);
            $queryResultCount0 = mysql_num_rows($queryResult);
            $dorpCounter = 0;
            for($DCC=0;$DCC<$queryResultCount0;$DCC++){
                $fetchRowResult = mysql_fetch_row($queryResult);
                if($fetchRowResult[0][$DCC] != ""){
                    $dorpCounter ++;
                }
            }
            print "[课程占位累加器]\$dorpCounter = ".$dorpCounter."<br />";
            print "[SQL请求结果计数器]\$queryResultCount0 = ".$queryResultCount0."<br />";
            
            if($classTypeTimeArrayStatic[$firstNumber]+$classTypeTimeArrayStatic[$i]<=10&&$classTypeTimeArrayStatic[$firstNumber]+$classTypeTimeArrayStatic[$i]>$classTypeTimeArrayStatic[$firstNumber]&&$i!=$firstNumber&&$dorpCounter<$courseArray[$i][1]){
                $selectNumberArray[1] = $i; //Load secondNumber into SNA
                $classTypeTimeSum = $classTypeTimeArrayStatic[$firstNumber]+$classTypeTimeArrayStatic[$i];
                print("<p style='color:#cc3333'>[\$secondNumber = ".$i."]</p><br />");
                print('<div style="width: 200px;border:1px dashed;margin:2px;padding:2px;color:#09C;font-size:10px;">$selectNumberArray[0] = '.$selectNumberArray[0].'<br />$selectNumberArray[1] = '.$selectNumberArray[1]."</div>");
                unset($classTypeTimeArray[$i]);//Dorp Second number class
                print("<br />[第三次循环开始_建立区间标记]<br />");
                for($j=0;$j<$classTypeTimeArrayCount;$j++){
                    if($endSign == 1){
                        break;
                    }
                    //Repeat Check
                    
                    //AM/PM Stamp
                    if($classTypeTimeSum%2 == 0){
                        $partStamp = "AM";
                        $weekStamp = $classTypeTimeSum/2+1;
                    }else{
                        $partStamp ="PM";
                        $weekStamp = floor($classTypeTimeSum/2)+1;
                    }
            
                    print "[区间标记]\$partStamp = ".$partStamp."<br />";
                    $SQLQueryForAcceptFull = "SELECT course".$j."$partStamp FROM ".$semesterNameForCourseForm." WHERE week = '$weekStamp' && semesterWeek = '$weekControl'";
                    print "[重复检测SQL语句][".$SQLQueryForAcceptFull."]<br />";
                    $queryResult = mysql_query($SQLQueryForAcceptFull);
                    $queryResultCount0 = mysql_num_rows($queryResult);
                    $dorpCounter = 0;
                    for($DCC=0;$DCC<$queryResultCount0;$DCC++){
                        $fetchRowResult = mysql_fetch_row($queryResult);
                        if($fetchRowResult[0][$DCC] != ""){
                            $dorpCounter ++;
                        }
                    }
                    print "[课程占位累加器]\$dorpCounter = ".$dorpCounter."<br />";
                    print "[SQL请求结果计数器]\$queryResultCount0 = ".$queryResultCount0."<br />";
                                                                
                    if($classTypeTimeSum<$classTypeTimeSum + $classTypeTimeArrayStatic[$j] && $classTypeTimeSum + $classTypeTimeArrayStatic[$j]<=10&&$dorpCounter<$courseArray[$j][1]&&$classTypeTimeArray[$j]!=0){
                        $classTypeTimeSum = $classTypeTimeSum + $classTypeTimeArrayStatic[$j];
                        $selectNumberArray[2] = $j;
                        unset($classTypeTimeArray[$j]);//Drop third number class
                        print("<br />[第4次循环开始_建立区间标记]<br />");
                        for($k=0;$k<$classTypeTimeArrayCount;$k++){
                            if($endSign == 1){
                                break;
                            }
                            //Repeat Check
                    
                            //AM/PM Stamp
                            if($classTypeTimeSum%2 == 0){
                                $partStamp = "AM";
                                $weekStamp = $classTypeTimeSum/2+1;
                            }else{
                                $partStamp ="PM";
                                $weekStamp = floor($classTypeTimeSum/2)+1;
                            }
            
                            print "[区间标记]\$partStamp = ".$partStamp."<br />";
                            $SQLQueryForAcceptFull = "SELECT course".$k."$partStamp FROM ".$semesterNameForCourseForm." WHERE week = '$weekStamp' && semesterWeek = '$weekControl'";
                            print "[重复检测SQL语句][".$SQLQueryForAcceptFull."]<br />";
                            $queryResult = mysql_query($SQLQueryForAcceptFull);
                            $queryResultCount0 = mysql_num_rows($queryResult);
                            $dorpCounter = 0;
                            for($DCC=0;$DCC<$queryResultCount0;$DCC++){
                                $fetchRowResult = mysql_fetch_row($queryResult);
                                if($fetchRowResult[0][$DCC] != ""){
                                    $dorpCounter ++;
                                }
                            }
                            print "[课程占位累加器]\$dorpCounter = ".$dorpCounter."<br />";
                            print "[SQL请求结果计数器]\$queryResultCount0 = ".$queryResultCount0."<br />";
                            
                            if($classTypeTimeSum<$classTypeTimeSum + $classTypeTimeArrayStatic[$k] && $classTypeTimeSum + $classTypeTimeArrayStatic[$k]<=10&&$dorpCounter<$courseArray[$k][1]&&$classTypeTimeArray[$k]!=0){
                                $classTypeTimeSum = $classTypeTimeSum + $classTypeTimeArrayStatic[$k];
                                $selectNumberArray[3] = $k;
                                unset($classTypeTimeArray[$k]);//Dorp forth number class
                                print("<br />[第5次循环开始_建立区间标记]<br />");
                                for($l=0;$l<$classTypeTimeArrayCount;$l++){
                                    if($endSign == 1){
                                        break;
                                    }
                                    //Repeat Check
                    
                                    //AM/PM Stamp
                                    if($classTypeTimeSum%2 == 0){
                                        $partStamp = "AM";
                                        $weekStamp = $classTypeTimeSum/2+1;
                                    }else{
                                        $partStamp ="PM";
                                        $weekStamp = floor($classTypeTimeSum/2)+1;
                                    }
            
                                    print "[区间标记]\$partStamp = ".$partStamp."<br />";
                                    $SQLQueryForAcceptFull = "SELECT course".$l."$partStamp FROM ".$semesterNameForCourseForm." WHERE week = '$weekStamp' && semesterWeek = '$weekControl'";
                                    print "[重复检测SQL语句][".$SQLQueryForAcceptFull."]<br />";
                                    $queryResult = mysql_query($SQLQueryForAcceptFull);
                                    $queryResultCount0 = mysql_num_rows($queryResult);
                                    $dorpCounter = 0;
                                    for($DCC=0;$DCC<$queryResultCount0;$DCC++){
                                        $fetchRowResult = mysql_fetch_row($queryResult);
                                        if($fetchRowResult[0][$DCC] != ""){
                                            $dorpCounter ++;
                                        }
                                    }
                                    print "[课程占位累加器]\$dorpCounter = ".$dorpCounter."<br />";
                                    print "[SQL请求结果计数器]\$queryResultCount0 = ".$queryResultCount0."<br />";
                                    if($classTypeTimeSum<$classTypeTimeSum + $classTypeTimeArrayStatic[$l] && $classTypeTimeSum + $classTypeTimeArrayStatic[$l]<=10&&$dorpCounter<$courseArray[$k][1]&&$classTypeTimeArray[$l]!=0){
                                        $classTypeTimeSum = $classTypeTimeSum + $classTypeTimeArray[$l];
                                        $selectNumberArray[4] = $l;
                                        unset($classTypeTimeArray[$l]);//Dorp fiveth number class
                                    }
                                    if($l == $classTypeTimeArrayCount-1){
                                        $endSign = 1;
                                    }
                                    
                                    
                                }
                                
                            }
                            if($k == $classTypeTimeArrayCount-1){
                                $endSign = 1;
                            }
                        }
                    }
                    if($j == $classTypeTimeArrayCount-1){
                                $endSign = 1;
                            }   
                }                
            }  
        }
        $countA = count($selectNumberArray);
        for($i=0;$i<$countA;$i++){
            print "selectNumberArray $i = ".$selectNumberArray[$i]."<br />";
        }
        
    //--------------------------------------------------------------[ Write to database
        
        $selectNumberArrayCount = count($selectNumberArray);
        //Query the number of course to add
        unset($courseFormFilled);
        unset($courseFormFilledIDArray);
        for($i=0;$i<$selectNumberArrayCount;$i++){
            $courseFormFilled[$i][0] = $selectNumberArray[$i];
            $courseFormFilled[$i][1] = $courseArray[$selectNumberArray[$i]][$classTypeSelected+3];
        }
        $courseFormFilledCount0 = count($courseFormFilled);
        //Make the array like the numberOrder array
        for($i=0;$i<$courseFormFilledCount0;$i++){
            for($j=0;$j<$courseFormFilled[$i][1];$j++){
                $courseFormFilledIDArray[] = $courseFormFilled[$i][0];
            }
        }
        //Define the class headNumber
        $courseFormFilledIDArrayCount0 = count($courseFormFilledIDArray);
        $classHeadNumberCounter = 1;
            //Add the "Fin" sign for final
            $classTypeTimeArrayCount0 = count ($classTypeTimeArray);
            
        for($i=0;$i<$courseFormFilledIDArrayCount0;$i++){
            print "[+] ".$courseFormFilledIDArray[$i].'<br />';
            if($classTypeTimeArrayCount0 == 0&&$i==($courseFormFilledIDArrayCount0-1)){
                $classHeadNumberSign = "F";
            }else{
                $classHeadNumberSign = "W";
            }
            if($i==0&&$courseFormFilledIDArray[$i] != $courseFormFilledIDArray[$i+1]||$i==($courseFormFilledIDArrayCount0-1)){
                $classHeadNumber[$i] = $classHeadNumberSign;
                $classHeadNumberCounter = 1;
            }elseif($courseFormFilledIDArray[$i-1] == $courseFormFilledIDArray[$i]&&$courseFormFilledIDArray[$i] != $courseFormFilledIDArray[$i+1]){
                $classHeadNumber[$i] = $classHeadNumberSign;
                $classHeadNumberCounter = 1;
            }elseif($courseFormFilledIDArray[$i-1] != $courseFormFilledIDArray[$i] && $courseFormFilledIDArray[$i] != $courseFormFilledIDArray[$i+1]){
                $classHeadNumber[$i] = $classHeadNumberSign;
                $classHeadNumberCounter = 1;   
            }elseif($courseFormFilledIDArray[$i-1] != $courseFormFilledIDArray[$i]){
                $classHeadNumberCounter = 1;
                $classHeadNumber[$i] = $classHeadNumberCounter;
                $classHeadNumberCounter++;
            }elseif($courseFormFilledIDArray[$i-1] == $courseFormFilledIDArray[$i]||$i==0){
                $classHeadNumber[$i] = $classHeadNumberCounter;
                $classHeadNumberCounter++; 
            }
            
        }
        //Write to the database
        for($i=0;$i<5;$i++){
            $weekToInsert = $i + 1;
            $courseToInsert[0] = "course".$courseFormFilledIDArray[$i+$i]."AM";
            $courseToInsert[1] = "course".$courseFormFilledIDArray[$i+$i+1]."PM";
            $headNumberToInsert[0] = $classHeadNumber[$i+$i];
            $headNumberToInsert[1] = $classHeadNumber[$i+$i+1];
            $SQLAddClassToCourseForm = "INSERT INTO classsheet.$semesterNameForCourseForm (semesterWeek,week,$courseToInsert[0],$courseToInsert[1]) VALUES ('$weekControl','$weekToInsert','$headNumberToInsert[0].$pickUpClass[0]','$headNumberToInsert[1].$pickUpClass[0]')";
            print $SQLAddClassToCourseForm."<br />";
            mysql_query($SQLAddClassToCourseForm);
        }
    
        
        
       
        print "------------------------------------------------------------<br />";
    }

    //--------------------------------------------------------------[ Optimize database
    
    //Select course and Load in Array
    $shiftCounter = 0;
    $weekCounter = 0;
    $roundCounter = 0;
    for($weekRound=0;$weekRound<5/*Change to "5" */;$weekRound++){
        $weekRoundInsert = $weekRound+1;        
        //Reunion SQL Syntax for query from database AND fill in $optimizeCourseFormArray
        $SQLSelectCourse = "SELECT semesterWeek,week";
        for($coursePickupSQLQueryCounter=0;$coursePickupSQLQueryCounter<$courseArrayCount0;$coursePickupSQLQueryCounter++){
            $SQLSelectCourse = $SQLSelectCourse.",course".$coursePickupSQLQueryCounter."AM,course".$coursePickupSQLQueryCounter."PM";
        }
        $SQLSelectCourse = $SQLSelectCourse." FROM $semesterNameForCourseForm WHERE semesterWeek = $semesterWeekSelected && week = $weekRoundInsert";
        //print("\$SQLSelectCourse = ".$SQLSelectCourse."<br />");
        //Query from Database
        $queryResult = mysql_query($SQLSelectCourse);
        $queryResultCount0 = mysql_num_rows($queryResult);
        //print "\$queryResultCount0 = ".$queryResultCount0."<br />";
        //Load into Array
        for($i=0;$i<$queryResultCount0/*It's 12'*/;$i++){
            $fetchRowResult = mysql_fetch_row($queryResult);
            //Input course into Array
            for($j=2;$j<$courseArrayCount0+$courseArrayCount0+2/*It's 18'*/;$j++){
                //print("[".$fetchRowResult[$j]."]<br />");
               if($fetchRowResult[$j] != ""){
                    //SHIFT CAL 
                    if($shiftCounter>0 && $optimizeCourseFormArray[$shiftCounter-1][$j] == "" && $optimizeCourseFormArray[$shiftCounter-1][1] == $optimizeCourseFormArray[$shiftCounter][1] && $j!=1){
                        $optimizeCourseFormArray[$shiftCounter-1][$j] = $fetchRowResult[$j];
                        //print ("[$shiftCounter-1"."_".$j."]".$optimizeCourseFormArray[$shiftCounter-1][$j]."<br />");
                    }elseif($optimizeCourseFormArray[$shiftCounter][$j] == ""){
                        $optimizeCourseFormArray[$shiftCounter][$j] = $fetchRowResult[$j];
                        //print ("[$shiftCounter"."_".$j."]".$optimizeCourseFormArray[$shiftCounter][$j]."<br />");
                    }elseif($optimizeCourseFormArray[$shiftCounter][$j] != "" && $optimizeCourseFormArray[$shiftCounter][$j]!= $fetchRowResult[$j]){
                        $shiftCounter ++;
                        $optimizeCourseFormArray[$shiftCounter][$j] = $fetchRowResult[$j];
                        //print ("[$shiftCounter"."_".$j."]".$optimizeCourseFormArray[$shiftCounter][$j]."<br />");
                    }
                }   
            }
        }
        $shiftCounter ++;
        //Input courseWeek,week into Array     
        $optimizeCourseFormArrayCount0 = count($optimizeCourseFormArray);
        //print("\$optimizeCourseFormArrayCount0 = ".$optimizeCourseFormArrayCount0."<br />");
        //print("\$weekCounter = ".$weekCounter."<br />");
        $roundCounter = 0;
        for($i=$weekCounter;$i<$optimizeCourseFormArrayCount0;$i++){
            $optimizeCourseFormArray[$i][0] = $semesterWeekSelected;
            $optimizeCourseFormArray[$i][1] = $weekRoundInsert;
            $roundCounter ++;
        }
        $weekCounter = $weekCounter + $roundCounter;
        //print "<br />";            
    }
    //Dorp old data from database
    $SQLDeleteItem = "DELETE FROM classsheet.$semesterNameForCourseForm WHERE semesterWeek = $weekControl";
    mysql_query($SQLDeleteItem);
    //Write Array to database
    $optimizeCourseFormArrayCount0 = count($optimizeCourseFormArray);
        for($j=0;$j<$optimizeCourseFormArrayCount0;$j++){
            $SQLSelectCourseForm = "INSERT INTO classsheet.$semesterNameForCourseForm (semesterWeek,week";
            for($i=0;$i<$courseArrayCount0;$i++){
                $SQLSelectCourseForm = $SQLSelectCourseForm.",course$i"."AM,course$i"."PM";
            }
            //$courseFormArrayTempCountATL = count($courseFormArrayTemp[0]);
            $semesterWeekToInsert = $optimizeCourseFormArray[$j][0];
            $weekToInsert = $optimizeCourseFormArray[$j][1];
            $SQLSelectCourseForm = $SQLSelectCourseForm.") VALUES ('$semesterWeekToInsert','$weekToInsert'";
            for($k=0;$k<($courseArrayCount0+$courseArrayCount0);$k++){
                $pickUpClass[$k] = $optimizeCourseFormArray[$j][$k+2];
                $SQLSelectCourseForm = $SQLSelectCourseForm.",'$pickUpClass[$k]'";
            }
            $SQLSelectCourseForm = $SQLSelectCourseForm.")";
            //print "[Write to database]".$j."[".$SQLSelectCourseForm."<br />";
            mysql_query($SQLSelectCourseForm);
        }
    
    
    
    /*---------------------------------------------------------------------------------------------------------------------------------------*/ 
}

print "</div>";

//Reload the $courseFormArray
    $courseFormArray = queryCourseForm($semesterNameForCourseForm,$courseArrayCount0,$semesterWeekSelected);
    $courseFormArrayCount0 = count($courseFormArray);


mysql_close($DBConnect);

?>


<div class="title">
<h2>总课程表输出</h2>
<div class="underline"></div>
<h6>All &nbsp;Course &nbsp; List &nbsp;Output</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="courseForm.php" method="post">
<input type="hidden" name="semesterListSelected" value="yes" />
<p>学年度:</p>
<select name="semesterList" size="10">
<?php
for($i=0;$i<$semesterSetCount0;$i++){

?>
<option value="<?php print($i);?>"><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
<?php
}

?>
</select><br />
<input type="submit" value="选定" style="margin-top: 10px;" /> 
</form>
</div>
<?php
}
///////////////////////////////////////////////////////////////////////////////////// - After post
if($_POST["semesterListSelected"] == "yes"){
?>
<form action="courseForm.php" method="post">
    <div style="float: left;">
        <input type="hidden" name="semesterListSelected" value="yes" />
         <p>学年度:</p>
         <select name="semesterList" size="10">
          <?php
          for($i=0;$i<$semesterSetCount0;$i++){

           ?>
       <option value="<?php print($i);?>"<?php if($i == $_POST["semesterList"]){print("selected");}?>><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
          <?php
          }
          ?>
         </select><br />
         <input type="submit" value="选定" style="margin-top: 10px;" /> 
     </div>




<div style="float:left; margin: 0 0 0 10px;"><span>第<input type="text" name="semesterWeekSelected" maxlength="2" size="2" value="<?php print($semesterWeekSelected);?>" />周总课程表<input type="submit" value="确认" style="margin-top: 10px;" /></span>
<table id="tableId" cellspacing="0">
<tr>
<td class="Thead" align="center" style="border-bottom:1px solid #000;">工种</td>
<?php
for($i=0;$i<$courseArrayCount0;$i++){
    print('<th colspan="2" align="center" width="160" style="border-left:1px solid #000;border-bottom:1px solid #000;" >'.$courseArray[$i][0].'</th>');
}

 ?>
</tr>
<tr>
<td class="Thead" align="center">课节</td>
<?php
for($i=0;$i<$courseArrayCount0;$i++){
    print('<td align="center" class="borderedTd" >1234</td>');
    print('<td align="center" class="borderedTd" >5678</td>');
}
?>
</tr>
<!-- Read the array and output -->
<?php
//if(!$_POST["reformCourse"]){
    for($i=0;$i<$courseFormArrayCount0;$i++){
    
        if($courseFormArray[$i][1] != $courseFormArray[$i-1][1]){
            print('<tr style="background:#09c;">');
            for($j=0;$j<($courseArrayCount0+$courseArrayCount0+1);$j++){
                print('<td style="height:1px;background:#cc3333;"></td>');
            }
            print('</tr><td class="angle Thead">&nbsp;周'.$courseFormArray[$i][1].'<input type="hidden" name="i'.$i.'j1fin" value="'.$courseFormArray[$i][1].'" /></td>');
        }else{
            print('<tr>');
            print('<td class="Thead"><input type="hidden" name="i'.$i.'j1fin" value="'.$courseFormArray[$i][1].'" /></td>');
        }
        for($j=2;$j<($courseArrayCount0+$courseArrayCount0+2);$j++){
                print('<td class="borderedTd">'.$courseFormArray[$i][$j].'<input type="hidden" name="i'.$i.'j'.$j.'fin" value="'.$courseFormArray[$i][$j].'" /> </td>');    
        }
        print('</tr>');
    }
//}



?>



</table>


<input type="submit" value="排列课程并保存" name="reformCourse" style="margin-top: 10px;" /><input type="submit" value="保存手动调节结果" name="saveManualCourseForm" style="margin-top: 10px;" /><input type="submit" value="删除排列结果" name="deleteCourseForm" style="margin-top: 10px;" />
</form>
<?php
}
//////////////////////////

?>
</div>
</div>
</div>
</body>
</html>