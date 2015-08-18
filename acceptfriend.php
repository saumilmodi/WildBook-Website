<?php
error_reporting(0);
include("initdb.php");
session_start();
if(!isset($_SESSION['username']))
{
	header('Location:index.html');
}
else
{
	$username=$_SESSION['username'];
}

if($_POST["userfriend"] && ctype_alnum($_POST["userfriend"]))
{
	$userfriend = $_POST["userfriend"];
	if(strcmp($_POST["flag"],"yes")==0)
	{
		mysqli_query($con,"Insert into `user_friend` (`user_name`,`user_friend`,`time_stamp`) values ('$username', '$userfriend', now());");
		mysqli_query($con,"Delete from `user_friend_request` where `user_name`='$userfriend' and `user_friend`='$username'");
	}
	else if(strcmp($_POST["flag"],"no")==0)
	{
		mysqli_query($con,"Delete from `user_friend_request` where `user_name`='$userfriend' and `user_friend`='$username'");
	}
}

?>