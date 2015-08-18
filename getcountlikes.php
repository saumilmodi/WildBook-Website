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

if($_POST['postid'] && ctype_digit($_POST['postid']))
	$postid = $_POST['postid'];

$result6 = mysqli_query($con,"SELECT count(post_id) AS likescount FROM user_like WHERE post_id='" . $postid . "' GROUP BY post_id");
while($row2 = mysqli_fetch_array($result6))
{
	echo $row2['likescount'];
}
?>