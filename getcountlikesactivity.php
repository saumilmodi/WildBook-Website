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

$result6 = mysqli_query($con,"SELECT count(user_name) AS likescount FROM user_activity WHERE activity='" . $activityname . "' GROUP BY activity");
while($row2 = mysqli_fetch_array($result6))
{
	echo $row2['likescount'];
}
?>