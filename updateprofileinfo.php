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

$r=array(1,1,1,1);

if(ctype_alpha($_POST['first_name']) && $_POST['first_name']!="")
{
	$first_name=$_POST['first_name'];
}
else
{
	$first_name=NULL;
	$r[0]=0;
}

if(ctype_alpha($_POST['last_name']) && $_POST['last_name']!="")
{
	$last_name=$_POST['last_name'];
}
else
{
	$last_name=NULL;
	$r[1]=0;
}

$dob=$_POST['date_of_birth'];
if(strlen($dob)==10)
{
	$d1=substr($dob,0,2);
	$d2=substr($dob,3,2);
	$d3=substr($dob,6,4);
	if(ctype_digit($d1) && ctype_digit($d2) && ctype_digit($d3))
	{
		$date_of_birth=$d3 . "-" . $d1 . "-" . $d2;
	}
	else
	{
		$date_of_birth=NULL;
		$r[2]=0;
	}
}
else
{
	$date_of_birth=NULL;
	$r[2]=0;
}

$temp=str_replace(' ','',$_POST['city']);
if(ctype_alpha($temp) && $temp!="")
{
	$city=$_POST['city'];
}
else
{
	$city=NULL;
	$r[3]=0;
}

if($r[0]!==0 && $r[1]!=0 && $r[2]!=0 && $r[3]!=0)
{
	mysqli_query($con,"UPDATE user SET first_name='$first_name',last_name='$last_name',date_of_birth='$date_of_birth',city='$city' WHERE user_name='$username'");
}
echo "".$r[0].$r[1].$r[2].$r[3]."";
?>