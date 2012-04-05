<?php
/**
 *  classRoom set page;
 *  by:Linfcstmr
 *  111208
 * 
 */
$pageSwich = 7;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $semesterSet
$semesterSet = querySemesterInfo();

//Count the $semesterSet array
$semesterSetCount0 = count($semesterSet);
for($i=0;$i<$semesterSetCount0;$i++){
    $semesterSetCount1[$i] = count($semesterSet[$i]);
}


//Load the semesterName which semester selected
$semesterSelected = $_POST["semesterList"];
$semesterName = "class".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];

//Load the $classTypeArray
$classTypeArray = queryClassType($semesterName);

//Load the $courseArray
if($_POST["semesterListSelected"] == "yes"){
    $courseName = "course".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    //Load the $courseArray
    $courseArray = queryCourseInfo($courseName,$classTypeArray);
}

//Load the $classRoomArray
if($_POST["semesterListSelected"] == "yes"){
    //Create table of classRoom if NOT exists
    $semesterSelected = $_POST["semesterList"];
    $tableName = "classRoom".$semesterSet[$semesterSelected][0]."_".$semesterSet[$semesterSelected][1];
    $SQLCreateTable = "CREATE TABLE IF NOT EXISTS $tableName (ID int NOT NULL AUTO_INCREMENT,PRIMARY KEY(ID),classRoomName varchar(15),classRoomAccept int,classRoomType int,classRoomPriorityProcessing int)";
    mysql_query($SQLCreateTable,$DBConnect);
    //Load the $classRoomArray
    $classRoomArray = queryClassRoomArray($tableName);
    
}

//Add the class Room data
if($_POST["classRoomInfoAdd"]){
    $SQLAddSClassRoom="INSERT INTO $tableName (classRoomName,classRoomAccept,classRoomType,classRoomPriorityProcessing) VALUES ('$_POST[classRoomName]','$_POST[classRoomAccept]','$_POST[classRoomType]','$_POST[classRoomPriorityProcessing]')";
    mysql_query($SQLAddSClassRoom);
    //ReLoad the $classRoomArray
    $classRoomArray = queryClassRoomArray($tableName);    
}

//Delete the class Room data

if($_POST["classRoomListDelete"]){
    //Change serialNumber to ID
    $classRoomID = $_POST["classRoomList"];
    $deleteID = $classRoomArray[$classRoomID][4];
    $SQLDeleteItem = "DELETE FROM classsheet.$tableName WHERE ID=$deleteID";
    mysql_query($SQLDeleteItem,$DBConnect);
    //ReLoad the $classRoomArray
    $classRoomArray = queryClassRoomArray($tableName);    
}

//Rewrite the class Room data

if($_POST["classRoomInfoChange"]){
    //Change serialNumber to ID
   
    $classRoomID = $_POST["classRoomList"];
    $changeID = $classRoomArray[$classRoomID][4];
    $classRoomNameChange = $_POST["classRoomNameChange"];
    $classRoomAcceptChange = $_POST["classRoomAcceptChange"];
    $classRoomTypeChange = $_POST["classRoomTypeChange"];
    $classRoomPriorityProcessingChange = $_POST["classRoomPriorityProcessingChange"];
    
    $SQLChangeData = "UPDATE classsheet.$tableName SET classRoomName ='$classRoomNameChange',classRoomAccept = '$classRoomAcceptChange',classRoomType = '$classRoomTypeChange',classRoomPriorityProcessing = $classRoomPriorityProcessingChange WHERE ID=$changeID";
    mysql_query($SQLChangeData,$DBConnect);
    //ReLoad the $classRoomArray
    $classRoomArray = queryClassRoomArray($tableName);
    
}

// count the $courseArray array
$courseArrayCount0 = count($courseArray);

//$classRoomArray count
$classRoomArrayCount0 = count($classRoomArray);



?>

<div class="title">
<h2>教室管理</h2>
<div class="underline"></div>
<h6>Class &nbsp; Room &nbsp; Manage</h6>
</div>
<div style="clear:both;"></div>
<div class="form">
<?php
////////////////////////////////////////////////////////////////////////////////// - Before post 
if($_POST["semesterListSelected"] != "yes"){
?>
<div style="float: left;">
<form action="classRoomSet.php" method="post">
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
<form action="classRoomSet.php" method="post">
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


<!-- ////////////////////Class Room list///////////////////-->

    <div style="float: left; margin-left: 20px;">
        <p>[&nbsp;教室&nbsp;]</p>
        <select name="classRoomList" size="10">
         <?php
         
         for($i=0;$i<$classRoomArrayCount0;$i++){
            $serialNumber = $i+1;
         ?>
         <option value="<?php print($i);?>" <?php if($i == $_POST["classRoomList"]){print("selected");}?>> <?php print($serialNumber.".".$classRoomArray[$i][0]); ?> </option>
         <?php
         }
        
         ?>
         </select><br />
         <input type="submit" value="修改" name="classRoomListChange" style="margin-top: 10px;" /> <input type="submit" value="删除" name="classRoomListDelete" style="margin-top: 10px;" />
    </div>
<!-- ////////////////////Class Room information send block //////////////////-->
    <?php if(!$_POST["classRoomListChange"]){
        ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;教室信息&nbsp;]</p>
        <span>教室名称:<input type="text" name="classRoomName" maxlength="10" size="10" /></span><br />
        <span>教室最大容纳量:<input type="text" name="classRoomAccept" maxlength="3" size="3" /></span><br />
        <span>教室类型:
        <select name="classRoomType">
        <option value="1">车间</option>
        <option value="0">多媒体教室</option>
        </select></span><br />
        <span>优先上课类型:<select name="classRoomPriorityProcessing">
        <?php 
        for($i=0;$i<$courseArrayCount0;$i++){
            if($courseArray[$i][2]==""){
            print('<option value="'.$i.'">'.$courseArray[$i][0].'</option>');
            }
        }
        ?>
        </select></span><br />
        <input type="submit" value="添加" name="classRoomInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />
    </div>
    <?php
    }
    
    if($_POST["classRoomListChange"]){
    ?>
    <div style=" float: left; margin-left: 20px;">
        
        <p>[&nbsp;教室信息&nbsp;]</p>
        <span>教室名称:<input type="text" name="classRoomNameChange" maxlength="10" size="10" value="<?php print($classRoomArray[$_POST["classRoomList"]][0])?>" /></span><br />
        <span>教室最大容纳量:<input type="text" name="classRoomAcceptChange" maxlength="3" size="3" value="<?php print($classRoomArray[$_POST["classRoomList"]][1])?>" /></span><br />
        <span>教室类型:
         <select name="classRoomTypeChange">
        <option value="1" <?php if($classRoomArray[$_POST["classRoomList"]][2] == 1){print "selected";} ?>>车间</option>
        <option value="0" <?php if($classRoomArray[$_POST["classRoomList"]][2] == 0){print "selected";} ?>>多媒体教室</option>
        </select>
        </span><br />
        <span>优先上课类型:<select name="classRoomPriorityProcessingChange">
        <?php 
        for($i=0;$i<$courseArrayCount0;$i++){
            if($courseArray[$i][2]==""){
                $selected = "";
                if($classRoomArray[$_POST["classRoomList"]][3] == $i){$selected = "selected";}
            print('<option value="'.$i.'" '.$selected.'>'.$courseArray[$i][0].'</option>');
            }
        }
        ?>
        </select></span><br />
        <input type="submit" value="提交" name="classRoomInfoChange" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />
    </div>
    <?php
    }
    ?>

<div style="float:left; margin: 0 0 0 10px;">
<table >
<!-- table head output block start -->
 <tr>
  <td>教室名称</td>
  <td>教室最大容纳量</td>
  <td>教室类型</td>
  <td>优先上课类型</td>
</tr>

<!-- information output block start -->
<?php
for($i=0;$i<$classRoomArrayCount0;$i++){
    print("<tr>");
    for($j=0;$j<4;$j++){
        if($j<2){
           print("<td>".$classRoomArray[$i][$j]."</td>"); 
        }elseif($j==2){
            if($classRoomArray[$i][$j] == 0){
                print("<td>多媒体教室</td>"); 
            }else{
                print("<td>车间</td>"); 
            }
        }elseif($j==3){
           $printID = $classRoomArray[$i][$j];
           print("<td>".$courseArray[$printID][0]."</td>");
        }
        
    }
    print("</tr>");
}
?>
</table>
</div>
</form>
<?php
}
?>
</div>
</div>
</div>
</body>
</html>