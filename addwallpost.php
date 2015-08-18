<?php
include("initdb.php");
include("validateinput.php");
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
{
	$userwall=$_POST['userwall'];
}
else
{
	$userwall=NULL;
}
if($_POST['addtext'])
{
	$addtext=$_POST['addtext'];
	$addtext = test_input($addtext);
}
else
{
	$addtext=NULL;
}

mysqli_query($con,"INSERT INTO user_post(user_profile,user_name,activity_description) values('$userwall','$username','$addtext')");


?>