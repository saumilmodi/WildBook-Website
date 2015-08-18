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

$finalresult = "";

$result1 = mysqli_query($con,"SELECT count(*) AS a FROM user_friend_request WHERE user_friend = '$username'");
while($row1=mysqli_fetch_array($result1))
{
	$finalresult = $finalresult + $row1['a'];
}

$result3 = mysqli_query($con,"SELECT count(*) AS a
        FROM user_post as up left outer join user_like as ul on up.post_id=ul.post_id
	left outer join user_comment as uc on up.post_id=uc.post_id
	left outer join location as l on up.location_id=l.location_id
	where up.user_profile='$username' and 
		(up.time_stamp > (SELECT last_login FROM user where user_name='$username') or 
    	ul.time_stamp > (SELECT last_login FROM user where user_name='$username' ) or 
        uc.time_stamp > (SELECT last_login FROM user where user_name='$username' ))");
while($row3=mysqli_fetch_array($result3))
{
	$finalresult = $finalresult . "&" . $row3['a'];
}

echo $finalresult;

?>