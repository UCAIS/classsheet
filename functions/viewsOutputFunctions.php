<?php
/**
*	Views output functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ divHead_Output_WithClassOption Function ]-  ------
function divHead_Output_WithClassOption($className){
	print '<div class="'.$className.'">';
}

//------  -[ divEnd_Output Function ]-  ------
function divEnd_Output(){
	print '</div>';
}

//------  -[ mainTitle_Output Function ]-  ------
function mainTitle_Output($PAGE_SWITCH, $PAGE_TITLE_INFO){
	print '<div class="title"><h2>';
	print $PAGE_TITLE_INFO[$PAGE_SWITCH][1];
	print '</h2><div class="underline"></div><h6>';
	print $PAGE_TITLE_INFO[$PAGE_SWITCH][0];
	print '</h6></div><div style="clear:both;"></div>';
}

//------  -[ semesterList_Output Function ]-  ------
//Tips:
//The $Inner_POST must be $_POST["semesterList"].
function semesterList_Output($PAGE_SWITCH, $PAGE_TITLE_INFO, $semesterListArrayCount0, $Inner_POST, $semesterListArray){
	print '<form action="'.$PAGE_TITLE_INFO[$PAGE_SWITCH][0].'.php" method="post">';
	print '<p>[&nbsp;学年度列表&nbsp;]</p>';
	print '<select name="semesterList" size="10">';
	for($i=0;$i<$semesterListArrayCount0;$i++){
		if($Inner_POST == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $semesterListArray[$i][0]."学年度第".$semesterListArray[$i][1]."学期</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="semesterListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="semesterListDelete" style="margin-top: 10px;" />';
	print '</form>';
}

//------  -[ semesterInfo_Output Function ]-  ------
function semesterInfo_Output(){
	print '<form action="semesterManage.php" method="post">';
	print '<p>[&nbsp;学年信息&nbsp;]</p>';
	print '<span><input type="text" name="semesterPartA" maxlength="9" size="4" />-<input type="text" name="semesterPartB" maxlength="9" size="4" />学年度&nbsp;&nbsp;</span>';
	print '<span>第<select name="part">';
	print '<option value="1" selected >1</option>';
	print '<option value="2" >2</option>';
	print '</select>学期</span><br />';
	print '<span>学期开始日期:<input type="text" name="startYear" maxlength="4" size="4" />年<input type="text" name="startMonth" maxlength="2" size="2" />月<input type="text" name="startDay" maxlength="2" size="2" />日</span><br />';
	print '学期周数:<input type="text" name="weekCount" maxlength="2" size="2" />周';
	print '<br /><input type="submit" name="semesterInfoAdd" value="&nbsp;添加&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />';
	print '</form>';
}






        
    



//Fin.
?>

