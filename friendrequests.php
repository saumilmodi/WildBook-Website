<?php
error_reporting(0);
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

$result1 = mysqli_query($con,"SELECT * FROM user_friend_request NATURAL JOIN USER WHERE user_friend = '$username'");
while($row1=mysqli_fetch_array($result1))
{
	$json[] = array( 	'user_name'=>$row1['user_name'],
						'first_name'=>$row1['first_name'],
						'last_name'=>$row1['last_name'],
						'photo'=>$row1['photo']);
}
$jsonstring = json_encode($json);
echo $jsonstring;

?>