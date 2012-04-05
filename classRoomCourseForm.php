<?php

/**
 * @author Linfcstmr
 * @copyright 2011
 */

$pageSwich = 8;
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
$CRCFtableName = "classRoomCourseForm".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
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
print "\$courseArrayCount0 = ".$courseArrayCount0."<br />";
for($i=0;$i<$courseArrayCount0;$i++){
    $courseArrayCount1[] = count($courseArray[$i]);
    
}

//**Load the $courseFormArray
$courseFormArray = queryCourseForm($semesterNameForCourseForm,$courseArrayCount0,$semesterWeekSelected);

//Count the $courseFormArray array
$courseFormArrayCount0 = count($courseFormArray);
print "\$courseFormArrayCount0 = ".$courseFormArrayCount0."<br />";

//Load the classRoomArray
$tableName = "classRoom".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
$classRoomArrayTemp = queryClassRoomArray($tableName);
    //Reunion the $classRoomArray follow the courseID
    $classRoomArrayCount0 = count($classRoomArrayTemp);
    for($i=0;$i<$classRoomArrayCount0;$i++){
        for($j=0;$j<4;$j++){
            $serialNumber = $classRoomArrayTemp[$i][3];
            $classRoomArray[$serialNumber][$j] = $classRoomArrayTemp[$i][$j];
        }   
    }
    
    
//Load the CRCF


//Load the $prelession [ Source from Database ]
    for($i=0;$i<$courseArrayCount0;$i++){
        $SQLQueryClassByWeek = "SELECT course$i"."AM FROM ".$semesterNameForCourseForm." WHERE week='1' && semesterWeek = '$semesterWeekSelected'";
        $queryResult = mysql_query($SQLQueryClassByWeek);
        $queryResultCount0 = mysql_num_rows($queryResult);
        for($j=0;$j<$queryResultCount0;$j++){
            $fetchRowResult = mysql_fetch_row($queryResult);
            //print $fetchRowResult[0]."<br />";
            if($fetchRowResult[0] != ""){
                $prelessionArray[] = $fetchRowResult[0];    
            }
        }
    }
    //Dorp the repeat Prelession
    $prelessionArrayCount0 = count($prelessionArray);
    //print $prelessionArrayCount0."<br />";
    //Dorp the repeat in This semester and Last semester
        for($semesterSwich=0;$semesterSwich<2;$semesterSwich++){
            $semesterNameForCheckRepeat[0] = $semesterNameForCourseForm;//This semester DB class name
            $semesterNameForCheckRepeat[1] = "courseform".$semesterSet[$semesterSelected-1][0]."_".$semesterSet[$semesterSelected-1][1];
            for($i=0;$i<$prelessionArrayCount0;$i++){
                $repeatCheckPrelession = $prelessionArray[$i];
                if($semesterSwich == 0){
                    for($j=1;$j<$semesterWeekSelected;$j++){
                        $weekToSearch = $j;
                        for($k=0;$k<$courseArrayCount0;$k++){
                            $SQLCheckRepeatInThisSemester = "SELECT course$k"."AM FROM ".$semesterNameForCheckRepeat[$semesterSwich]." WHERE course$k"."AM = '$repeatCheckPrelession' && week='1' && semesterWeek = '$weekToSearch'";
                            //print $SQLCheckRepeatInThisSemester."<br />";
                            $queryResult = mysql_query($SQLCheckRepeatInThisSemester);
                            $fetchRowResult = mysql_fetch_row($queryResult);
                            //print "[本学期重复查找结果 课程".$i." 第".$j."周 第".$k."课程]fetchRowResult = ".$fetchRowResult."<br />";
                            if($fetchRowResult){
                                unset($prelessionArray[$i]);
                            }    
                        } 
                    }    
                }else{
                    for($j=1;$j<=$semesterWeek;$j++){
                        $weekToSearch = $j;
                        for($k=0;$k<$courseArrayCount0;$k++){
                            $SQLCheckRepeatInThisSemester = "SELECT course$k"."AM FROM ".$semesterNameForCheckRepeat[$semesterSwich]." WHERE course$k"."AM = '$repeatCheckPrelession' && week='1' && semesterWeek = '$weekToSearch'";
                            //print $SQLCheckRepeatInThisSemester."<br />";
                            $queryResult = mysql_query($SQLCheckRepeatInThisSemester);
                            $fetchRowResult = mysql_fetch_row($queryResult);
                            //print "[上学期重复查找结果 课程".$i." 第".$j."周 第".$k."课程]fetchRowResult = ".$fetchRowResult."<br />";
                            if($fetchRowResult){
                                unset($prelessionArray[$i]);
                            }    
                        } 
                    } 
                }
                 
            }
        }
    //Recount $prelessionArray
    foreach($prelessionArray as $prelessionArrayValue){
        if($prelessionArrayValue != ""){
            $prelessionArrayTemp[] = $prelessionArrayValue;
        }
    }
    $prelessionArray = $prelessionArrayTemp;
    $prelessionArrayCount0 = count($prelessionArray);
    
    for($i=0;$i<$prelessionArrayCount0;$i++){
        print $prelessionArray[$i]."<br />";
    }


//Load the $therylessionArray [ Source on $courseFromArray ]
    for($i=0;$i<$courseFormArrayCount0;$i++){
        $therylessionArray[$i][0] = $courseFormArray[$i][0];
        $therylessionArray[$i][1] = $courseFormArray[$i][1];
        for($j=2;$j<$courseArrayCount0+$courseArrayCount0+2;$j++){
            $courseFormArrayExplode = explode(".", $courseFormArray[$i][$j]);
            if($courseFormArrayExplode[0] == 1){
                $therylessionArray[$i][$j] = $courseFormArray[$i][$j];
                //print $therylessionArray[$i][$j]."<br />";
            }
        }
    }
    
//Load the $finallessionArray [ Source on $courseFromArray ]
    unset($finallessionArray);
    for($i=0;$i<$courseFormArrayCount0;$i++){
        for($j=2;$j<$courseArrayCount0+$courseArrayCount0+2;$j++){
            $courseFormArrayExplode = explode(".", $courseFormArray[$i][$j]);
            if($courseFormArrayExplode[0] == "F"){
                $finallessionArray[] = $courseFormArrayExplode[1];
            }
        }
    }
    $finallessionArrayCount = count ($finallessionArray);
    print("--FINAL-- ".$finallessionArrayCount.$finallessionArray[0]."<br />");
    //Load the classType [$finallessionClassTypeArray]
    unset($finallessionClassTypeArray);//Destory the Array
    for($i=0;$i<$finallessionArrayCount;$i++){
        $SQLQueryClassType = "SELECT classType FROM $semesterName WHERE className = '$finallessionArray[$i]'";
        print $SQLQueryClassType."<br />";
        $queryResult = mysql_query($SQLQueryClassType);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $finallessionClassTypeArray[$i] = $fetchRowResult[0];        
    }
    print "\$finallessionClassTypeArray[0] = ".$finallessionClassTypeArray[0]."<br />";
    //Load the courseTypeTime [$finallessionCourseTypeTimeArray]
    for($i=0;$i<$finallessionArrayCount;$i++){
        $classTypeForQuery = $finallessionClassTypeArray[$i]; 
        $SQLQueryCourseTypeTimeA = "SELECT $classTypeForQuery FROM $courseName WHERE courseName = '工艺设计' ";
        print $SQLQueryCourseTypeTimeA."<br />";
        $queryResult = mysql_query($SQLQueryCourseTypeTimeA);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $finallessionCourseTypeTimeArray[$i][0] = $fetchRowResult[0];
        
        $SQLQueryCourseTypeTimeB = "SELECT $classTypeForQuery FROM $courseName WHERE courseName = '考试' ";
        print $SQLQueryCourseTypeTimeB."<br />";
        $queryResult = mysql_query($SQLQueryCourseTypeTimeB);
        $fetchRowResult = mysql_fetch_row($queryResult);
        $finallessionCourseTypeTimeArray[$i][1] = $fetchRowResult[0]; 
    }
    print "\$finallessionCourseTypeTimeArray[0][0] = ".$finallessionCourseTypeTimeArray[0][0]."<br />";
    print "\$finallessionCourseTypeTimeArray[0][1] = ".$finallessionCourseTypeTimeArray[0][1]."<br />";

//Create $classRoomCourseFormArray and load Data

    //Load the prelession
    $prelessionArray;
    for($i=0;$i<$prelessionArrayCount0;$i++){
        if($i<4){
            if($i==0){
                $classRoomCourseFormArray[$i][10] = $prelessionArray[$i]; 
            }else{
                $classRoomCourseFormArray[$i][10] = $prelessionArray[$i];  
            }
        }elseif($i<8){
            if($i==4){
                $classRoomCourseFormArray[$i-4][14] = $prelessionArray[$i];
            }else{
                $classRoomCourseFormArray[$i-4][14] = $prelessionArray[$i];
            }
        }else{
            if($i==8){
                $classRoomCourseFormArray[$i-8][26] = $prelessionArray[$i];
            }else{
                $classRoomCourseFormArray[$i-8][26] = $prelessionArray[$i];
            }
        }
    }
    //Load the semesterWeek
    $classRoomCourseFormArrayCount0 = count($classRoomCourseFormArray);
    for($i=0;$i<$classRoomCourseFormArrayCount0;$i++){
        $classRoomCourseFormArray[$i][0] = $semesterWeekSelected;
        $classRoomCourseFormArray[$i][1] = 1;
    }
    
    //Load the therylession
    print "\$courseFormArrayCount0 = ".$courseFormArrayCount0."<br />";
    for($i=0;$i<$courseFormArrayCount0;$i++){
        for($j=2;$j<$courseArrayCount0+$courseArrayCount0+2;$j++){
            if($i<3){//Bug if remove !!!!!!!
                unset ($compairCounter);
                for($k=0;$k<$prelessionArrayCount0;$k++){
                    if($prelessionArray[$k] == $therylessionArray[$i][$j]){
                        if($i==0){
                            $classRoomCourseFormArray[$i][$j+$j-1] = $therylessionArray[$i][$j];
                        }else{
                            $classRoomCourseFormArray[$i][$j+$j-1] = $therylessionArray[$i][$j];
                        }
                    }
                    if($prelessionArray[$k] != $therylessionArray[$i][$j] && $therylessionArray[$i][$j] != ""){
                        $compairCounter ++;           
                    }
                    if($compairCounter == $prelessionArrayCount0){
                        $classRoomCourseFormArray[$i][$j+$j-2] = $therylessionArray[$i][$j];
                    }
                }
            }else{
                $classRoomCourseFormArray[$i+1][$j+$j-2] = $therylessionArray[$i][$j];
                $classRoomCourseFormArray[$i+1][1] = $courseFormArray[$i][1];//Load week info
            }
            
        }
    }
    //Load the $finallession
    $classRoomCourseFormArrayCount0 = count($classRoomCourseFormArray);
    $weekSwitch = 0;
    for($i=0;$i<$classRoomCourseFormArrayCount0;$i++){
        if($classRoomCourseFormArray[$i][1]== 5 && $weekSwitch == 0){
            $weekSwitch = 1;
            for($j=0;$j<$finallessionArrayCount;$j++){
                if($finallessionCourseTypeTimeArray[$j][0]== 4 && $finallessionCourseTypeTimeArray[$j][1]== 2){
                    $classRoomCourseFormArray[$i][10] = "工艺设计<br />".$finallessionArray[$j];
                    $classRoomCourseFormArray[$i][11] = "工艺设计<br />".$finallessionArray[$j];
                    $classRoomCourseFormArray[$i][12] = "考试<br />".$finallessionArray[$j];
                }elseif($finallessionCourseTypeTimeArray[$j][0]== 2){
                    $classRoomCourseFormArray[$i][10] = "工艺设计<br />".$finallessionArray[$j];
                }elseif($finallessionCourseTypeTimeArray[$j][1]== 4){
                    $classRoomCourseFormArray[$i][10] = "考试<br />".$finallessionArray[$j];
                }
            }
        }
        
    }
    
    //Write into Database
    $classRoomCourseFormArrayCount0 = count($classRoomCourseFormArray);
    $classRoomCourseFormArrayCount1 = $courseArrayCount0*4+2;
        //Create table
        $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $CRCFtableName (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),semesterWeek int,week int";
        for($i=0;$i<($classRoomCourseFormArrayCount1-2)/4;$i++){
            $SQLCreateTable = $SQLCreateTable.",course$i"."_12 varchar(15),course$i"."_34 varchar(15),course$i"."_56 varchar(15),course$i"."_78 varchar(15)";
        }
        $SQLCreateTable = $SQLCreateTable.",course_12 varchar(15), course_34 varchar(15), course_56 varchar(15), course_78 varchar(15))";
        //print $SQLCreateTable."<br />";
        mysql_query($SQLCreateTable);
        //Dorp Old Data
        $SQLDeleteItem = "DELETE FROM classsheet.$CRCFtableName WHERE semesterWeek = $semesterWeekSelected";
        //print $SQLDeleteItem."<br />";
        mysql_query($SQLDeleteItem);
        //Add data
        for($i=0;$i<$classRoomCourseFormArrayCount0-2;$i++){
            $SQLAddClassRoomCourseForm = "INSERT INTO $CRCFtableName (semesterWeek,Week";
            for($j=0;$j<($classRoomCourseFormArrayCount1-2)/4;$j++){
                $SQLAddClassRoomCourseForm = $SQLAddClassRoomCourseForm.",course$j"."_12,course$j"."_34,course$j"."_56,course$j"."_78";
            }
            $SQLAddClassRoomCourseForm = $SQLAddClassRoomCourseForm.") VALUES ('$semesterWeekSelected'";
            for($j=1;$j<$classRoomCourseFormArrayCount1;$j++){
                $SQLAddClassRoomCourseForm = $SQLAddClassRoomCourseForm.",'".$classRoomCourseFormArray[$i][$j]."'";
            }
            $SQLAddClassRoomCourseForm = $SQLAddClassRoomCourseForm.")";
            //print $SQLAddClassRoomCourseForm."<br />";
            mysql_query($SQLAddClassRoomCourseForm);
        }
        
//Delete the CRCF data
if($_POST[deleteClassRoomCourseForm]){
    $SQLDeleteItem = "DELETE FROM classsheet.$CRCFtableName WHERE semesterWeek = $semesterWeekSelected";
    mysql_query($SQLDeleteItem);
}
    
    

mysql_close($DBConnect);
?>



<div class="title">
<h2>教室课程表输出</h2>
<div class="underline"></div>
<h6>Class&nbsp;Room&nbsp;Course&nbsp;List&nbsp;Output</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="classRoomCourseForm.php" method="post">
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
<form action="classRoomCourseForm.php" method="post">
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




<div style="float:left; margin: 0 0 0 10px;"><span>第<input type="text" name="semesterWeekSelected" maxlength="2" size="2" value="<?php print($semesterWeekSelected);?>" />周教室课程表<input type="submit" value="确认" style="margin-top: 10px;" /></span>
<table  cellspacing="0">
<tr>
<td class="Thead" align="center" style="border-bottom:1px solid #000;">教室</td>
<?php
for($i=0;$i<$classRoomArrayCount0;$i++){
    
        print('<th colspan="4" align="center" width="160" style="border-left:1px solid #000;border-bottom:1px solid #000;"  >'.$classRoomArray[$i][0].'</th>');        

}

 ?>
</tr>
<tr>
<td class="Thead" align="center">课节</td>
<?php
for($i=0;$i<$classRoomArrayCount0;$i++){
        print('<td align="center" class="borderedTd" >12</td>');
        print('<td align="center" class="borderedTd" >34</td>');
        print('<td align="center" class="borderedTd" >56</td>');
        print('<td align="center" class="borderedTd" >78</td>');
}
?>
</tr>
<!-- Read the array and output -->
<?php
//if(!$_POST["reformCourse"]){
    for($i=0;$i<$courseFormArrayCount0;$i++){
        //print('<tr>');
        if($classRoomCourseFormArray[$i][1] != $classRoomCourseFormArray[$i-1][1]){
            print('<tr style="background:#09c;">');
            for($j=0;$j<(($courseArrayCount0+$courseArrayCount0)*2+2);$j++){
                print('<td style="height:1px;background:#cc3333;"></td>');
            }
            print('</tr><tr><td class="angle Thead">&nbsp;周'.$classRoomCourseFormArray[$i][1].'<input type="hidden" name="i'.$i.'j1fin" value="'.$courseFormArray[$i][1].'" /></td>');
        }else{
            print('<tr><td class="Thead" ><input type="hidden" name="i'.$i.'j1fin" value="'.$courseFormArray[$i][1].'" /></td>');
        }
        for($j=2;$j<(($courseArrayCount0+$courseArrayCount0)*2+2);$j++){
            print('<td class="borderedTd">'.$classRoomCourseFormArray[$i][$j].'<input type="hidden" name="i'.$i.'j'.$j.'fin" value="'.$classRoomCourseFormArray[$i][$j].'" /> </td>');    
        }
        print('</tr>');
    }
//}



?>



</table>


<input type="submit" value="重新获取教室课程表" name="reformClassRoomCourseForm" style="margin-top: 10px;" /><input type="submit" value="删除记录" name="deleteClassRoomCourseForm" style="margin-top: 10px;" />
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