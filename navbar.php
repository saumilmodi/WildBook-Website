<html>
	<head>
		<title>WildBook</title>
		<meta name="viewport" content-"width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<style>
			#home,#notifications,#diary
			{
				color:white; padding:5px; cursor:pointer; font-size:19px;
			}
			#logout
			{
				color:white; border:solid white 1px; padding:7px; cursor:pointer;
			}
		</style>
		<script>
			$(document).ready(function(){
				$("#home").mouseover(function(){
					$("#home").css("background-color","grey");
				});
				$("#home").mouseout(function(){
					$("#home").css("background-color","initial");
				});
				$("#notifications").mouseover(function(){
					$("#notifications").css("background-color","grey");
				});
				$("#notifications").mouseout(function(){
					$("#notifications").css("background-color","initial");
				});
				$("#diary").mouseover(function(){
					$("#diary").css("background-color","grey");
				});
				$("#diary").mouseout(function(){
					$("#diary").css("background-color","initial");
				});
				$("#logout").mouseover(function(){
					$("#logout").css("background-color","grey");
				});
				$("#logout").mouseout(function(){
					$("#logout").css("background-color","black");
				});
			});
		</script>
	</head>
	<body>
		<div class="container">
			<div class="navbar navbar-inverse navbar-fixed-top" style="height:10%;">
				<a class="navbar-brand" href="home.php" style="cursor:pointer; font-size:30px; padding-left:3%; padding-top:20px;">WildBook</a>
				<div class="collapse navbar-collapse navHeaderCollapse">
					<ul class="nav navbar-nav" style="padding-top:10px; padding-right:10px; float:right;">
						<li id="home" onclick="window.location.assign('home.php')">&nbsp;&nbsp;&nbsp;Home&nbsp;&nbsp;&nbsp;</li>
						<li id="diary" onclick="window.location.assign('diary.php')">&nbsp;&nbsp;&nbsp;Diary&nbsp;&nbsp;&nbsp;</li>
						<li id="search">
							<form class="form-inline" role="form" method="post" action="search.php">
								<div class="form-group">
									&nbsp;&nbsp;&nbsp;<input type="text" class="form-control" name="search">
								</div>
								<button type="submit" class="btn btn-default">Search</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</form>
						<li>
						<li id="logout" onclick="window.location.assign('logout.php')">Logout</li>
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>