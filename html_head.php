<?php
/**
* HTML head area page
* This page includes CSS settings and NAV settings 
*
* Serial:   120317
* by:     M.Karminski
*
*/

//Include Files
include('settings.php');

//HTML Area Start
?>
<html>

<!-- 
            Classsheet Version 0.9.1
            
            Copyright (C) 2011,2012    M.karminski 
            For more information please see documents.
            M.Karminski 
            linfcstmr@gmail.com  

-->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $SYSTEM_NAME; ?></title>

<!-- CSS Area Start  -->
<style type="text/css" media="screen">

/**   Global settings   */
  body { margin: 0; font-size: 12px;}
  a { color: #CCC; height: 12px; padding: 7px 4px 7px 4px; text-decoration: none; line-height: 30px; }
  a:hover { background-color: #4C4C4C; height: 12px; padding: 7px 4px 7px 4px; line-height: 30px;}
  h2 { margin: 0 0 0 20px; font-size: 18px; }
  h6 { margin: 0 0 0 10px; font-size: 12px; }

/**   Special TABLE tag settings   */
  table { border:1px solid #000; margin:0px;}
  tr { border:0; padding:0; margin:0;}
  th { color: #4f4f4f;}
  td { border: 0px solid #000; font-size: 12px; padding:0; text-align: center;}
    .borderedTd { border-left: 1px solid #000; font-size: 12px; width:80px; margin:0; padding:0; text-align:center;}
    .Thead { width: 40px;}

/**     NAV settings  */
  .actived { background: url(images/active.png) repeat-x; color: #FFF;}
  .mainTop { width: 100%; height: 30px; background: url(images/title_banner_bg.png) repeat-x;}
  .logo { float: left; height: 30px; width: 111px; background: url(images/classsheet.png) no-repeat;}
  .nav { float: left; height: 30px; width: auto;}
  .navRight { float: right; height: 30px; width: auto;}

/**     Main Middle area settings   */
.mainMiddle { margin: 0px;}
  .mainMiddleBlockLeft { float: left; height: 240px; width: 200px;}
  .mainMiddleBlockRight { float: left; height: 240px; width: 400px;}
  /**   Main Title settings   */
  .title { float:left; margin: 20px 0 0 0; border-left: 6px #2d2d2d solid; padding-left: 19px;}
    .underline { height: 2px; width: 500px; background: url(images/sub_title_bg.png); font-size: 0px;}  
  .form { border-left: 6px #2d2d2d solid; float: left; margin-top:20px; padding-left:19px;}
  .angle { background: url(images/angle.png) no-repeat; text-align:center;}
  
  

.classBlockLeft { height: 240px; width: 900px; float: left;}
.classBlockRight { height: 240px; width: 270px; float: left; margin-left: 20px;}

</style>
<!--[if IE]>
<style type="text/css">
  a { color: #CCC; height: 12px; padding: 0px 4px 0px 4px; text-decoration: none; line-height: 30px; }
  a:hover { background-color: #4C4C4C; height: 12px; padding: 0px 4px 0px 4px; line-height: 30px;}
</style>
<![endif]-->
</head>

<body>
<div class="mainTop">
  <div class="logo"></div>
  <div class="nav">
    <a href="index.php" <?php if($PAGE_SWITCH == 0){print ('class="actived"');}?>>[&nbsp;首页&nbsp;]</a>
    <a href="semester_manage.php" <?php if($PAGE_SWITCH == 1){print ('class="actived"');}?>>[&nbsp;学期管理&nbsp;]</a>
    <a href="course_type_manage.php" <?php if($PAGE_SWITCH == 2){print ('class="actived"');}?>>[&nbsp;课程类型管理&nbsp;]</a>
    <a href="course_manage.php" <?php if($PAGE_SWITCH == 3){print ('class="actived"');}?>>[&nbsp;课程管理&nbsp;]</a>
    <a href="class_manage.php" <?php if($PAGE_SWITCH == 4){print ('class="actived"');}?>>[&nbsp;班级管理&nbsp;]</a>
    <a href="classFormSet.php" <?php if($PAGE_SWITCH == 5){print ('class="actived"');}?>>[&nbsp;周课程表管理&nbsp;]</a>
    <a href="courseForm.php" <?php if($PAGE_SWITCH == 6){print ('class="actived"');}?>>[&nbsp;总课程表管理&nbsp;]</a>
    <a href="classRoomSet.php" <?php if($PAGE_SWITCH == 7){print ('class="actived"');}?>>[&nbsp;教室管理&nbsp;]</a>
    <a href="classRoomCourseForm.php" <?php if($PAGE_SWITCH == 8){print ('class="actived"');}?>>[&nbsp;教室课程表&nbsp;]</a>
    <a href="studentsCourseForm.php" <?php if($PAGE_SWITCH == 9){print ('class="actived"');}?>>[&nbsp;学生课程表&nbsp;]</a>
  </div>
  <div class="navRight">
    <a href="setting.php" <?php if($PAGE_SWITCH == 10){print ('class="actived"');}?>>[&nbsp;设置&nbsp;]</a>
  </div>
</div>


