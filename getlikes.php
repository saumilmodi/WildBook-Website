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

$result = mysqli_query($con,"SELECT user_name,first_name,last_name FROM user_like NATURAL JOIN user WHERE post_id='" . $postid . "'");
while($row = mysqli_fetch_array($result))
{
	$json[] = array( 	'username' => $row['user_name'],
						'firstname' => $row['first_name'],
						'lastname' => $row['last_name']);
}
if($json)
{
	$jsonstring = json_encode($json);
	echo $jsonstring;
}
?>