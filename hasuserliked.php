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

$result5 = mysqli_query($con,"SELECT * FROM user_like WHERE post_id = '" . $postid . "' AND user_name ='" . $username . "'");
if(mysqli_num_rows($result5)==0)
{
	echo "1";
}
else
{
	echo "2";
}
?>