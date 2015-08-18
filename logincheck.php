<?php
	include("initdb.php");
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{

		$username = $_POST["username"];
		$password = hash('sha256',$_POST["password"]);

		if(ctype_alnum($username))
		{
			$result = mysqli_query($con,"SELECT * FROM user WHERE user_name='" . $username . "' AND password='" . $password . "'");
			if(mysqli_num_rows($result)>0)
			{
				session_start();
				$_SESSION['username']=$username;
				echo "correct";
			}
			else
			{
				echo "1"; //Not registered
			}
		}
		else
		{
			echo "2"; //Invalid Username
		}
	}
	
?>