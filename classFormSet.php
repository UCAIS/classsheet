<?php
/**
 *  class form page;
 *  by:Linfcstmr
 *  110721
 * 
 */
 $pageSwich =5;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

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

//Rewrite the class data


if($_POST["classFormChange"]){
    $classArrayCount0 = count($classArray);
    $weekCount = $semesterSet[$_POST["semesterList"]][2];
    
    for($i=0;$i<$classArrayCount0;$i++){ 
      $changeID = $classArray[$i][$weekCount+4];//Waring the ID status if BUG exists!
      $SQLChangeData = "UPDATE classsheet.$semesterName SET ";
      for($j=4;$j<($weekCount+4);$j++){       
        $postTemp = "i".$i."j".$j;
        if($j<$weekCount+3){
            $SQLChangeData = $SQLChangeData . ("week".($j-4)."=".$_POST["$postTemp"].",");
        }else{
            $SQLChangeData = $SQLChangeData . ("week".($j-4)."=".$_POST["$postTemp"]);
        }
      }
      $SQLChangeData = $SQLChangeData . " WHERE $semesterName.ID=$changeID";
      mysql_query($SQLChangeData,$DBConnect);
    }  
    //ReLoad the $classArray
    $classArray = queryClassInfo($semesterName,$semesterWeekCount);
}

// /////////////////////[BUG] [if those code block move to top cause data load fail]////////////////////
//Semester week set
$weekCount = $semesterSet[$_POST["semesterList"]][2];
$years = $semesterSet[$_POST["semesterList"]][3]; 
$months = $semesterSet[$_POST["semesterList"]][4];
$days = $semesterSet[$_POST["semesterList"]][5];
$semesterStartDay = date("m.d",mktime(0,0,0,$months,$days,$years));



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



mysql_close($DBConnect);
?>




<div class="title">
<h2>周课表管理</h2>
<div class="underline"></div>
<h6>Course &nbsp; List &nbsp;Manage</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="classFormSet.php" method="post">
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
<form action="classFormSet.php" method="post">
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





<div style="float:left; margin: 0 0 0 0px;"><p>[&nbsp;<?php print($semesterSet[$_POST["semesterList"]][0]); ?>学年第<?php print($semesterSet[$_POST["semesterList"]][1]); ?>学期机械工程训练总课程表&nbsp;]</p>
<?php
// People count code block
/*
    //people in total
    for($i=0;$i<$classArrayCount0;$i++){
    	$peopleInTotal =$peopleInTotal + $classArray[$i][3];   
    }
    
    //class type
    for($i=0;$i<$classArrayCount0;$i++){
        $classType[] = $classArray[$i][1];
    }
    $classTypeLength = $classArrayCount0;
    
    //class numbers in every class type
    for($i=0;$i<$classArrayCount0;$i++){
        $classNumbersInEveryTypeClass[$i] = $classNumbersInEveryTypeClass[$i] + $classArrayCount1[$i];
        
    }
    
    //people numbers in every class type
    for($i=0;$i<$classArrayCount0;$i++){
        for($j=0;$j<$classArrayCount1[$i];$j++){
            $peopleNumbersInEveryTypeClass[$i] = $peopleNumbersInEveryTypeClass[$i] + $classArray[$i][$j][4];
        }
    }
    
    //output
    print("<div><span>总班级数:".$lengthOfClassArrayCount2."人数: 总计".$peopleInTotal."人,其中:");
    for($i=0;$i<$classTypeLength;$i++){
        print($classType[$i].":".$classNumbersInEveryTypeClass[$i]."个班,".$peopleNumbersInEveryTypeClass[$i]."人; ");
    }
    print("</span></div>");
*/
?>




<table>

<!-- table head output block start -->
 <tr>
  <td rowspan=2>序号</td>
  <td rowspan=2>课程<br />类型</td>
  <td rowspan=2>学时</td>
  <td rowspan=2>班级</td>
  <td rowspan=2>人数</td>
  <td>第1周</td>  
<?php
for($i=2;$i<=$weekCount;$i++){
    print ("<td align=center>".$i."</td>");
    
}
?>    
</tr>
<tr>
<?php
// round the semester date of title
print("<td>".$semesterStartDay."</td>");
for($i=0;$i<$weekCount-1;$i++){ 
    $semesterWeekArrays[$i] = date("m.d",mktime(0,0,0,$months,$days+7,$years));
    $months= date("m",mktime(0,0,0,$months,$days+7,$years));
    $days = date("d",mktime(0,0,0,$months-1,$days+7,$years));
    print("<td>".$semesterWeekArrays[$i]."</td>");
}
?> 
</tr>


<!-- class output block start -->
<?php

for($i=0;$i<$classArrayCount0;$i++){
  print("<tr><td>".($i+1)."</td>");
	for($j=0;$j<($weekCount+4);$j++){
   print("<td>");
	if($j<4){
		print($classArray[$i][$j]);
	}else{
		?>
		<span id=""><select name="<?php print("i".$i."j".$j);?>">
                <option value="0" <?php if($classArray[$i][$j] == "0"){print("selected");}?>></option>
                <option value="2" <?php if($classArray[$i][$j] == "2"){print("selected");}?>>/</option>
                <option value="1" <?php if($classArray[$i][$j] == "1"){print("selected");}?>>全天</option></select>
                </span>
		<?php
	}
print("</td>");
	}
print("</tr>");
}


?>
</table>
<input type="submit" value="保存" name="classFormChange" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />
</form>
<?php
}
?>
</div>
</div>
</div>
</body>
</html>


