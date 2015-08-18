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

$result5 = mysqli_query($con,"SELECT * FROM user_activity WHERE activity = '" . $activityname . "' AND user_name ='" . $username . "'");
if(mysqli_num_rows($result5)==0)
{
	echo "1";
}
else
{
	echo "2";
}
?>