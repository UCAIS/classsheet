<?php
/**
 *  semester set page;
 *  by:Linfcstmr
 *  110721
 * 
 */
$pageSwich =1;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

//Object array of $semesterSet [Not in use]//////////////////////////Prepear to rewrite or delete//////////////////////
$objectArray = $_POST["semesterList"];

//ADD the semester data
if($_POST["semesterInfoSubmitted"] == "yes"){
    $semesterCombian = "$_POST[semesterPartA]"."_$_POST[semesterPartB]";
    print $semesterCombian."<br />";
    $SQLAddSemester="INSERT INTO semester (semester,part,weekCount,startYear,startMonth,startDay) VALUES ('$semesterCombian','$_POST[part]','$_POST[weekCount]','$_POST[startYear]','$_POST[startMonth]','$_POST[startDay]')";
    print ("\$SQLAddSemester".$SQLAddSemester."<br />");
    mysql_query($SQLAddSemester);
    //ReLoad the $semesterSet
    $semesterSet = querySemesterInfo();
}

//Delete the semester data
if($_POST["semesterListDelete"]){
    //Change serialNumber to ID
    $semesterSetID = $_POST["semesterList"];
    $deleteID = $semesterSet[$semesterSetID][6];
    
    $SQLDeleteItem = "DELETE FROM classsheet.semester WHERE semester.ID=$deleteID";
    mysql_query($SQLDeleteItem);
    //ReLoad the $semesterSet
    $semesterSet = querySemesterInfo();
}

//Change the semester data 

if($_POST["semesterInfoChangeSubmitted"] == "yes"){
    //Change serialNumber to ID
    $semesterSetID = $_POST["semesterList"];
    print "\$semesterSetID = ".$semesterSetID."<br />";
    $changeID = $semesterSet[$semesterSetID][6];

    $semesterChange = $_POST["semesterChangePartA"]."_".$_POST["semesterChangePartB"];
    print $semesterChange."<br />";
    $partChange = $_POST["partChange"];
    $weekCountChange = $_POST["weekCountChange"];
    $startYearChange = $_POST["startYearChange"];
    $startMonthChange = $_POST["startMonthChange"];
    $startDayChange = $_POST["startDayChange"];

    $SQLChangeData = "UPDATE classsheet.semester SET semester ='$semesterChange',part = $partChange,weekCount = $weekCountChange,startYear = $startYearChange,startMonth = $startMonthChange,startDay = $startDayChange WHERE semester.ID=$changeID";
    print "\$SQLChangeData = ".$SQLChangeData."<br />";
    mysql_query($SQLChangeData);
    //ReLoad the $semesterSet
    $semesterSet = querySemesterInfo();
    
}

mysql_close($DBConnect);




//Count the semesterSet array
$semesterSetCount0 = count($semesterSet);
for($i=0;$i<$semesterSetCount0;$i++){
$semesterSetCount1[$i] = count($semesterSet[$i]);
}






?>


<div class="title">
<h2>学期管理</h2>
<div class="underline"></div>
<h6>Semester Manage</h6>
</div>
<div style="clear:both;"></div>

<div class="form">
<?php if(!$_POST["semesterListChange"]){
?>
<div class="semesterBlockLeft">
<form action="semesterSet.php" method="post">

<input type="hidden" name="semesterListSubmitted" value="yes" />


<p>[&nbsp;学年度列表&nbsp;]</p>
<select name="semesterList" size="10">
    <?php
    for($i=0;$i<$semesterSetCount0;$i++){
        ?><option value="<?php print ($i);?>"<?php if($_POST["semesterListSubmitted"]&&$_POST["semesterList"] == $i){print("selected");}?>><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
     <?php  
    }
     ?>        
</select>
<input type="submit" value="&nbsp;修改&nbsp;" name="semesterListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="semesterListDelete" style="margin-top: 10px;" />

</form>
</div>
<?php
}
?>




<?php
if($_POST["semesterListSubmitted"] != "yes" || $_POST["semesterListDelete"]){
 ?>
 <div class="semesterBlockRight">
<form action="semesterSet.php" method="post">
<input type="hidden" name="semesterInfoSubmitted" value="yes" />

<p>[&nbsp;学年信息&nbsp;]</p>
<span><input type="text" name="semesterPartA" maxlength="9" size="4" />-<input type="text" name="semesterPartB" maxlength="9" size="4" />学年度&nbsp;&nbsp;</span>
<span>第<select name="part">
        <option value="1" selected >1</option>
        <option value="2" >2</option>
</select>学期</span><br />
<span>学期开始日期:
<input type="text" name="startYear" maxlength="4" size="4" />年
<input type="text" name="startMonth" maxlength="2" size="2" />月
<input type="text" name="startDay" maxlength="2" size="2" />日</span><br />
学期周数:<input type="text" name="weekCount" maxlength="2" size="2" />周

<br /><input type="submit" value="&nbsp;添加&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />

</form>
</div>
<?php
}
?>

<?php
if($_POST["semesterListChange"]){
$semesterListNumber = $_POST["semesterList"];
 ?>



<form action="semesterSet.php" method="post">
<input type="hidden" name="semesterInfoChangeSubmitted" value="yes" />
<div class="semesterBlockLeft">
<p>[&nbsp;学年度列表&nbsp;]</p>
<select name="semesterList" size="10">
    <?php
    for($i=0;$i<$semesterSetCount0;$i++){
        ?><option value="<?php print ($i);?>"<?php if($_POST["semesterListSubmitted"]&&$_POST["semesterList"] == $i){print("selected");}?>><?php print($semesterSet[$i][0]);?>学年度第<?php print($semesterSet[$i][1]);?>学期</option>
     <?php  
    }
     ?>        
</select>
<input type="submit" value="&nbsp;修改&nbsp;" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="semesterListDelete" style="margin-top: 10px;" />
</div>

<div class="semesterBlockRight">
<p>[&nbsp;学年信息&nbsp;]</p>
<?php
$semesterPart = explode("_", $semesterSet[$semesterListNumber][0]);

?>
<span><input type="text" name="semesterChangePartA" maxlength="9" size="4" value="<?php print($semesterPart[0]);?>" />-<input type="text" name="semesterChangePartB" maxlength="9" size="4" value="<?php print($semesterPart[1]);?>" />学年度</span>
<?php 
if($semesterSet[$semesterListNumber][1] == 1){
?>
<span>第<select name="partChange">
        <option value="1" selected >1</option>
        <option value="2" >2</option>
</select>学期</span>
<?php
}elseif($semesterSet[$semesterListNumber][1] == 2){
 ?>
 <span>第<select name="partChange">
        <option value="1">1</option>
        <option value="2" selected >2</option>
</select>学期</span>
 <?php
 }
 ?>
<br />
<span>学期开始日期:
<input type="text" name="startYearChange" maxlength="4" size="4" value="<?php print($semesterSet[$semesterListNumber][3]);?>" />年
<input type="text" name="startMonthChange" maxlength="2" size="2" value="<?php print($semesterSet[$semesterListNumber][4]);?>" />月
<input type="text" name="startDayChange" maxlength="2" size="2" value="<?php print($semesterSet[$semesterListNumber][5]);?>" />日</span><br />
学期周数:<input type="text" name="weekCountChange" maxlength="2" size="2" value="<?php print($semesterSet[$semesterListNumber][2]);?>" />周

<br /><input type="submit" value="&nbsp;提交&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />
</div>
</form>
<?php
}
?>


</div>
</div>
</body>
</html>