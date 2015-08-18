<html>
	<head>
		<title>WildBook</title>
		<meta name="viewport" content-"width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<style>
			
		</style>
		<script>
			function gotoprofile(name)
			{
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
			function gotoactivity(name)
			{
				document.getElementById("autoform").action="activity.php";
				document.getElementById("passdata").name="activityname";
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
		</script>
	</head>
	<body>
			<?php
				error_reporting(0);
				include("navbar.php");
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
				if($_POST["search"] && ctype_alnum(str_replace(" ","",$_POST["search"])))
				{
					$query = $_POST["search"];
				}
			?>
			<div style="position:absolute; bottom:0%; width:100%; height:90%; background-color:lightskyblue;">
				<div style="position:absolute;left:0%;width:30%;height:100%;background-color:white;border-right:solid black 1px;text-align:center;overflow-y:scroll;">
					<br><p style='font-size:20px;'>USERS</p>
					<?php
						$result1 = mysqli_query($con,"SELECT * FROM user WHERE user_name LIKE '%" . $query . "%' OR first_name LIKE '%" . $query . "%' OR last_name LIKE '%" . $query . "%'");
						if(mysqli_num_rows($result1)==0)
							echo "<br><br><p style='font-size:20px;'>No users found containing the given keywords</p>";
						while($row1 = mysqli_fetch_array($result1))
						{
							echo "<br><a style='font-size:20px;cursor:pointer;' onclick=gotoprofile('".$row1["user_name"]."')>" . $row1["first_name"] . " " . $row1["last_name"] . "</a><br>";
							if($row1['photo']!="0")
							{
								echo "<div style='cursor:pointer;position:absolute;left:30%;width:40%;height:30%;background-image: url(\"uploads/".$row1['user_name']."/profilepic.".$row1['photo']."\");background-size:cover;' onclick='gotoprofile(\"". $row1['user_name'] . "\")'></div>";
							}
							else
							{
								echo "<div style='cursor:pointer;position:absolute;left:30%;width:40%;height:30%;background-color:lightgrey;' onclick='gotoprofile(\"". $row1['user_name'] . "\")'><p style='position:absolute;top:40%;left:20%;'>No Profile Pic</p></div>";
							}
							echo "<br><br><br><br><br><br><br><br><br>";
						}
					?>
				</div>
				<div style="position:absolute;left:30%;width:50%;height:100%;background-color:white;border-right:solid black 1px;text-align:center;overflow-y:scroll;">
					<br><p style='font-size:20px;'>POSTS</p>
					<?php
						$result2 = mysqli_query($con,"SELECT * FROM user_post JOIN user ON user_post.user_name=user.user_name WHERE activity_title LIKE '%" . $query . "%' OR activity_description LIKE '%" . $query . "%'");
						if(mysqli_num_rows($result2)==0)
							echo "<br><br><p style='font-size:20px;'>No posts found with the given keywords</p>";
						while($row2 = mysqli_fetch_array($result2))
						{
							echo "<br><p style='font-size:20px;'>By " . $row2['first_name'] . " " . $row2['last_name'] . "<b><br>" . $row2["activity_title"] . "</b><br>Activity Name: <a style='cursor:pointer;' onclick=gotoactivity('".$row2['activity_name']."')>" . $row2["activity_name"] . "</a><br>" . $row2["activity_description"] . "<br>";
							if($row2["multimedia"])
							{
								echo "<img style='width:70%;height:50%;' src='uploads/".$username."/".$row2['multimedia']."'><br>";
							}
						}
					?>		
						
				</div>
				<div style="position:absolute;left:80%;width:20%;height:100%;background-color:white;text-align:center;overflow-y:scroll;">
					<br><p style='font-size:20px;'>ACTIVITIES</p>
					<?php
						$result3 = mysqli_query($con,"SELECT * FROM activity WHERE activity_name LIKE '%" . $query . "%'");
						if(mysqli_num_rows($result3)==0)
							echo "<br><br><p style='font-size:20px;'>No activities found with the given keywords</p>";
						while($row3 = mysqli_fetch_array($result3))
						{
							echo "<br><a style='font-size:20px; cursor:pointer;' onclick=gotoactivity('" . $row3['activity_name'] . "')>" . $row3["activity_name"] . "<br><img style='width:80%;height:30%;' src='img/".$row3["activity_name"].".jpg'></a><br>";
						}
					?>
				</div>
			</div>
			
			<form id="autoform" method="post" action="diary.php">
				<input type="hidden" id="passdata" name="userwall">
			</form>
			
	</body>
</html>