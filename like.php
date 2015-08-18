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
else
	header('Location:diary.php');
	
mysqli_query($con,"INSERT INTO user_like(user_name,post_id) VALUES('$username','$postid')");

?>