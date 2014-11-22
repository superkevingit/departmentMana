<meta charset="UTF-8">
<?php
session_start();
error_reporting(0);
?>
<?php
include("conn.php"); 
function redirect()
{
	$url="index.php";
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='$url'";
	echo "</script>";               //重新回到主页
}
if (isset ($_SESSION['un']))
{
	redirect();
}
else
{
	$un=$_POST['username'] ;    
	$pw=md5($_POST['password']) ; 
	$check_query=mysql_query("SELECT * FROM `editors` WHERE `un`='$un' and `pw`='$pw'"); 	
	if ($result= mysql_fetch_array($check_query))
	{
		$_SESSION['un']=$un;        //注册新的变量,保存当前会话的昵称
		$_SESSION['pw']=$pw;       //登录成功回到主页面
		echo "<script>alert('登陆成功');</script>";
		redirect();
	}
    else
	{
		echo "<script>alert('登陆信息不正确');</script>";
	}
}
?>