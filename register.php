<?php
	include("initdb.php");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username = $_POST["username"];
		$password = hash('sha256',$_POST["password"]);

		if(ctype_alnum($username))
		{
			$result = mysqli_query($con,"SELECT * FROM user WHERE user_name='" . $username . "'");
			if(mysqli_num_rows($result)>0)
			{
				echo "1"; //Duplicate Username
			}
			else
			{
				mysqli_query($con,"INSERT INTO user (user_name,password,photo) values('$username','$password','0')");
				mkdir("uploads/".$username);
				session_start();
				$_SESSION['username']=$username;
				echo "correct";
			}
		}
		else
		{
			echo "2"; //Invalid Username
		}
	}
	
?>