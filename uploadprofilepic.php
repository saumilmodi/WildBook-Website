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

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 1000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {

    if (file_exists("uploads/" . $username . "/" . $_FILES["file"]["name"]))
      {
		unlink("uploads/".$username."/profilepic.".$_SESSION['profilepic']);
      }
      move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/" . $username . "/" . "profilepic.".$temp[1]);
      mysqli_query($con,"UPDATE user SET photo='$temp[1]' WHERE user_name='" . $username . "'");
	  header('Location:diary.php');
    }
  }
else
  {
  echo "Invalid file";
  }
?>