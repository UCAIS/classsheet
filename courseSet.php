<?php

/**
 * Course information set
 * @author Linfcstmr
 * @copyright 2011
 * 
 * Serial:110726
 * 
 */
$pageSwich =2;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

//Load the semesterName which semester selected
$semesterSelected = $_POST["semesterList"];
$semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
//$weekCount = $semesterSet[$semesterSelected][2];

//Load the $classTypeArray
$classTypeArray = queryClassType($semesterName);

//Load the $courseArray
if($_POST["semesterListSelected"] == "yes"){
    //Create table of course about semester if NOT exists
    $semesterSelected = $_POST["semesterList"];
    $courseName = "course".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $courseName (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),courseName varchar(15),courseAccept int,courseType varchar(15)";
    //Cal the classType add to database
    $classTypeArrayCount = count($classTypeArray);
    for($i=0;$i<$classTypeArrayCount;$i++){
        $SQLCreateTable = $SQLCreateTable.",".$classTypeArray[$i]." int";
    }
    $SQLCreateTable = $SQLCreateTable.")";
    mysql_query($SQLCreateTable,$DBConnect);
    
    //Load the $courseArray
    $courseArray = queryCourseInfo($courseName,$classTypeArray);

}

//Add the course data

if($_POST["courseInfoAdd"]){
  
    $SQLAddSemester="INSERT INTO $courseName (courseName,courseAccept,courseType) VALUES ('$_POST[courseName]','$_POST[courseAccept]','$_POST[courseType]')";
    mysql_query($SQLAddSemester);
    //ReLoad the $courseArray
     $courseArray = queryCourseInfo($courseName,$classTypeArray);
    
    
}

//Delete the course data

if($_POST["courseListDelete"]){
    //Change serialNumber to ID
    $courseID = $_POST["courseList"];
    $deleteID = $courseArray[$courseID][$classTypeArrayCount+3];    
    $SQLDeleteItem = "DELETE FROM classsheet.$courseName WHERE $courseName.ID=$deleteID";
    mysql_query($SQLDeleteItem);
    //ReLoad the $courseArray
     $courseArray = queryCourseInfo($courseName,$classTypeArray);
}

//Rewrite the course data

if($_POST["courseInfoChange"]){
    //Change serialNumber to ID
    $courseID = $_POST["courseList"];
    $changeID = $courseArray[$courseID][$classTypeArrayCount+3];
    $courseNameChange = $_POST["courseNameChange"];
    $courseAcceptChange = $_POST["courseAcceptChange"];
    $courseTypeChange = $_POST["courseTypeChange"];
    $SQLChangeData = "UPDATE classsheet.$courseName SET courseName ='$courseNameChange',courseAccept = '$courseAcceptChange',courseType = '$courseTypeChange' WHERE $courseName.ID=$changeID";
    mysql_query($SQLChangeData);
    //ReLoad the $courseArray
     $courseArray = queryCourseInfo($courseName,$classTypeArray);
}



mysql_close($DBConnect);






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




?>



<div class="title">
<h2>课程管理</h2>
<div class="underline"></div>
<h6>Course &nbsp;Manage</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<div class="classBlockLeft">
<!-- /////////////////Semester list/////////////////////-->


<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="courseSet.php" method="post">
<input type="hidden" name="semesterListSelected" value="yes" />
<p>[&nbsp;学年度&nbsp;]</p>
<select name="semesterList" size="10">
<?php
for($i=0;$i<$semesterSetCount0;$i++){

?>
<option value="<?php print($i);?>"><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
<?php
}

?>
</select><br />
<input type="submit" value="&nbsp;选定&nbsp;" style="margin-top: 10px;" /> 
</form>
</div>
<?php
}
///////////////////////////////////////////////////////////////////////////////////// - After post
if($_POST["semesterListSelected"] == "yes"){
?>
<form action="courseSet.php" method="post">
    <div style="float: left;">
        <input type="hidden" name="semesterListSelected" value="yes"  />
         <p>[&nbsp;学年度&nbsp;]</p>
         <select name="semesterList" size="10" >
          <?php
          for($i=0;$i<$semesterSetCount0;$i++){

           ?>
       <option value="<?php print($i);?>"<?php if($i == $_POST["semesterList"]){print("selected");}?>><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
          <?php
          }
          ?>
         </select><br />
         <input type="submit" value="&nbsp;选定&nbsp;" style="margin-top: 10px;" /> 
    
    </div>
<!-- ////////////////////Course list///////////////////-->

    <div style="float: left; margin-left: 20px;">
        <p>[&nbsp;课程&nbsp;]</p>
        <select name="courseList" size="10" >
         <?php
         
         for($i=0;$i<$courseArrayCount0;$i++){
            $serialNumber = $i+1;
         ?>
         <option value="<?php print($i);?>" <?php if($i == $_POST["courseList"]){print("selected");}?>> <?php print($serialNumber.'.'.$courseArray[$i][0]); ?> </option>
         <?php
         }
        
         ?>
         </select><br />
         <input type="submit" value="修改" name="courseListChange" style="margin-top: 10px;" /> <input type="submit" value="删除" name="courseListDelete" style="margin-top: 10px;" />
    </div>

<!-- ////////////////////Course information send block //////////////////-->
    <?php if(!$_POST["courseListChange"]){
        ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;课程信息&nbsp;]</p>
        <span>课程名称:<input type="text" name="courseName" maxlength="10" size="5" /></span><br />
        <span>最大接纳班级个数:<input type="text" name="courseAccept" maxlength="2" size="2" /></span><br />
        <span>是否为考试或工艺设计:<input type="checkbox" name="courseType" /></span><br />
        <input type="submit" value="&nbsp;添加&nbsp;" name="courseInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />
    </div>
    <?php
    }
    
    if($_POST["courseListChange"]){
    ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;课程信息&nbsp;]</p>
        <span>课程名称:<input type="text" name="courseNameChange" maxlength="10" size="5" value="<?php print($courseArray[$_POST["courseList"]][0])?>" /></span><br />
        <span>最大接纳班级个数:<input type="text" name="courseAcceptChange" maxlength="3" size="3" value="<?php print($courseArray[$_POST["courseList"]][1])?>" /></span><br />
        <span>是否为考试或工艺设计:<input type="checkbox" name="courseTypeChange" <?php if($courseArray[$_POST["courseList"]][2] == "on"){ print('checked="true"');}?> /></span><br />
        <input type="submit" value="&nbsp;提交&nbsp;" name="courseInfoChange" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />
    </div>
    <?php
    }
    ?>
</form>

<?php
}
?>

</div>




</div>
</div>
</body>
</html>