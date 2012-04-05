<?php

/**
 * Setting Page
 * @author Linfcstmr
 * @copyright 2011
 */

$pageSwich = 10;
include('headArea.php');
require_once('config.php');
include('global.php');
include ('sessionCheck.php');
//Load the $userArray
$userArray = queryUserArray();
//User array count
$userArrayCount0 = count($userArray);

//ADD the user data
if($_POST["userInfoSubmitted"] == "yes" && $_POST['password'] == $_POST['repassword']){
    $password = md5($_POST['password']);
    $SQLAddUser="INSERT INTO user (username,password) VALUES ('$_POST[username]','$password')";
    mysql_query($SQLAddUser);
    //ReLoad the $semesterSet
    $userArray = queryUserArray();
    $userArrayCount0 = count($userArray);
    ?>
    <script>
	 alert("成功增加用户！");
	 </script>
    <?php
}elseif($_POST["userInfoSubmitted"] == "yes"){
    ?>
    <script>
	 alert("两次输入密码不一致！");
	 </script>
    <?php
}

//Delete the user data
if($_POST["userListDelete"]){
    //Change serialNumber to ID
    $userArrayID = $_POST["userList"];
    $deleteID = $userArray[$userArrayID][1];
    
    $SQLDeleteItem = "DELETE FROM classsheet.user WHERE user.ID=$deleteID";
    mysql_query($SQLDeleteItem);
    //ReLoad the $semesterSet
    $userArray = queryUserArray();
    $userArrayCount0 = count($userArray);
    ?>
    <script>
	 alert("成功删除用户！");
	 </script>
    <?php
}

//Change the user data 

if($_POST["userInfoChangeSubmitted"] == "yes" && $_POST['password'] == $_POST['repassword']){
    //Change serialNumber to ID
    $userArrayID = $_POST["userList"];
    print "\$userArrayID = ".$userArrayID."<br />";
    $changeID = $userArray[$userArrayID][1];

    $usernameChange = $_POST["userChange"];
    $password = md5($_POST['password']);
   

    $SQLChangeData = "UPDATE classsheet.user SET username ='$usernameChange',password = '$password' WHERE user.ID=$changeID";
    mysql_query($SQLChangeData);
    print $SQLChangeData."<br />";
    //ReLoad the $semesterSet
    $userArray = queryUserArray();
    $userArrayCount0 = count($userArray);
    ?>
    <script>
	 alert("成功修改用户数据！");
	 </script>
    <?php
    
}elseif($_POST["userInfoChangeSubmitted"] == "yes"){
    ?>
    <script>
	 alert("两次输入密码不一致！");
	 </script>
    <?php
}

mysql_close($DBConnect);
?>


<div class="title">
<h2>用户管理</h2>
<div class="underline"></div>
<h6>User &nbsp;Management</h6>
</div>
<div style="clear:both;"></div>

<div class="form">
<?php if(!$_POST["userListChange"]){
?>
<div class="userBlockLeft">
<form action="setting.php" method="post">

<input type="hidden" name="userListSubmitted" value="yes" />


<p>[&nbsp;用户列表&nbsp;]</p>
<select name="userList" size="10">
    <?php
    for($i=0;$i<$userArrayCount0;$i++){
        ?><option value="<?php print ($i);?>"<?php if($_POST["userListSubmitted"]&&$_POST["userList"] == $i){print("selected");}?>><?php print(($i+1).".".$userArray[$i][0]);?></option>
     <?php  
    }
     ?>        
</select>
<input type="submit" value="&nbsp;修改&nbsp;" name="userListChange" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="userListDelete" style="margin-top: 10px;" />

</form>
</div>
<?php
}
?>




<?php
if($_POST["userListSubmitted"] != "yes" || $_POST["userListDelete"]){
 ?>
<div class="userBlockRight">
<form action="setting.php" method="post">
<input type="hidden" name="userInfoSubmitted" value="yes" />

<p>[&nbsp;用户信息&nbsp;]</p>
<span>用户姓名<input type="text" name="username" maxlength="10" size="9" /></span><br />
<span>输入密码<input type="password" name="password" maxlength="20" size="9" /></span><br />
<span>重复密码<input type="password" name="repassword" maxlength="20" size="9" /></span>
<br /><input type="submit" value="&nbsp;添加&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />

</form>
</div>
<?php
}
?>

<?php
if($_POST["userListChange"]){
$userListNumber = $_POST["userList"];
 ?>



<form action="setting.php" method="post">
<input type="hidden" name="userInfoChangeSubmitted" value="yes" />
<div class="userBlockLeft">
<p>[&nbsp;用户列表&nbsp;]</p>
<select name="userList" size="10">
    <?php
    for($i=0;$i<$userArrayCount0;$i++){
        ?><option value="<?php print($i);?>"<?php if($_POST["userListSubmitted"]&&$_POST["userList"] == $i){print("selected");}?>><?php print(($i+1).".".$userArray[$i][0]);?></option>
     <?php  
    }
     ?>        
</select>
<input type="submit" value="&nbsp;修改&nbsp;" style="margin-top: 10px;" /><input type="submit" value="&nbsp;删除&nbsp;" name="userListDelete" style="margin-top: 10px;" />
</div>

<div class="userBlockRight">
<p>[&nbsp;用户信息&nbsp;]</p>
<span>用户姓名<input type="text" name="userChange" maxlength="9" size="9" value="<?php print($userArray[$userListNumber][0]);?>" /></span><br />
<span>输入密码<input type="password" name="password" maxlength="20" size="9" /></span><br />
<span>重复密码<input type="password" name="repassword" maxlength="20" size="9" /></span>
<br /><input type="submit" value="&nbsp;提交&nbsp;" style="margin-top: 10px;" /> <input type="reset" value="&nbsp;重置&nbsp;" style="margin-top: 10px;" />
</div>
</form>
<?php
}
?>


</div>
</div>
</body>
</html>