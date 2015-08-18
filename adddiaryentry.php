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
if($_POST['addtitle'])
{
	$addtitle=$_POST['addtitle'];
	$addtitle = test_input($addtitle);
}
else
{
	$addtitle=NULL;
}
if($_POST['addactivity'])
{
	$addactivity=$_POST['addactivity'];
	$addactivity = test_input($addactivity);
}
else
{
	$addactivity=NULL;
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
if($_POST['addlocationtext'])
{
	$addlocationtext=$_POST['addlocationtext'];
	$addlocationtext = test_input($addlocationtext);
}
else
{
	$addlocationtext=NULL;
}

$permission = $_POST['permission'];

if(is_uploaded_file($_FILES["file2"]["tmp_name"]))
{	
		$allowedExts = array("gif", "jpeg", "jpg", "png","mp4");
		$temp = explode(".", $_FILES["file2"]["name"]);
		$filename = $_FILES["file2"]["name"];

		  function check($con,$count,$username,$filename,$addtitle,$addactivity,$addtext,$addlocationtext,$permission,$temp)
		  {
			if (file_exists("uploads/" . $username . "/" . $filename))
			{
				$filename= $temp[0] . $count . "." . $temp[1];
				$count=$count+1;
				check($con,$count,$username,$filename,$addtitle,$addactivity,$addtext,$addlocationtext,$permission,$temp);
			}
			else
			{
				move_uploaded_file($_FILES["file2"]["tmp_name"],"uploads/" . $username . "/" . $filename);
					if($addlocationtext)
						mysqli_query($con,"INSERT INTO user_post(user_profile,user_name,activity_title,activity_name,activity_description,location_id,permission,multimedia) values('$username','$username','$addtitle','$addactivity','$addtext','$addlocationtext','$permission','$filename')");
					else
						mysqli_query($con,"INSERT INTO user_post(user_profile,user_name,activity_title,activity_name,activity_description,permission,multimedia) values('$username','$username','$addtitle','$addactivity','$addtext','$permission','$filename')");
				$_SESSION['shared']=1;
				header('Location:diary.php');
			}
		  }

		$extension = end($temp);
		if ((($_FILES["file2"]["type"] == "image/gif")
		|| ($_FILES["file2"]["type"] == "image/jpeg")
		|| ($_FILES["file2"]["type"] == "image/jpg")
		|| ($_FILES["file2"]["type"] == "image/pjpeg")
		|| ($_FILES["file2"]["type"] == "image/x-png")
		|| ($_FILES["file2"]["type"] == "image/png"))
		&& ($_FILES["file2"]["size"] < 10000000)
		&& in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file2"]["error"] > 0)
			{
			echo "Return Code: " . $_FILES["file2"]["error"] . "<br>";
			}
		  else
			{
				$count=0;
				check($con,$count,$username,$filename,$addtitle,$addactivity,$addtext,$addlocationtext,$permission,$temp);
			}
		  }
		else
		  {
		  echo "Invalid file";
		  }
}
else
{	
		if($addlocationtext)
			mysqli_query($con,"INSERT INTO user_post(user_profile,user_name,activity_title,activity_name,activity_description,location_id,permission) values('$username','$username','$addtitle','$addactivity','$addtext','$addlocationtext','$permission')");
		else
			mysqli_query($con,"INSERT INTO user_post(user_profile,user_name,activity_title,activity_name,activity_description,permission) values('$username','$username','$addtitle','$addactivity','$addtext','$permission')");
		$_SESSION['shared']=1;
		header('Location:diary.php');
		
}
?>