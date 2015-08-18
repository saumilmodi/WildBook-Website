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

if($_POST['activityname'] && ctype_alnum($_POST['activityname']))
	$activityname = $_POST['activityname'];
else
	header('Location:home.php');
	
mysqli_query($con,"INSERT INTO user_activity(user_name,activity) VALUES('$username','$activityname')");

?>