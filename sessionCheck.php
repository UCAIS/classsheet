<?php
//Session check
session_start();
if($_SESSION['username']){
    $loginCheck = 1;
    print "User Login.";
}else{
   ?>
    <script>
	 alert("请登录");
	 location.href="./index.html";
	 </script>
   <?php
}

?>