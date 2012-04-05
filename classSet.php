<?php

/**
 * Class information set
 * @author Linfcstmr
 * @copyright 2011
 * 
 * Serial:110729
 * 
 */
  $pageSwich =4;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
$semesterSet = querySemesterInfo();

$semesterSelected = $_POST["semesterList"];
$semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
$weekCount = $semesterSet[$semesterSelected][2];


//Load the $classArray
if($_POST["semesterListSelected"] == "yes"){
    //Create table of classSet about semester if NOT exists
    $semesterSelected = $_POST["semesterList"];
    $semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    $semesterWeekCount = $semesterSet[$semesterSelected][2];
    $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $semesterName (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),classType varchar(15),classTime int,className varchar(15),classPeople int";
    //Cal the weekCount add to database
    for($i=0;$i<$semesterWeekCount;$i++){
        $SQLCreateTable = $SQLCreateTable.",week$i int";
    }
    $SQLCreateTable = $SQLCreateTable.")";
    mysql_query($SQLCreateTable,$DBConnect);
    
    //Load the $classArray
    $classArray = queryClassInfo($semesterName,$semesterWeekCount);

}

//Add the class data

if($_POST["classInfoAdd"]){
  
    $SQLAddSemester="INSERT INTO $semesterName (classType,classTime,className,classPeople) VALUES ('$_POST[classType]','$_POST[classTime]','$_POST[className]','$_POST[classPeople]')";
    mysql_query($SQLAddSemester);
    //ReLoad the $classArray
    $classArray = queryClassInfo($semesterName,$semesterWeekCount);
    
    
}

//Delete the class data

if($_POST["classListDelete"]){
    //Change serialNumber to ID
    $classID = $_POST["classList"];
    $deleteID = $classArray[$classID][$weekCount+4];
    
    $SQLDeleteItem = "DELETE FROM classsheet.$semesterName WHERE $semesterName.ID=$deleteID";
    mysql_query($SQLDeleteItem,$DBConnect);
    //ReLoad the $classArray
    $classArray = queryClassInfo($semesterName,$semesterWeekCount);
}

//Rewrite the class data

if($_POST["classInfoChange"]){
    //Change serialNumber to ID
   
    $classID = $_POST["classList"];
    $changeID = $classArray[$classID][$weekCount+4];
    $classTypeChange = $_POST["classTypeChange"];
    $classTimeChange = $_POST["classTimeChange"];
    $classNameChange = $_POST["classNameChange"];
    $classPeopleChange = $_POST["classPeopleChange"];
  

    $SQLChangeData = "UPDATE classsheet.$semesterName SET classType ='$classTypeChange',classTime = '$classTimeChange',className = '$classNameChange',classPeople = $classPeopleChange WHERE $semesterName.ID=$changeID";
    mysql_query($SQLChangeData,$DBConnect);
    //ReLoad the $classArray
    $classArray = queryClassInfo($semesterName,$semesterWeekCount);
    
}



mysql_close($DBConnect);






//Count the $semesterSet array
$semesterSetCount0 = count($semesterSet);
for($i=0;$i<$semesterSetCount0;$i++){
    $semesterSetCount1[$i] = count($semesterSet[$i]);
}

// count the $classArray array
$classArrayCount0 = count($classArray);
for($i=0;$i<$classArrayCount0;$i++){
    $classArrayCount1[] = count($classArray[$i]);
    
}

// count the countArray length
$lengthOfClassArrayCount1=count($classArrayCount1);



?>



<div class="title">
<h2>班级管理</h2>
<div class="underline"></div>
<h6>Class &nbsp;Manage</h6>
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
<form action="classSet.php" method="post">
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
<input type="submit" value="选定" style="margin-top: 10px;" /> 
</form>
</div>
<?php
}
///////////////////////////////////////////////////////////////////////////////////// - After post
if($_POST["semesterListSelected"] == "yes"){
?>
<form action="classSet.php" method="post">
    <div style="float: left;">
        <input type="hidden" name="semesterListSelected" value="yes" />
         <p>[&nbsp;学年度&nbsp;]</p>
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
<!-- ////////////////////Class list///////////////////-->

    <div style="float: left; margin-left: 20px;">
        <p>[&nbsp;班级&nbsp;]</p>
        <select name="classList" size="10">
         <?php
         
         for($i=0;$i<$classArrayCount0;$i++){
            $serialNumber = $i+1;
         ?>
         <option value="<?php print($i);?>" <?php if($i == $_POST["classList"]){print("selected");}?>> <?php print($serialNumber.'.['.$classArray[$i][0].'].'.$classArray[$i][2]); ?> </option>
         <?php
         }
        
         ?>
         </select><br />
         <input type="submit" value="修改" name="classListChange" style="margin-top: 10px;" /> <input type="submit" value="删除" name="classListDelete" style="margin-top: 10px;" />
    </div>

<!-- ////////////////////Class information send block //////////////////-->
    <?php if(!$_POST["classListChange"]){
        ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;班级信息&nbsp;]</p>
        <span>课程类型:<input type="text" name="classType" maxlength="2" size="2" /></span><br />
        <span>学时:<input type="text" name="classTime" maxlength="3" size="3" /></span><br />
        <span>班级名称:<input type="text" name="className" maxlength="20" size="20" /></span><br />
        <span>人数:<input type="text" name="classPeople" maxlength="3" size="3" /></span><br />
        <input type="submit" value="添加" name="classInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />
    </div>
    <?php
    }
    
    if($_POST["classListChange"]){
    ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;班级信息&nbsp;]</p>
        <span>课程类型:<input type="text" name="classTypeChange" maxlength="2" size="2" value="<?php print($classArray[$_POST["classList"]][0])?>" /></span><br />
        <span>学时:<input type="text" name="classTimeChange" maxlength="3" size="3" value="<?php print($classArray[$_POST["classList"]][1])?>" /></span><br />
        <span>班级名称:<input type="text" name="classNameChange" maxlength="20" size="20" value="<?php print($classArray[$_POST["classList"]][2])?>" /></span><br />
        <span>人数:<input type="text" name="classPeopleChange" maxlength="3" size="3" value="<?php print($classArray[$_POST["classList"]][3])?>" /></span><br />
        <input type="submit" value="提交" name="classInfoChange" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />
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