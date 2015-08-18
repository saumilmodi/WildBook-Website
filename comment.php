<?php
include("initdb.php");
session_start();
include("validateinput.php");
if(!isset($_SESSION['username']))
{
	header('Location:index.html');
}
else
{
	$username=$_SESSION['username'];
}

if(ctype_digit($_POST['postid']))
	$postid = $_POST['postid'];
else
	header('Location:diary.php');

$comment = test_input($_POST['comment']);

mysqli_query($con,"INSERT INTO user_comment(user_name,post_id,comment) VALUES('$username','$postid','$comment')");

?>