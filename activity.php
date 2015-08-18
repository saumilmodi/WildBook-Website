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
			function gotoactivity(name)
			{
				document.getElementById("autoform").action="activity.php";
				document.getElementById("passdata").name="activityname";
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
			function likeactivity(activityname)
			{
				$.post("likeactivity.php",
				{
					activityname:activityname
				},
				function(data,status)
				{
					hasuserliked(activityname);
					countlikes(activityname);
				}
				);
			}
			function hasuserliked(activityname)
			{
				$.post("hasuserlikedactivity.php",
				{
					activityname:activityname
				},
				function(data,status)
				{
					if(data==2)
					{
						document.getElementById("likebutton").innerHTML="<a style='font-size:20px;cursor:default;'>Liked</a>";
					}
				}
				);
				document.getElementById("likebutton").style.visibility="visible";
			}
			function countlikes(activityname)
			{
				$.post("getcountlikesactivity.php",
				{
					activityname:activityname
				},
				function(data,status)
				{
					if(data==1)
					{
						document.getElementById("countlikes").innerHTML=data+" like";
					}
					else if(data>1)
					{
						document.getElementById("countlikes").innerHTML=data+" likes";
					}
				}
				);
				document.getElementById("countlikes").style.visibility="visible";
			}
		</script>
	</head>
	<body>
		<div style="position:absolute;top:10%;left:0%;width:60%;height:90%;">
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
				if($_POST["activityname"] && ctype_alnum(str_replace(" ","",$_POST["activityname"])))
				{
					$activityname = $_POST["activityname"];
					echo "<iframe style='position:absolute;left:0%;top:0%;width:100%;height:100%;' src='http://en.wikipedia.org/wiki/".$activityname."'></iframe>
		</div>
		<div style='position:absolute;top:10%;left:60%;width:40%;height:90%;text-align:center;'>";
					
					echo "	<br><br><br><br><br><br><br><a style='font-size:40px;cursor:pointer;' onclick=gotoactivity('".$activityname."')>" . $activityname . "</a><br><br><br>
							<div id='likebutton' style='visibility:hidden;'><img style='width:30%;height:30%;cursor:pointer;' src='img/like.gif' onclick=likeactivity('".$activityname."')></div><br><br><p id='countlikes' style='visibility:hidden;font-size:20px;cursor:default;'>Like ".$activityname." on Wildbook
							<script>hasuserliked('".$activityname."'); countlikes('".$activityname."');</script>";
					
				}
				
			?>
		</div>
		<form id="autoform" method="post" action="diary.php">
			<input type="hidden" id="passdata" name="userwall">
		</form>
	</body>
</html>