<?php
session_start();
require_once('config.php')
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" 
/>
<title></title>
</head>
<body>
<?php
header("Content-Type:text/html;charset=UTF-8");
//连接数据库
//mysql_connect("localhost","root","827942863");
//mysql_select_db("user_dl");
//mysql_query("set names utf8");
/**该文件的作用是 接收用户输入的信息，进行查询，判断是否存在，如果存
在则跳转到成功页面，如果错误，则给出错误提示。*/
//接受用户参数(用户名、密码)
$username = $_POST['username'];
//Change to MD5
$passwordTemp = $_POST['pass'];
$password = md5($passwordTemp);
//根据用户名进行查询，取出密码
$SQLQueryPassword = "SELECT password FROM user WHERE username='".$username."'";

//执行查询
$rs=mysql_query($SQLQueryPassword);
if($rs!=""){
$selpass=mysql_result($rs,0);
}
if($selpass!=''){ 
   //判断取出的密码是否和用户输入的一致
     if($password == $selpass){
        global $login;
        $login = $username;
        
        $_SESSION['username'] = $login;
	 
     ?>
     <script>
     //如果一致提示登陆成功
	 alert("登陆成功！");
	 //页面要跳转到主页
	 location.href="./front.php";
	 </script>
	 <?php
     }else{
	 //提示密码错误
	 ?>
     <script>
     //如果一致提示登陆成功
	 alert("密码输入错误");
	 //页面要跳转到主页
	 location.href="./index.html";
	 </script>
	 <?php
	 }
}else{
//如果根据用户名查询不到信息，提示用户名不存在
     ?>
     <script>
     //如果一致提示登陆成功
	 alert("您输入的用户名不存在，请核实！");
	 //页面要跳转到主页
	 location.href="./index.html";
	 </script>
	 	 <?php
}
 ?>
?>
</body>
</html>