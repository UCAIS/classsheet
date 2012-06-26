<?php
/**
*	Views output functions page
*	
*	Serial:		120317
*	by:			M.Karminski
*
*/

//TODO: Fill the comment .
//TODO: Change the "target_array" place. [IMPORTANT]

//------  -[ javascript_include_output Function ]-  ------
function javascript_include_output(){
	print <<<SCRITPS
		<!-- editable grid -->
		<script src="scripts/editablegrid-2.0.1.js"></script>   
		<!-- I use jQuery for the Ajax methods -->
		<script src="scripts/jquery-1.7.2.min.js" ></script>
SCRITPS;
	return 0;
}

//------  -[ javascript_window_onload_output Fucntion ]-  ------
function javascript_window_onload_output(){
	print <<<SCRITPS
		<script type="text/javascript">
			window.onload = function() { 
				datagrid = new DatabaseGrid();
			}; 
		</script>
SCRITPS;
	return 0;
}

//------  -[ editable_grid_output Function ]-  ------
function editable_grid_output(){
	print <<<SCRITPS
		<div id="wrap">
		<h1>手动编辑数据</h1> 
			<!-- Feedback message zone -->
			<div id="message"></div>

			<!-- Grid contents -->
			<div id="tablecontent"></div>
		
			<!-- Paginator control -->
			<div id="paginator"></div>
		</div>  
SCRITPS;
	return 0;
}

//------  -[ html_end_output Function ]-  ------
function html_end_output(){
	print "</html>";
	return 0;
}

//------  -[ body_end_output Function ]-  ------
function body_end_output(){
	print "</body>";
	return 0;
}

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

	print '<form action="'.$action.'" enctype="multipart/form-data" method="'.$method.'">';
	return 0;
}

//------  -[ form_end_output Function ]-  ------
function form_end_output(){
	print '</form>';
	return 0;
}

//------  -[ main_title_output Function ]-  ------C
function main_title_output($page_info_array, $page_switch){
	$page_info_array;	//For output the title info
	$page_switch;		//For array number


	print '<div class="title"><h2>';
	print $page_info_array[$page_switch]['PAGE_NAME_IN_CHINESE'];
	print '</h2><div class="underline"></div><h6>';
	print $page_info_array[$page_switch]['PAGE_NAME'];
	print '</h6></div><div style="clear:both;"></div>';
	return 0;
}

//------  -[ semester_list_output Function ]-  ------C
function semester_list_output($page_switch, $semester_list_array, $table_key_names_array, $target_array){
	$page_switch;			//For change list views
	$semester_list_array; 	//For output the list info
	$table_key_names_array;	//For set the $semesterListArray key names
	$target_array;			//For select the target semester

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

	print '<p>[&nbsp;学年信息输入&nbsp;]</p>';
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

//------  -[ semester_info_change_output Function ]-  ------C
function semester_info_change_output($semester_list_array, $table_key_names_array, $target_array){
	$semester_list_array;		//For HTML tag "value" values
	$table_key_names_array;		//For HTML tag "name" values
	$target_array;				//For $semester_list_array object

	$semesterPart = explode("_", $semester_list_array[$target_array]['SEMESTER']);	//Devide the semester information
	print '<p>[&nbsp;学年信息修改&nbsp;]</p>';
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

//------  -[ course_type_list_output Function ]-  ------C
function course_type_list_output($course_type_list_array, $target_array){
	$course_type_list_array;	//For HTML option
	$target_array;				//For HTML tag "selected" values

	$course_type_list_array_count0 = count($course_type_list_array);
	print '<p>[&nbsp;模块列表&nbsp;]</p>';
	print '<select name="courseTypeList" size="10">';
	for($i=0;$i<$course_type_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print "模块".$course_type_list_array[$i]['COURSE_TYPE']."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="courseTypeListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="courseTypeListDelete" style="margin-top: 10px;" />';
	return 0;
}

//------  -[ course_type_info_output Function ]-  -------
function course_type_info_output($table_key_names_array){
	$table_key_names_array;		//For HTML tag "name" values

	print '<p>[&nbsp;模块信息输入&nbsp;]</p>';
	print '<span>模块名称:<input type="text" name="'.$table_key_names_array['COURSE_TYPE'].'" maxlength="2" size="2" /></span><br />';
	print '<span>学时:<input type="text" name="'.$table_key_names_array['COURSE_TOTAL_PERIOD'].'" maxlength="3" size="3" /></span>';
	print '<input type="submit" value="添加" name="courseTypeInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ course_type_info_change_output Function]-  ------C
function course_type_info_change_output($course_type_list_array, $table_key_names_array, $target_array){
	$course_type_list_array;	//
	$table_key_names_array;		//
	$target_array;				//

	print '<p>[&nbsp;模块信息修改&nbsp;]</p>';
	print '<span>模块名称:<input type="text" name="'.$table_key_names_array['COURSE_TYPE'].'" value="'.$course_type_list_array[$target_array]['COURSE_TYPE'].'" maxlength="2" size="2" /></span><br />';
	print '<span>学时:<input type="text" name="'.$table_key_names_array['COURSE_TOTAL_PERIOD'].'" value="'.$course_type_list_array[$target_array]['COURSE_TOTAL_PERIOD'].'" maxlength="3" size="3" /></span>';
	print '<input type="submit" value="修改" name="courseTypeInfoChanged" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ course_list_output Function ]-  ------
function course_list_output($course_list_array, $target_array){
	$course_list_array;			//
	$target_array;				//

	$course_list_array_count0 = count($course_list_array);
	print '<p>[&nbsp;课程列表&nbsp;]</p>';
	print '<select name="courseList" size="10">';
	for($i=0;$i<$course_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		$serialNumber = $i + 1;
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $serialNumber.".".$course_list_array[$i]['COURSE_NAME']."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="courseListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="courseListDelete" style="margin-top: 10px;" />';
	return 0;

}

//------  -[ course_info_output Function ]-  ------
function course_info_output($table_key_names_array){
	$table_key_names_array;			//

	print '<p>[&nbsp;课程信息输入&nbsp;]</p>';
    print '<span>课程名称:<input type="text" name="'.$table_key_names_array['COURSE_NAME'].'" maxlength="20" size="20" /></span><br />';
    print '<span>课程容量:<input type="text" name="'.$table_key_names_array['COURSE_CAPABILITY'].'" maxlength="3" size="3" />[单位:班级]</span><br />';
    print '<span>课程方式:<select name="'.$table_key_names_array['COURSE_STYLE'].'">';
    print '<option value="G">概论课</option>';
    print '<option value="Z">铸造</option>';
    print '<option value="D">锻压</option>';
    print '<option value="H">焊接</option>';
    print '<option value="C">车工</option>';
    print '<option value="X">铣刨磨</option>';
    print '<option value="S">数控</option>';
    print '<option value="T">特种加工</option>';
    print '<option value="Q">钳工</option>';
    print '<option value="GY">工艺设计</option>';
    print '<option value="K">考试</option>';
    print '</select></span><br />';
    print '<input type="submit" value="添加" name="courseInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ course_info_change_output Function ]-  ------C
function course_info_change_output($course_list_array, $table_key_names_array, $target_array){
	$course_list_array;				//
	$table_key_names_array;			//
	$target_array;					//

	print '<p>[&nbsp;课程信息修改&nbsp;]</p>';
	print '<span>课程名称:<input type="text" name="'.$table_key_names_array['COURSE_NAME'].'" value="'.$course_list_array[$target_array]['COURSE_NAME'].'" maxlength="10" size="10" /></span><br />';
	print '<span>课程容量:<input type="text" name="'.$table_key_names_array['COURSE_CAPABILITY'].'" value="'.$course_list_array[$target_array]['COURSE_CAPABILITY'].'" maxlength="3" size="3" />[单位:班级]</span><br />';
	print '<span>课程方式:<select name="'.$table_key_names_array['COURSE_STYLE'].'">';
	if($course_list_array[$target_array]['COURSE_STYLE'] == "G"){
		$option0 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "Z"){
		$option1 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "D"){
		$option2 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "H"){
		$option3 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "C"){
		$option4 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "X"){
		$option5 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "S"){
		$option6 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "T"){
		$option7 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "Q"){
		$option8 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "GY"){
		$option9 = "selected";
	}elseif($course_list_array[$target_array]['COURSE_STYLE'] == "K"){
		$option10 = "selected";
	}
    print '<option value="G" '.$option0.'>概论课</option>';
    print '<option value="Z" '.$option1.'>铸造</option>';
    print '<option value="D" '.$option2.'>锻压</option>';
    print '<option value="H" '.$option3.'>焊接</option>';
    print '<option value="C" '.$option4.'>车工</option>';
    print '<option value="X" '.$option5.'>铣刨磨</option>';
    print '<option value="S" '.$option6.'>数控</option>';
    print '<option value="T" '.$option7.'>特种加工</option>';
    print '<option value="Q" '.$option8.'>钳工</option>';
    print '<option value="GY" '.$option9.'>工艺设计</option>';
    print '<option value="K" '.$option10.'>考试</option>';
    print '</select></span><br />';
	print '<input type="submit" value="修改" name="courseInfoChanged" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;

}

//------  -[ class_list_output Function ]-  ------C
function class_list_output($class_list_array, $target_array, $page_switch = 0){
	$class_list_array;			//For HTML option
	$target_array;				//For HTML tag "selected" values


	$class_list_array_count0 = count($class_list_array);
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
		print $serialNumber.".[".$class_list_array[$i]['CLASS_TYPE']."].".$class_list_array[$i]['CLASS_NAME']."</option>";
	}
	print '</select>';
	if($page_switch != 0){
		print '<input type="submit" value="&nbsp;确定&nbsp;" name="classListQuery" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="classListDelete" style="margin-top: 10px;" />';
	}else{
		print '<input type="submit" value="&nbsp;修改&nbsp;" name="classListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="classListDelete" style="margin-top: 10px;" />';
	}
	return 0;
}

//------  -[ class_info_output Function ]-  ------C
function class_info_output($course_type_list_array, $table_key_names_array){
	$course_type_list_array;	//
	$table_key_names_array;		//For HTML tag "name" values


	$course_type_list_array_count0 = count($course_type_list_array);
    print '<p>[&nbsp;班级信息输入&nbsp;]</p>';
    print '<span>课程类型:<select name="'.$table_key_names_array['CLASS_TYPE'].'">';
    for($i=0;$i<$course_type_list_array_count0;$i++){
    	print '<option value="'.$course_type_list_array[$i]['COURSE_TYPE'].'">';
    	print $course_type_list_array[$i]['COURSE_TYPE']."学时".$course_type_list_array[$i]['COURSE_TOTAL_PERIOD']."</option>";
    }
    print '</select></br>';
    print '<span>班级名称:<input type="text" name="'.$table_key_names_array['CLASS_NAME'].'" maxlength="20" size="20" /></span><br />';
    print '<span>人数:<input type="text" name="'.$table_key_names_array['CLASS_CAPABILITY'].'" maxlength="3" size="3" /></span><br />';
    print '<input type="submit" value="添加" name="classInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
} 

//------  -[ class_info_change_output Function ]-  ------C
function class_info_change_output($class_list_array, $course_type_list_array, $table_key_names_array, $target_array){
	$class_list_array;			//For HTML tag "value" values
	$course_type_list_array;	//
	$table_key_names_array;		//For HTML tag "name" values
	$target_array;				//For location $class_list_array 

	$course_type_list_array_count0 = count($course_type_list_array);
	print '<p>[&nbsp;班级信息修改&nbsp;]</p>';
	print '<span>课程类型:<select name="'.$table_key_names_array['CLASS_TYPE'].'">';
	$targetType = $class_list_array[$target_array]['CLASS_TYPE'];
    for($i=0;$i<$course_type_list_array_count0;$i++){
    	if($targetType == $course_type_list_array[$i]['COURSE_TYPE']){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
    	print '<option value="'.$course_type_list_array[$i]['COURSE_TYPE'].'" '.$selectedValue.'>';
    	print $course_type_list_array[$i]['COURSE_TYPE']."学时".$course_type_list_array[$i]['COURSE_TOTAL_PERIOD']."</option>";
    }
    print '</select></br>';
    print '<span>班级名称:<input type="text" name="'.$table_key_names_array['CLASS_NAME'].'" value="'.$class_list_array[$target_array]['CLASS_NAME'].'"  maxlength="20" size="20" /></span><br />';
    print '<span>人数:<input type="text" name="'.$table_key_names_array['CLASS_CAPABILITY'].'" value="'.$class_list_array[$target_array]['CLASS_CAPABILITY'].'"  maxlength="3" size="3" /></span><br />';
    print '<input type="submit" value="修改" name="classInfoChanged" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ table_info_output Function ]-  ------
function table_info_output($table_key_names_array, $target_list_array){
	$table_key_names_array;		//
	$target_list_array;			//

	$target_list_array_count0 = count($target_list_array);
	print '<p>[表信息]</p>';
	print '<table align="center">';
	print '<tr>';
	foreach($table_key_names_array as $value){
		print '<td class="borderedTd">'.$value.'</td>';
	}
	print '</tr>';
	for($i=0;$i<$target_list_array_count0;$i++){
		print '<tr>';
		foreach($table_key_names_array as $value){
			print '<td class="borderedTd">'.$target_list_array[$i][$value].'</td>';
		}
		print '</td>';	
	}
	print '</table>';
	return 0;
}

//------  -[ files_upload_output Function ]-  ------
function files_upload_output(){
	print '<span>请选择上传文件[.csv][文件编码格式必须为UTF-8]<input type="file" name="uploadFiles" value="" />';
	print '<input type="submit" name="upload" value="上传" /></span>';
	return 0;
}

//------  -[ week_select_output Function ]-  ------
function week_select_output($total_schedule_table_key_names_array, $semester_week_set){
	$total_schedule_table_key_names_array;		//
	$semester_week_set;							//

	print '<span>学期第<input type="text" name="'.$total_schedule_table_key_names_array['SEMESTER_WEEK'].'" value="'.$semester_week_set.'"  maxlength="2" size="2" />周<input type="submit" name="SEMESTER_WEEK_CONFIRM" value="确定" /></span>';
	return 0;
}

//------  -[ reschedule_button_output Function ]-  ------
function reschedule_button_output(){
	print '<span><input type="submit" name="RESCHEDULE" value="重新排列课程并保存" /></span>';
	return 0;
}

//------  -[ classroom_list_output Function ]-  ------
function classroom_list_output($classroom_list_array, $target_array){
	$classroom_list_array;			//
	$target_array;					//

	$classroom_list_array_count0 = count($classroom_list_array);
	print '<p>[&nbsp;教室列表&nbsp;]</p>';
	print '<select name="classroomList" size="10">';
	for($i=0;$i<$classroom_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $classroom_list_array[$i]['CLASSROOM_NAME']."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="classroomListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="classroomListDelete" style="margin-top: 10px;" />';
	return 0;

}

//------  -[ classroom_info_output Function ]-  ------
//[WARING] The classroom_type has been hardcoded in syntax.
function classroom_info_output($table_key_names_array){
	$table_key_names_array;			//

	print '<p>[&nbsp;教室信息输入&nbsp;]</p>';
	print '<span>教室名称:<input type="text" name="'.$table_key_names_array['CLASSROOM_NAME'].'" maxlength="10" size="10" /></span><br />';
	print '<span>教室类型:<select name="'.$table_key_names_array['CLASSROOM_TYPE'].'">';
    print '<option value="J">教室[J]</option>';
    print '<option value="S">编程室[S]</option>';
    print '<option value="H">焊接室[H]</option>';
    print '<option value="D">锻压室[D]</option>';
    print '<option value="Z">铸热室[Z]</option>';
    print '<option value="T">特加室[T]</option>';
    print '</select></span><br />';
    print '<span>教室容纳班级量:<input type="text" name="'.$table_key_names_array['CLASSROOM_CAPABILITY'].'" maxlength="2" size="2" /></span><br />';
	print '<input type="submit" value="添加" name="classroomInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ classroom_info_change_output Function ]-  ------
function classroom_info_change_output($classroom_list_array, $table_key_names_array, $target_array){
	$classroom_list_array;			//
	$table_key_names_array;			//
	$target_array;					//

	print '<p>[&nbsp;教室信息修改&nbsp;]</p>';
	print '<span>教室名称:<input type="text" name="'.$table_key_names_array['CLASSROOM_NAME'].'" value="'.$classroom_list_array[$target_array]['CLASSROOM_NAME'].'" maxlength="10" size="10" /></span><br />';
	print '<span>教室类型:<select name="'.$table_key_names_array['CLASSROOM_TYPE'].'">';
	if($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "J"){
		$option0 = "selected";
	}elseif($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "S"){
		$option1 = "selected";
	}elseif($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "H"){
		$option2 = "selected";
	}elseif($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "D"){
		$option3 = "selected";
	}elseif($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "Z"){
		$option4 = "selected";
	}elseif($classroom_list_array[$target_array]['CLASSROOM_TYPE'] == "T"){
		$option5 = "selected";
	}
    print '<option value="J" '.$option0.'>教室[J]</option>';
    print '<option value="S" '.$option1.'>编程室[S]</option>';
    print '<option value="H" '.$option2.'>焊接室[H]</option>';
    print '<option value="D" '.$option3.'>锻压室[D]</option>';
    print '<option value="Z" '.$option4.'>铸热室[Z]</option>';
    print '<option value="T" '.$option5.'>特加室[T]</option>';
    print '</select></span><br />';
    print '<span>教室容纳班级量:<input type="text" name="'.$table_key_names_array['CLASSROOM_CAPABILITY'].'" value="'.$classroom_list_array[$target_array]['CLASSROOM_CAPABILITY'].'" maxlength="2" size="2" /></span><br />';
	print '<input type="submit" value="修改" name="classroomInfoChanged" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ teacher_list_output Function ]-  ------
function teacher_list_output($teacher_list_array, $target_array){
	$teacher_list_array;			//
	$target_array;					//

	$teacher_list_array_count0 = count($teacher_list_array);
	print '<p>[&nbsp;教师列表&nbsp;]</p>';
	print '<select name="teacherList" size="10">';
	for($i=0;$i<$teacher_list_array_count0;$i++){
		if($target_array == $i){
			$selectedValue = "selected";
		}else{
			$selectedValue = "";
		}
		print '<option value="'.$i.'" '.$selectedValue.'>';//There a BLANK between value and $selectedValue for HTML tag.
		print $teacher_list_array[$i]['TEACHER_NAME']."[".$teacher_list_array[$i]['TEACHER_TYPE_INTRO'].".".$teacher_list_array[$i]['TEACHER_TYPE_DESIGN'].".".$teacher_list_array[$i]['TEACHER_TYPE_EXAM'].".".$teacher_list_array[$i]['TEACHER_TYPE_TRAIN']."]"."</option>";
	}
	print '</select>';
	print '<input type="submit" value="&nbsp;修改&nbsp;" name="teacherListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="teacherListDelete" style="margin-top: 10px;" />';
	return 0;
}


//------  -[ teacher_info_output Function ]-  ------
//[WARING] Hardcode
function teacher_info_output($table_key_names_array){
	$table_key_names_array;			//

	print '<p>[&nbsp;教师信息输入&nbsp;]</p>';
	print '<span>教师名称:<input type="text" name="'.$table_key_names_array['TEACHER_NAME'].'" maxlength="10" size="10" /></span><br />';
	print '<span>教师类型:</span><br />';
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'" id="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'" value="G" /><label for="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'">概论课教师[G]</label><br />';
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'" id="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'" value="GY" /><label for="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'">工艺设计教师[GY]</label><br />';
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'" id="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'" value="K" /><label for="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'">监考教师[K]</label><br />';
	print '<span>教师教学类型:<select name="'.$table_key_names_array['TEACHER_TYPE_TRAIN'].'">';
	print '<option value="" selected>无</option>';
    print '<option value="Z">铸造理论课教师[Z]</option>';
    print '<option value="H">焊接理论课教师[H]</option>';
    print '<option value="C">车工理论课教师[C]</option>';
    print '<option value="Q">钳工理论课教师[Q]</option>';
    print '<option value="S">数控理论课教师[S]</option>';
    print '<option value="T">特加理论课教师[T]</option>';
    print '<option value="X">铣刨磨理论课教师[X]</option>';
    print '<option value="D">锻压理论课教师[D]</option>';
    print '</select></span><br />';
	print '<input type="submit" value="添加" name="teacherInfoAdd" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}

//------  -[ teacher_info_change_output Function ]-  ------
function teacher_info_change_output($teacher_list_array, $table_key_names_array, $target_array){
	$teacher_list_array;			//
	$table_key_names_array;			//
	$target_array;					//

	print '<p>[&nbsp;教师信息修改&nbsp;]</p>';
	print '<span>教师名称:<input type="text" name="'.$table_key_names_array['TEACHER_NAME'].'" value="'.$teacher_list_array[$target_array]['TEACHER_NAME'].'" maxlength="10" size="10" /></span><br />';
	if($teacher_list_array[$target_array]['TEACHER_TYPE_INTRO'] == "G"){
		$optionA = 'checked="checked"';
	}
	if($teacher_list_array[$target_array]['TEACHER_TYPE_DESIGN'] == "GY"){
		$optionB = 'checked="checked"';
	}
	if($teacher_list_array[$target_array]['TEACHER_TYPE_EXAM'] == "K"){
		$optionC = 'checked="checked"';
	}
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'" id="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'" value="G" '.$optionA.' /><label for="'.$table_key_names_array['TEACHER_TYPE_INTRO'].'">概论课教师[G]</label><br />';
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'" id="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'" value="GY" '.$optionB.' /><label for="'.$table_key_names_array['TEACHER_TYPE_DESIGN'].'">工艺设计教师[GY]</label><br />';
	print '<input type="checkbox" name="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'" id="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'" value="K" '.$optionC.' /><label for="'.$table_key_names_array['TEACHER_TYPE_EXAM'].'">监考教师[K]</label><br />';
	print '<span>教师教学类型:<select name="'.$table_key_names_array['TEACHER_TYPE_TRAIN'].'">';
	if($teacher_list_array[$target_array]['TEACHER_TYPE'] == "Z"){
		$option0 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "H"){
		$option1 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "C"){
		$option2 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "Q"){
		$option3 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "S"){
		$option4 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "T"){
		$option5 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "X"){
		$option6 = "selected";
	}elseif($teacher_list_array[$target_array]['TEACHER_TYPE_TRAIN'] == "D"){
		$option7 = "selected";
	}else{
		$optionNull = "selected";
	}
	print '<option value="" '.$optionNull.'>无</option>';
    print '<option value="Z" '.$option0.'>铸造理论课教师[Z]</option>';
    print '<option value="H" '.$option1.'>焊接理论课教师[H]</option>';
    print '<option value="C" '.$option2.'>车工理论课教师[C]</option>';
    print '<option value="Q" '.$option3.'>钳工理论课教师[Q]</option>';
    print '<option value="S" '.$option4.'>数控理论课教师[S]</option>';
    print '<option value="T" '.$option5.'>特加理论课教师[T]</option>';
    print '<option value="X" '.$option6.'>铣刨磨理论课教师[X]</option>';
    print '<option value="D" '.$option7.'>锻压理论课教师[D]</option>';
    print '</select></span><br />';
	print '<input type="submit" value="修改" name="teacherInfoChanged" style="margin-top: 10px;" /> <input type="reset" value="重置" style="margin-top: 10px;" />';
    return 0;
}


//Fin.
?>

