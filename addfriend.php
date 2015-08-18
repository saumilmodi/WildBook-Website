<?php
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

if($_POST['userwall'] && ctype_alnum($_POST['userwall']))
	$userwall = $_POST['userwall'];

mysqli_query($con,"Insert into `user_friend_request`(`user_name`,`user_friend`) values ('$username','$userwall')");

?>