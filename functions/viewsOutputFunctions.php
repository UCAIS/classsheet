<?php
/**
*	Views output functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/


//------  -[ div_head_output_with_class_option Function ]-  ------
function div_head_output_with_class_option($class_name){
	print '<div class="'.$class_name.'">';
	return 0;
}

//------  -[ div_end_output Function ]-  ------
function div_end_output(){
	print '</div>';
	return 0;
}

//------  -[ form_head_output Function ]-  ------
function form_head_output($action, $method){
	print '<form action="'.$action.'" method="'.$method.'">';
	return 0;
}

//------  -[ form_end_output Function ]-  ------
function form_end_output(){
	print '</form>';
	return 0;
}

//------  -[ mainTitle_Output Function ]-  ------
function main_title_output($page_switch, $page_info_array){
	print '<div class="title"><h2>';
	print $page_info_array[$page_switch]['PAGE_NAME_IN_CHINESE'];
	print '</h2><div class="underline"></div><h6>';
	print $page_info_array[$page_switch]['PAGE_NAME'];
	print '</h6></div><div style="clear:both;"></div>';
	return 0;
}

//------  -[ semesterList_Output Function ]-  ------
function semesterList_Output($page_switch, $TARGET_ARRAY, $SEMESTER_LIST_ARRAY, $SEMESTER_LIST_ARRAY_Count0){
	print '<p>[&nbsp;学年度列表&nbsp;]</p>';
	print '<select name="semesterList" size="10">';
	for($i=0;$i<$SEMESTER_LIST_ARRAY_Count0;$i++){
		if($TARGET_ARRAY == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $SEMESTER_LIST_ARRAY[$i][0]."学年度第".$SEMESTER_LIST_ARRAY[$i][1]."学期</option>";
	}
	print '</select>';
	if($page_switch == 1){
		print '<input type="submit" value="&nbsp;修改&nbsp;" name="semesterListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="semesterListDelete" style="margin-top: 10px;" />';
	}else{
		print '<input type="submit" value="&nbsp;选定&nbsp;" name="semesterListSelected" style="margin-top: 10px;" />';
	}
	return 0;
}

//------  -[ semesterInfo_Output Function ]-  ------
function semesterInfo_Output($TABLE_KEY_NAMES_ARRAY){
	print '<p>[&nbsp;学年信息&nbsp;]</p>';
	print '<span><input type="text" name="semesterPartA" maxlength="4" size="4" />-<input type="text" name="semesterPartB" maxlength="4" size="4" />学年度&nbsp;&nbsp;</span>';
	print '<span>第<select name="'.$TABLE_KEY_NAMES_ARRAY[1].'">';
	print '<option value="1" selected >1</option>';
	print '<option value="2" >2</option>';
	print '</select>学期</span><br />';
	print '<span>学期开始日期:<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[3].'" maxlength="4" size="4" />年<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[4].'" maxlength="2" size="2" />月<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[5].'" maxlength="2" size="2" />日</span><br />';
	print '学期周数:<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[2].'" maxlength="2" size="2" />周';
	print '<br /><input type="submit" name="semesterInfoAdd" value="&nbsp;添加&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />';
	return 0;
}

//------  -[ semesterInfo_Change_Output Function ]-  ------
function semesterInfo_Change_Output($TABLE_KEY_NAMES_ARRAY, $SEMESTER_LIST_ARRAY, $TARGET_ARRAY){
	$semesterPart = explode("_", $SEMESTER_LIST_ARRAY[$TARGET_ARRAY][0]);	//Devide the semester information
	print '<p>[&nbsp;学年信息&nbsp;]</p>';
	print '<span><input type="text" name="semesterPartA" maxlength="4" size="4" value="'.$semesterPart[0].'" />-<input type="text" name="semesterPartB" maxlength="4" size="4" value="'.$semesterPart[1].'" />学年度</span>';
	if($SEMESTER_LIST_ARRAY[$TARGET_ARRAY][1] == 1){
		print '<span>第<select name="'.$TABLE_KEY_NAMES_ARRAY[1].'"><option value="1" selected >1</option><option value="2" >2</option></select>学期</span>';
	}elseif($SEMESTER_LIST_ARRAY[$TARGET_ARRAY][1] == 2){
		print '<span>第<select name="'.$TABLE_KEY_NAMES_ARRAY[1].'"><option value="1" >1</option><option value="2" selected >2</option></select>学期</span>';
	}
	print '<br /><span>学期开始日期:<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[3].'" maxlength="4" size="4" value="'.$SEMESTER_LIST_ARRAY[$TARGET_ARRAY][3].'" />年<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[4].'" maxlength="2" size="2" value="'.$SEMESTER_LIST_ARRAY[$TARGET_ARRAY][4].'" />月<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[5].'" maxlength="2" size="2" value="'.$SEMESTER_LIST_ARRAY[$TARGET_ARRAY][5].'" />日</span><br />';
	print '学期周数:<input type="text" name="'.$TABLE_KEY_NAMES_ARRAY[2].'" maxlength="2" size="2" value="'.$SEMESTER_LIST_ARRAY[$TARGET_ARRAY][2].'" />周';
	print '<br /><input type="submit" name="semesterInfoChange" value="&nbsp;提交&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" /></div>';
	return 0; 
}

//------  -[ classList_Output Function ]-  ------
function classList_Output($TARGET_ARRAY, $CLASS_LIST_ARRAY, $CLASS_LIST_ARRAY_Count0){
	print '<p>[&nbsp;班级列表&nbsp;]</p>';
	print '<select name="classList" size="10">';
	for($i=0;$i<$CLASS_LIST_ARRAY_Count0;$i++){
		if($TARGET_ARRAY == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		$serialNumber = $i + 1;
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $serialNumber.".[".$CLASS_LIST_ARRAY[$i][0]."].".$CLASS_LIST_ARRAY[$i][2]."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="classListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="classListDelete" style="margin-top: 10px;" />';
	return 0;
}

//------  -[ classInfo_Output Function ]-  ------
function classInfo_Output(){
    print '<p>[&nbsp;班级信息&nbsp;]</p>';
    print '<span>课程类型:<input type="text" name="classType" maxlength="2" size="2" /></span><br />';
    print '<span>学时:<input type="text" name="classTime" maxlength="3" size="3" /></span><br />';
    print '<span>班级名称:<input type="text" name="className" maxlength="20" size="20" /></span><br />';
    print '<span>人数:<input type="text" name="classPeople" maxlength="3" size="3" /></span><br />';
    print '<input type="submit" value="添加" name="classInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
} 







        
    



//Fin.
?>

