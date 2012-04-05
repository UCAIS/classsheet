<html>
<!--

▆      ▆  ▆▆▆▆▆  ▆▆▆▆▆  ▆▆▆▆▆  ▆▆▆▆▆  T E A M
▆      ▆  ▆          ▆      ▆      ▆      ▆
▆      ▆  ▆          ▆      ▆      ▆      ▆
▆      ▆  ▆          ▆▆▆▆▆      ▆      ▆▆▆▆▆
▆      ▆  ▆          ▆      ▆      ▆              ▆
▆      ▆  ▆          ▆      ▆      ▆              ▆
▆▆▆▆▆  ▆▆▆▆▆  ▆      ▆  ▆▆▆▆▆  ▆▆▆▆▆ .usth.edu.cn

Classsheet Version 0.8

Design by Moloer from UCAIS 

-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Classsheet System for USTH TrainCenter - 黑龙江科技学院实训中心课程调度系统</title>
<script type="text/javascript" src="dragedTableData.js"></script>
<script language="javascript">
function init()
{
//注册可拖拽表格
new DragedTable("tableId");
}
</script>
<style type="text/css" media="screen">
/*Global Settings*/
body { margin: 0; font-size: 12px;}
a { color: #CCC; height: 12px; padding: 7px 4px 7px 4px; text-decoration: none; line-height: 30px; }
a:hover { background-color: #4C4C4C; height: 12px; padding: 7px 4px 7px 4px; line-height: 30px;}
table { border:1px solid #000; margin:0px;}
tr { border:0; padding:0; margin:0;}
th { color: #4f4f4f;}
td { border: 0px solid #000; font-size: 12px; padding:0; text-align: center;}
    .borderedTd { border-left: 1px solid #000; font-size: 12px; width:80px; margin:0; padding:0; text-align:center;}
    .Thead { width: 40px;}
h2 { margin: 0 0 0 20px; font-size: 18px; }
h6 { margin: 0 0 0 10px; font-size: 12px; }

.actived { background: url(images/active.png) repeat-x; color: #FFF;}
.mainTop { width: 100%; height: 30px; background: url(images/titleBannerBG.png) repeat-x;}
  .logo { float: left; height: 30px; width: 111px; background: url(images/classsheet.png) no-repeat;}
  .nav { float: left; height: 30px; width: auto;}
  .navRight { float: right; height: 30px; width: auto;}

.mainMiddle { margin: 0px;}
  .title { float:left; margin: 20px 0 0 0; border-left: 6px #2d2d2d solid; padding-left: 19px;}
    .underline { height: 2px; width: 500px; background: url(images/subTitleBG.png); font-size: 0px;}  
  .form { border-left: 6px #2d2d2d solid; float: left; margin-top:20px; padding-left:19px;}
  .angle { background: url(images/angle.png) no-repeat; text-align:center;}
  
  
.semesterBlockLeft, .userBlockLeft, .classRoomBlockLeft { float: left; height: 240px; width: 200px;}
.semesterBlockRight, .userBlockRight, .classRoomBlockLeft { float: left; height: 240px; width: 400px;}
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

<body oncontextmenu="return false;" onload="init()">
<div class="mainTop">
  <div class="logo"></div>

  <div class="nav">
  <a href="front.php" <?php if($pageSwich == 0){print ('class="actived"');}?>>[&nbsp;首页&nbsp;]</a>
  <a href="semesterSet.php" <?php if($pageSwich == 1){print ('class="actived"');}?>>[&nbsp;学期管理&nbsp;]</a>
  <a href="classSet.php" <?php if($pageSwich == 4){print ('class="actived"');}?>>[&nbsp;班级管理&nbsp;]</a>
  <a href="courseSet.php" <?php if($pageSwich == 2){print ('class="actived"');}?>>[&nbsp;课程管理&nbsp;]</a>
  <a href="courseTimeSet.php" <?php if($pageSwich == 3){print ('class="actived"');}?>>[&nbsp;学时管理&nbsp;]</a>
  <a href="classFormSet.php" <?php if($pageSwich == 5){print ('class="actived"');}?>>[&nbsp;周课程表管理&nbsp;]</a>
  <a href="courseForm.php" <?php if($pageSwich == 6){print ('class="actived"');}?>>[&nbsp;总课程表管理&nbsp;]</a>
  <a href="classRoomSet.php" <?php if($pageSwich == 7){print ('class="actived"');}?>>[&nbsp;教室管理&nbsp;]</a>
  <a href="classRoomCourseForm.php" <?php if($pageSwich == 8){print ('class="actived"');}?>>[&nbsp;教室课程表&nbsp;]</a>
  <a href="studentsCourseForm.php" <?php if($pageSwich == 9){print ('class="actived"');}?>>[&nbsp;学生课程表&nbsp;]</a>
  </div>
  <div class="navRight">
  <a href="setting.php" <?php if($pageSwich == 10){print ('class="actived"');}?>>[&nbsp;设置&nbsp;]</a>
  </div>

  
</div>

<div class="mainMiddle">

