<?php

/**
 *  Course time set page;
 *  by:Linfcstmr
 *  110730
 * 
 */
$pageSwich =3;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

//Load the semesterName which semester selected
$semesterSelected = $_POST["semesterList"];
$semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
//$semesterWeekCount = $semesterSet[$semesterSelected][2];

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

//Rewrite the course data

if($_POST["courseArrayChange"]){
    $courseArrayCount0 = count($courseArray);
    $classTypeArrayCount = count($classTypeArray);
    
    $semesterSelected = $_POST["semesterList"];
    $courseName = "course".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    
    for($i=0;$i<$courseArrayCount0;$i++){ 
      $changeID = $courseArray[$i][$classTypeArrayCount+3];//Waring the ID status if BUG exists!
      $SQLChangeData = "UPDATE classsheet.$courseName SET ";
      
      for($j=3;$j<($classTypeArrayCount+3);$j++){       
        $postTemp = "i".$i."j".$j;
        if($j<$classTypeArrayCount+2){
            $SQLChangeData = $SQLChangeData . ($classTypeArray[$j-3]."=".$_POST["$postTemp"].",");
        }else{
            $SQLChangeData = $SQLChangeData . ($classTypeArray[$j-3]."=".$_POST["$postTemp"]);
        }
      }
      $SQLChangeData = $SQLChangeData . " WHERE $courseName.ID=$changeID";
      print $SQLChangeData."<br />";
      mysql_query($SQLChangeData,$DBConnect);
    }  
    //Reload the $courseArray
    $courseArray = queryCourseInfo($courseName,$classTypeArray);
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




mysql_close($DBConnect);
?>





<div class="title">
<h2>课程学时管理</h2>
<div class="underline"></div>
<h6>Course &nbsp; Time &nbsp; Manage</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="courseTimeSet.php" method="post">
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
<form action="courseTimeSet.php" method="post">
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





<div style="float:left; margin: 0 0 0 10px;">
<p>[&nbsp;<?php print($semesterSet[$_POST["semesterList"]][0]); ?>学年第<?php print($semesterSet[$_POST["semesterList"]][1]); ?>学期机械工程训练模块及其学时分配表&nbsp;]</p>

<table>

<!-- table head output block start -->
 <tr>
  <td align="center">序号</td>
  <td align="center">课程名称</td>
  <td align="center">最大接纳班级数</td>
  <td align="center">考试或工艺设计</td>
<?php
for($i=0;$i<$classTypeArrayCount;$i++){
    print ('<td align="center">'.$classTypeArray[$i]."</td>");
    
}
?>    
</tr>

<!-- class output block start -->
<?php

for($i=0;$i<$courseArrayCount0;$i++){
  print('<tr><td align="center">'.($i+1)."</td>");
	for($j=0;$j<($classTypeArrayCount+3);$j++){
   print('<td align="center">');
	if($j<2){
		print($courseArray[$i][$j]);
	}elseif($j==2){
	   if($courseArray[$i][$j]=="on"){print "是";}else{print "否";}
	}else{
		?>
        <input type="text" name="<?php print("i".$i."j".$j);?>" maxlength="3" size="2" value="<?php if($courseArray[$i][$j] != ""){print($courseArray[$i][$j]);}else{print (0);}?>" />
		<?php
	}
print("</td>");
	}
print("</tr>");
}


?>
</table>
<input type="submit" value="&nbsp;保存&nbsp;" name="courseArrayChange" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />
</form>
<?php
}
?>
</div>
</div>
</div>
</body>
</html>