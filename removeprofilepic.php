<?php
include("initdb.php");
session_start();
if(is_null($_SESSION['username']))
{
	header('Location:index.html');
}
else
{
	$username=$_SESSION['username'];
}

unlink("uploads/".$username."/profilepic.".$_SESSION['profilepic']);
mysqli_query($con,"UPDATE user SET photo='0' WHERE user_name='" . $username . "'");
header('Location:diary.php');

?>