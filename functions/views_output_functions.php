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
	$class_name;	//For HTML tag class option

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
	$action;	//For HTML tag action option
	$method;	//For HTML tag method option

	print '<form action="'.$action.'" method="'.$method.'">';
	return 0;
}

//------  -[ form_end_output Function ]-  ------
function form_end_output(){
	print '</form>';
	return 0;
}

//------  -[ main_title_output Function ]-  ------
function main_title_output($page_switch, $page_info_array){
	$page_switch;		//For array number
	$page_info_array;	//For output the title info

	print '<div class="title"><h2>';
	print $page_info_array[$page_switch]['PAGE_NAME_IN_CHINESE'];
	print '</h2><div class="underline"></div><h6>';
	print $page_info_array[$page_switch]['PAGE_NAME'];
	print '</h6></div><div style="clear:both;"></div>';
	return 0;
}

//------  -[ semester_list_output Function ]-  ------
function semester_list_output($page_switch, $target_array, $semester_list_array, $table_key_names_array){
	$page_switch;			//For change list views
	$target_array;			//For select the target semester
	$semester_list_array; 	//For output the list info
	$table_key_names_array;	//For set the $semesterListArray key names

	$semester_list_array_count0 = count($semester_list_array);
	print '<p>[&nbsp;学年度列表&nbsp;]</p>';
	print '<select name="semesterList" size="10">';
	for($i=0;$i<$semester_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between "value" and "$selectedValue" for HTML tag.
		print $semester_list_array[$i][$table_key_names_array["SEMESTER"]]."学年度第".$semester_list_array[$i][$table_key_names_array["PART"]]."学期</option>";
	}
	print '</select>';
	if($page_switch == 1){
		print '<input type="submit" value="&nbsp;修改&nbsp;" name="semesterListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="semesterListDelete" style="margin-top: 10px;" />';
	}else{
		print '<input type="submit" value="&nbsp;选定&nbsp;" name="semesterListSelected" style="margin-top: 10px;" />';
	}
	return 0;
}

//------  -[ semester_info_output Function ]-  ------
function semester_info_output($table_key_names_array){
	$table_key_names_array;		//For HTML tag "name" values

	print '<p>[&nbsp;学年信息&nbsp;]</p>';
	print '<span><input type="text" name="semesterPartA" maxlength="4" size="4" />-<input type="text" name="semesterPartB" maxlength="4" size="4" />学年度&nbsp;&nbsp;</span>';
	print '<span>第<select name="'.$table_key_names_array['PART'].'">';
	print '<option value="1" selected >1</option>';
	print '<option value="2" >2</option>';
	print '</select>学期</span><br />';
	print '<span>学期开始日期:<input type="text" name="'.$table_key_names_array['START_YEAR'].'" maxlength="4" size="4" />年<input type="text" name="'.$table_key_names_array['START_MONTH'].'" maxlength="2" size="2" />月<input type="text" name="'.$table_key_names_array['START_DAY'].'" maxlength="2" size="2" />日</span><br />';
	print '学期周数:<input type="text" name="'.$table_key_names_array['WEEK_COUNT'].'" maxlength="2" size="2" />周';
	print '<br /><input type="submit" name="semesterInfoAdd" value="&nbsp;添加&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />';
	return 0;
}

//------  -[ semester_info_change_output Function ]-  ------
function semester_info_change_output($table_key_names_array, $semester_list_array, $target_array){
	$table_key_names_array;		//For HTML tag "name" values
	$semester_list_array;		//For HTML tag "value" values
	$target_array;				//For $semester_list_array object

	$semesterPart = explode("_", $semester_list_array[$target_array]['SEMESTER']);	//Devide the semester information
	print '<p>[&nbsp;学年信息&nbsp;]</p>';
	print '<span><input type="text" name="semesterPartA" maxlength="4" size="4" value="'.$semesterPart[0].'" />-<input type="text" name="semesterPartB" maxlength="4" size="4" value="'.$semesterPart[1].'" />学年度</span>';
	if($semester_list_array[$target_array]['PART'] == 1){
		print '<span>第<select name="'.$table_key_names_array['PART'].'"><option value="1" selected >1</option><option value="2" >2</option></select>学期</span>';
	}elseif($semester_list_array[$target_array]['PART'] == 2){
		print '<span>第<select name="'.$table_key_names_array['PART'].'"><option value="1" >1</option><option value="2" selected >2</option></select>学期</span>';
	}
	print '<br /><span>学期开始日期:<input type="text" name="'.$table_key_names_array['START_YEAR'].'" maxlength="4" size="4" value="'.$semester_list_array[$target_array]['START_YEAR'].'" />年<input type="text" name="'.$table_key_names_array['START_MONTH'].'" maxlength="2" size="2" value="'.$semester_list_array[$target_array]['START_MONTH'].'" />月<input type="text" name="'.$table_key_names_array['START_DAY'].'" maxlength="2" size="2" value="'.$semester_list_array[$target_array]['START_DAY'].'" />日</span><br />';
	print '学期周数:<input type="text" name="'.$table_key_names_array['WEEK_COUNT'].'" maxlength="2" size="2" value="'.$semester_list_array[$target_array]['WEEK_COUNT'].'" />周';
	print '<br /><input type="submit" name="semesterInfoChange" value="&nbsp;提交&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" /></div>';
	return 0; 
}

//------  -[ class_list_output Function ]-  ------
function class_list_output($target_array, $class_list_array, $class_list_array_count0){
	print '<p>[&nbsp;班级列表&nbsp;]</p>';
	print '<select name="classList" size="10">';
	for($i=0;$i<$class_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		$serialNumber = $i + 1;
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $serialNumber.".[".$class_list_array[$i][0]."].".$class_list_array[$i][2]."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="classListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="classListDelete" style="margin-top: 10px;" />';
	return 0;
}

//------  -[ classInfo_Output Function ]-  ------
function class_info_output(){
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

