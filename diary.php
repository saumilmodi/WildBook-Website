<html>
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
		if(isset($_POST['userwall']))
		{
			if(ctype_alnum($_POST['userwall']))
			{
				if(strcmp($username,$_POST['userwall'])==0)
				{
					$userwall = $username;
					$mapping = 0;
				}
				else
				{
					$userwall = $_POST['userwall'];
					$result0 = mysqli_query($con,"SELECT * FROM `user_friend` WHERE (`user_name` = '" . $username . "' AND `user_friend` = '" . $userwall . "') OR (`user_name` = '" . $userwall . "' AND `user_friend` = '" . $username . "')");
					if(mysqli_num_rows($result0)>0)
					{
						$mapping = 1;
					}
					else
					{
						$resultmutual = mysqli_query($con,"Select `a` FROM
													(Select `user_friend` as `a` from `user_friend` where `user_name` = $username
													UNION
													Select `user_name` from `user_friend` where`user_friend` = $username)
													as `flist1`
													JOIN
													(Select `user_friend` as `b` from `user_friend` where `user_name` = $userwall
													UNION
													Select `user_name` from `user_friend` where`user_friend` = $userwall)
													as `flist2`
													ON `a`= `b`");
						if(mysqli_num_rows($resultmutual)>0)
						{
							$mapping = 2;
						}
					}
				}
			}
		}
		else
		{
			$userwall = $username;
			$mapping = 0;
		}
	?>
	<head>
		<title>WildBook</title>
		<meta name="viewport" content-"width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/datepicker.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		
		<style>
			#activitydiv::-webkit-scrollbar { 
			display: none; 
			}
			#addphoto
			{position:absolute; top:15%; left:75%; width:20%; text-align:center; cursor:pointer;}
			#addlocationbutton
			{position:absolute; top:32.5%; left:75%; width:20%; text-align:center; cursor:pointer;}
			#address
			{visibility:hidden; position:absolute; top:75%; left:3%; width:70%; height:15%; text-align:center;}
			#closelocation
			{visibility:hidden; position:absolute; top:75%; left:68%; width:4%;height:15%; opacity:0.6;}
			#pencil
			{position:absolute; top:20%; left:53%; width:5%;height:20%;font-size:30px;font-family:wingdings;cursor:default;}
			#addtitle
			{visibility:hidden; position:absolute; top:15%; left:3%; width:70%; height:15%; z-index:5; text-align:center;}
			#addactivity
			{visibility:hidden; position:absolute; top:30%; left:3%; width:70%; height:15%; z-index:5; text-align:center;}
			#addtext
			{position:absolute; top:15%; left:3%; width:70%; height:60%;overflow-y:scroll; text-align:center;}
			#postentry
			{position:absolute; top:52.5%; left:75%; width:20%; height:22%;}
			#shared
			{position:absolute; top:95%; left:75%; width:20%; color:green;text-align:center;}
			#tick
			{position:absolute; top:95%; left:90%; color:green; font-family:wingdings;}
			#permission
			{position:absolute; top:80%; left:85%; width:10%; text-align:center;}
			#privacytext
			{position:absolute; top:80%; left:75%; width:10%; text-align:center;}
		</style>
		<script>
			$.noConflict();
			jQuery(document).ready(function($){
				$('#date_of_birth').datepicker();
				$('#addlocationbutton').click(function(){
					$('#address').css('visibility','visible');
					$('#closelocation').css('visibility','visible');
				});
				$('#closelocation').click(function(){
					$('#address').css('visibility','hidden');
					$('#closelocation').css('visibility','hidden');
				});
				$('#addphoto').click(function(){
					$('#file2').click();
				});
				$('#addtext').click(function(){
					$('#addtext').css('top','45%');
					$('#addtext').css('height','40%');
					$('#addtext').attr('placeholder','Add Description');
					$('#addtitle').css('visibility','visible');
					$('#addactivity').css('visibility','visible');
					$('#pencil').css('visibility','hidden');
				});
				$('#postentry').click(function(){
					if(document.getElementById('addtitle').value=="")
					{
						$('#addtitle').css('border-color','red');
					}
					else if(document.getElementById('addactivity').value=="")
					{
						$('#addactivity').css('border-color','red');
					}
					else if($('#addtext').val()=="" && $('#file').val()=="")
					{
						$('#addtext').css('border-color','red');
						$('#addphoto').css('color','red');
					}
					else
					{
						$("#addentry").submit();
					}
				});
				$('#imgdiv').mouseover(function(){
					$('#uploadform').css('visibility','visible');
					$('#removeprofilepic').css('visibility','visible');
				});
				$('#uploadform').mouseover(function(){
					$('#uploadform').css('visibility','visible');
				});
				$('#imgdiv').mouseout(function(){
					$('#uploadform').css('visibility','hidden');
					$('#removeprofilepic').css('visibility','hidden');
				});
				$('#uploadform').mouseout(function(){
					$('#uploadform').css('visibility','hidden');
				});
				$('#uploadbutton').click(function(){
					$('#file').click();
				});
				$('#file').change(function(){
					$('#commit').click();
				});
				$('#removeprofilepic').click(function(){
					window.location.assign('removeprofilepic.php');
				});
				$('#updateinfo').click(function(){
					$.post("updateprofileinfo.php",
							{
							first_name:(document.getElementById("first_name").value),
							last_name:(document.getElementById("last_name").value),
							date_of_birth:(document.getElementById("date_of_birth").value),
							city:(document.getElementById("city").value)
							},
							function(data,status)
							{
								if(data.charAt(0)==0)
								{
									$('#first_name').css('border-color','red');
								}
								else if(data.charAt(1)==0)
								{
									$('#last_name').css('border-color','red');
								}
								else if(data.charAt(2)==0)
								{
									$('#date_of_birth').css('border-color','red');
								}
								else if(data.charAt(3)==0)
								{
									$('#city').css('border-color','red');
								}
								else
								{
									window.location.assign('diary.php');
								}
							}
						);
				});
				$('#editinfo').click(function(){
					$('#dfirst_name').css('visibility','hidden');
					$('#dlast_name').css('visibility','hidden');
					$('#ddate_of_birth').css('visibility','hidden');
					$('#dcity').css('visibility','hidden');
					$('#editinfo').css('visibility','hidden');
					$('#first_name').css('visibility','visible');
					$('#last_name').css('visibility','visible');
					$('#date_of_birth').css('visibility','visible');
					$('#city').css('visibility','visible');
					$('#updateinfo').css('visibility','visible');
				});
				$("#showmore").click(function(){
					$("#allfriendsdiv").fadeIn();
				});
				$("#closefriends").click(function(){
					$("#allfriendsdiv").fadeOut();
				});
				$(document).mousemove(function(event){ 
					mousex = event.pageX;
					mousey = event.pageY;
			  });
			});

			function gotoprofile(name)
			{
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
			function like(postid)
			{
				$.post("like.php",
				{
					postid:postid
				},
				function(data,status)
				{
					hasuserliked(postid);
					countlikes(postid);
				}
				);
			}
			function commentfunc(postid)
			{
				$.post("comment.php",
				{
					postid:postid,
					comment:(document.getElementById(postid+"comment").value)
				},
				function(data,status)
				{
					getcomments(postid);
				}
				);
			}
			function hasuserliked(postid)
			{
				$.post("hasuserliked.php",
				{
					postid:postid
				},
				function(data,status)
				{
					if(data==2)
					{
						document.getElementById(postid+"likebutton").innerHTML="<a style='font-size:15px;cursor:pointer;'>Liked</a>";
					}
					else
					{
						document.getElementById(postid+"likebutton").innerHTML="<img style='cursor:pointer;text-align:left;width:30px;height:30px;' src='img/like.gif' onClick=like('"+postid+"')>";
					}
				}
				);
			}
			function countlikes(postid)
			{
				$.post("getcountlikes.php",
				{
					postid:postid
				},
				function(data,status)
				{
					if(data==1)
					{
						document.getElementById(postid+"countlikes").innerHTML=data+" like";
					}
					else if(data>1)
					{
						document.getElementById(postid+"countlikes").innerHTML=data+" likes";
					}
				}
				);
			}
			function getlikes(postid)
			{
				$.post("getlikes.php",
				{
					postid:postid
				},
				function(data,status)
				{
					var temp = JSON.parse(data);
					var middle="";
					if(temp.length>=10)
						document.getElementById(postid+"likes").style.overflow="scroll";
					for(var i=0;i<temp.length;i++)
					{
						middle = middle + "<a style='cursor:pointer;' onclick=gotoprofile('"+temp[i]['username']+"')>"+temp[i]['firstname']+" "+temp[i]['lastname']+"</a><br>";
					}
					document.getElementById(postid+"likes").style.left=mousex+"px";
					document.getElementById(postid+"likes").style.top=mousey+"px";
					document.getElementById(postid+"likes").innerHTML = middle;
					document.getElementById(postid+"likes").style.visibility = "visible";
				}
				);
			}
			function hidelikes(postid)
			{
				document.getElementById(postid+"likes").style.visibility="hidden";
			}
			function getcomments(postid)
			{
				$.post("getcomments.php",
				{
					postid:postid
				},
				function(data,status)
				{
					var temp = JSON.parse(data);
					document.getElementById(postid+"comments").innerHTML="";
					for(var i=0;i<temp.length;i++)
					{
						var prev = document.getElementById(postid+"comments").innerHTML;
						if(i==0)
						{
							prev = prev + "<br>";
							document.getElementById(postid+"comments").innerHTML= prev + "<a style='cursor:pointer;' onclick=gotoprofile('"+temp[i]['username']+"')>"+temp[i]['firstname']+" "+temp[i]['lastname']+"</a><br><p>"+temp[i]['comment']+"</p>";
						}
					}
				}
				);
			}
			function addfriend(userwall)
			{
				$.post("addfriend.php",
				{
					userwall:userwall
				},
				function(data,status)
				{
					document.getElementById("addfriend").style.visibility="hidden";
					document.getElementById("requestsent").style.visibility="visible";
				}
				);
			}
			function gotoactivity(name)
			{
				document.getElementById("autoform").action="activity.php";
				document.getElementById("passdata").name="activityname";
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
			function addwallpost(userwall)
			{
				$.post("addwallpost.php",
				{
					userwall:userwall,
					addtext:(document.getElementById("wallposttext").value)
				},
				function(data,status)
				{
					gotoprofile(userwall);
				}
				);
			}
			function acceptfriend(userwall)
			{
				var flag="yes";
				$.post("acceptfriend.php",
				{
					userfriend:userwall,
					flag:flag
				},
				function(data,status)
				{
					gotoprofile(userwall);
				}
				);
			}
			function denyfriend(userwall)
			{
				var flag="no";
				$.post("acceptfriend.php",
				{
					userfriend:userwall,
					flag:flag
				},
				function(data,status)
				{
					gotoprofile(userwall);
				}
				);
			}
		</script>
	</head>
	<body>
			<?php
				$result1 = mysqli_query($con,"SELECT first_name,last_name,photo,date_of_birth,city FROM user WHERE user_name='" . $userwall . "'");
				while($row = mysqli_fetch_array($result1))
				{
					$first_name=$row['first_name'];
					$last_name=$row['last_name'];
					$date_of_birth=$row['date_of_birth'];
					$city=$row['city'];
					$photo=$row['photo'];
				}
			?>
			<div style="position:absolute; top:10%; width:100%; height:90%; background-color:lightskyblue; overflow-y:scroll;">
				<div style="position:absolute; top:0%; bottom:5%; left:3%; width:32%; height:214%; background-color:white;  border:solid grey 1px;">
					<div style="position:absolute; top:0%; left:0%; width:100%; height:50%; background-color:white;">
						<?php
							if($photo!='0')
							{
								echo "	<div id='imgdiv' style='position:absolute; left:5%; top:5%; width:45%; height:40%; border:solid grey 2px;'>
											<img style='width:100%; height:100%;' src='uploads/$userwall/profilepic.".$photo."'>";
								if($username==$userwall)
								{
									echo "<input type='button' style='visibility:hidden;position:absolute;right:0%;top:0%;width:15%;height:12.5%;text-align:center; opacity:0.8;' id='removeprofilepic' value='X'>";
								}
								echo "</div>";
							}
							else
							{
								echo "	<div id='imgdiv' style='position:absolute; left:5%; top:5%; width:45%; height:40%; border:solid grey 2px; text-align:center;'>
											<img src='img/face.png'>
										</div>";
							}	
							
							if($username==$userwall)
							{
								echo '<div style="position:absolute; left:5%; top:45%; width:45%; height:10%; background-color:#F0F0F0; visibility:hidden;" id="uploadform">
										<form action="uploadprofilepic.php" method="post" enctype="multipart/form-data">
											<input style="visibility:hidden" type="file" name="file" id="file">
											<a><input style="opacity:0.9;position:absolute;top:20%;left:10%;width:80%;height:60%;text-align:center;" id="uploadbutton" type="button" value="Update Profile Picture"></a>
											<a style="visibility:hidden;"><input type="submit" name="submit" id="commit"></a>
										</form>
									</div>';
							}
						?>
						<!--Profile Info Div-->
						<div>
							<?php
							if($date_of_birth)
							{
								$d1 = date('Y', strtotime($date_of_birth));
								$d2 = date("Y");
								$diff=$d2-$d1;
							}
							echo "	<input type='text' id='first_name' placeholder='First Name' class='form-control' style='position:absolute;left:55%;top:5%;width:40%;height:5%;text-align:center;'>
									<p id='dfirst_name' style='font-size:200%; position:absolute;left:55%;top:5%;width:40%;height:5%;text-align:center;cursor:default;'>" . $first_name . "</p>
									<input type='text' id='last_name' placeholder='Last Name' class='form-control' style='position:absolute;left:55%;top:15%;width:40%;height:5%;text-align:center;'>
									<p id='dlast_name' style='position:absolute;left:55%;top:11%;width:40%;height:5%;text-align:center;cursor:default;'>" . $last_name . "</p>
									<input type='text' id='date_of_birth' placeholder='Date Of Birth' class='form-control' style='position:absolute;left:55%;top:25%;width:40%;height:5%;text-align:center;'>
									<p id='ddate_of_birth' style='position:absolute;left:65%;top:15%;width:20%;height:5%;text-align:center;cursor:default;'>Age: " . $diff . "</p>
									<input type='text' id='city' placeholder='City' class='form-control' style='position:absolute;left:65%;top:35%;width:20%;height:5%;text-align:center;'>
									<p id='dcity' style='position:absolute;left:45%;top:19%;width:60%;height:5%;text-align:center;cursor:default;'>City: " . $city . "</p>
									<input type='button' id='updateinfo' class='btn btn-primary' style='position:absolute;left:60%;top:45%;width:30%;height:6%;text-align:center;' value='Update'>
									<input type='button' id='editinfo' class='btn btn-primary' style='position:absolute;left:60%;top:27%;width:30%;height:6%;text-align:center;' value='Edit'>
									<input type='button' id='addfriend' onclick=addfriend('".$userwall."') class='btn btn-primary' style='position:absolute;left:60%;top:27%;width:30%;height:6%;text-align:center;' value='Add Friend'>
									<input type='button' id='requestsent' class='btn btn-info' style='visibility:hidden;position:absolute;left:60%;top:27%;width:30%;height:6%;text-align:center;' value='Request Sent'>
									<input type='button' id='acceptfriend' class='btn btn-success' onclick=acceptfriend('".$userwall."') style='visibility:hidden;position:absolute;left:60%;top:27%;width:15%;height:6%;text-align:center;' value='Accept'>
									<input type='button' id='denyfriend' class='btn btn-danger' onclick=denyfriend('".$userwall."') style='visibility:hidden;position:absolute;left:80%;top:27%;width:15%;height:6%;text-align:center;' value='Deny'>
									<div id='friends' style='position:absolute;left:60%;top:27%;width:30%;height:6%;text-align:center;cursor:default;'>
										<button class='btn btn-success' style='cursor:default;'>
											<text style='font-family:wingdings;color:white;cursor:default;'>&#252;</text>
											<text style='color:white;cursor:default;'>Friends</text>
										</button>
									</div>
								";
							echo "<script>";
								if(!isset($first_name) || !isset($last_name) || !isset($date_of_birth) || !isset($city))
								{
									echo "document.getElementById('dfirst_name').style.visibility='hidden';";
									echo "document.getElementById('dlast_name').style.visibility='hidden';";
									echo "document.getElementById('ddate_of_birth').style.visibility='hidden';";
									echo "document.getElementById('dcity').style.visibility='hidden';";
									echo "document.getElementById('first_name').style.visibility='visible';";
									echo "document.getElementById('last_name').style.visibility='visible';";
									echo "document.getElementById('date_of_birth').style.visibility='visible';";
									echo "document.getElementById('city').style.visibility='visible';";
									echo "document.getElementById('updateinfo').style.visibility='visible';";
									echo "document.getElementById('editinfo').style.visibility='hidden';";
								}
								else
								{
									echo "document.getElementById('dfirst_name').style.visibility='visible';";
									echo "document.getElementById('dlast_name').style.visibility='visible';";
									echo "document.getElementById('ddate_of_birth').style.visibility='visible';";
									echo "document.getElementById('dcity').style.visibility='visible';";
									echo "document.getElementById('first_name').style.visibility='hidden';";
									echo "document.getElementById('last_name').style.visibility='hidden';";
									echo "document.getElementById('date_of_birth').style.visibility='hidden';";
									echo "document.getElementById('city').style.visibility='hidden';";
									echo "document.getElementById('updateinfo').style.visibility='hidden';";
									if(strcmp($userwall,$username)==0)
									{
										echo "document.getElementById('editinfo').style.visibility='visible';
										document.getElementById('addfriend').style.visibility='hidden';
										document.getElementById('friends').style.visibility='hidden';
										document.getElementById('friendstick').style.visibility='hidden';
										";
									}
									else
									{
										echo "document.getElementById('editinfo').style.visibility='hidden';";
										$result2 = mysqli_query($con,"Select `user_friend` from `user_friend` where `user_name` = '$userwall' AND `user_friend` = '$username' UNION Select `user_name` from `user_friend` where`user_friend` = '$userwall' AND `user_name` = '$username'");
										if(mysqli_num_rows($result2)==0)
										{
											echo "document.getElementById('addfriend').style.visibility='visible';
											document.getElementById('friends').style.visibility='hidden';";
											$result21 = mysqli_query($con,"Select `user_name` from `user_friend_request` where`user_friend` = '$userwall' AND `user_name` = '$username'");
											if(mysqli_num_rows($result21)>0)
											{
												echo "document.getElementById('requestsent').style.visibility='visible';
												document.getElementById('addfriend').style.visibility='hidden';";
											}
											$result22 = mysqli_query($con,"Select `user_friend` from `user_friend_request` where `user_name` = '$userwall' AND `user_friend` = '$username'");
											if(mysqli_num_rows($result22)>0)
											{
												echo "document.getElementById('acceptfriend').style.visibility='visible';
												document.getElementById('denyfriend').style.visibility='visible';
												document.getElementById('addfriend').style.visibility='hidden';";
											}
										}
										else
										{
											echo "document.getElementById('addfriend').style.visibility='hidden';
											document.getElementById('friends').style.visibility='visible';";
										}
									}
								}
							echo "</script>";
							?>
						</div>
						<!--FRIEND LIST DIV-->
						<div style="background-color:#F0F0F0; position:absolute; top:57%; left:2%; width:96%; height:42%; border:solid lightgrey 1px;">
							<?php
								$result3 = mysqli_query($con,"select user_name,photo,first_name,last_name from user where user_name in(Select `user_friend` from `user_friend` where `user_name` = '$userwall' UNION Select `user_name` from `user_friend` where`user_friend` = '$userwall') limit 3");
								if(mysqli_num_rows($result3))
								{
									echo "<p style='cursor:default;text-align:center; padding-top:5px; font-size:18px;'>".$first_name."'s friends</p>
									<p id='showmore' style='cursor:pointer;position:absolute;right:3%;bottom:-2%;font-size:18px;color:blue;'>Show More...</p>";
								}
								else
								{
									echo "<a style='position:absolute;left:33%;top:2%;font-size:18px;'>No friends added</a>";
								}
								$left=2; $count=0;
								while($row = mysqli_fetch_array($result3))
								{
									if($count<3)
									{
										
										echo "<div style='cursor:pointer;position:absolute;left:".$left."%;width:30%;top:15%;height:10%;font-size:15px;text-align:center;'><a onclick=gotoprofile('". $row['user_name'] . "')>" . $row['first_name'] . " " . $row['last_name'] . "</a></div>";
										if($row['photo']!="0")
										{
											echo "<div style='cursor:pointer; position:absolute;left:".$left."%;top:25%;width:30%;height:60%;background-image: url(\"uploads/".$row['user_name']."/profilepic.".$row['photo']."\");background-size:cover;' onclick=gotoprofile('". $row['user_name'] . "')></div>";
										}
										else
										{
											echo "<div style='cursor:pointer; position:absolute;left:".$left."%;top:25%;width:30%;height:60%;' onclick=gotoprofile('". $row['user_name'] . "')><img src='img/face.png'></div>";
										}
										$left=$left+32;
										$count = $count+1;
									}
								}
							?>
						</div>
						<div id="activitydiv" style="background-color:#F0F0F0; position:absolute; top:105%; left:2%; width:96%; max-height:90%; border:solid lightgrey 1px; overflow-y:scroll;">
							<?php
								$result14 = mysqli_query($con,"SELECT activity FROM user_activity WHERE user_name ='" . $userwall . "'");
								echo "<p style='text-align:center;padding-top:5px;font-size:18px;'>" . ucfirst($userwall);
								if(mysqli_num_rows($result14)==0)
								{
									echo " has not liked any activities</p>";
								}
								else
								{
									echo "'s Favourite Activities</p>";
								}
								while($row14=mysqli_fetch_array($result14))
								{
									echo "	<p style='font-size:20px;text-align:center;cursor:pointer;'><a  onclick=gotoactivity('".$row14["activity"]."')>" . $row14["activity"] . "</p>
											<center><img src='img/".$row14["activity"].".jpg' style='width:60%;height:30%;cursor:pointer;'></a></center><br>";
								}
							?>
						</div>
					</div>
				</div>
				<div id="diarydiv" style="position:absolute; top:0%; left:38%; width:50%; height:100%; overflow-y:scroll; background-color:white;  border-left:solid grey 1px; border-right:solid grey 1px; border-top:solid grey 1px;">
					<?php
						if(strcmp($userwall,$username)==0)
						{
							echo '
								<div style="position:absolute;width:100%; height:30%;">
									<!-- Diary Entry -->
									<form id="addentry" action="adddiaryentry.php" method="post" enctype="multipart/form-data">
										<input style="visibility:hidden" type="file" name="file2" id="file2">
										<a id="addphoto">Add Photo/Video</a>
										<a id="addlocationbutton">Add Location</a>
										<input id="addtitle" name="addtitle" type="text" placeholder="Add Title">
										<input id="addactivity" name="addactivity" type="text" placeholder="Add Activity Name">
										<textarea id="addtext" name="addtext" placeholder="
																							What\'s a wild thing you did today?" style="overflow-x:hidden;"></textarea>
										<input type="text" name="address" id="address" placeholder="Where were you?">
										<select name="permission" id="permission">
											<option value="3">Public</option>
											<option value="0">Private</option>
											<option value="1">Friends</option>
											<option value="2">Mutual Friends</option>
										</select>
										<p id="pencil">&#33;</p>
										<input id="closelocation" type="button" value="X">
										<input id="postentry" type="button" class="btn btn-primary" value="Post to Diary">
										<p id="shared">Shared</p><p id="tick">&#252;</p>
										<p id="privacytext">Visible to</p>
									</form>
								</div>';
						}
						
						if(strcmp($userwall,$username)==0)
							echo '	<div style="position:absolute;top:33%;width:100%;height:67%;">';
						else
							echo '	<div style="position:absolute;top:0%;width:100%;height:100%;"><br>';
						
						echo "<p style='text-align:center;font-size:25px;font-family:arial'>" . ucfirst($userwall) . "'s Diary</p><br>";

											$result4 = mysqli_query($con,"SELECT * FROM user_post WHERE user_profile='" . $userwall . "' AND user_name='" . $userwall . "' AND permission>='" . $mapping . "' ORDER BY time_stamp DESC");
											while($row1 = mysqli_fetch_array($result4))
											{
												echo "<center><div style='width:90%;border:solid lightgrey 1px;background-color:#F0F0F0'><p style='text-align:center;padding-left:20px;padding-top:10px;font-size:20px;'><b>" . $row1['activity_title'] . "</b><br>" . $row1['activity_name'] . "</p><p style='text-align:center;padding-left:20px;'>" . $row1['activity_description'] . "</p>";
												if($row1['multimedia']!=Null)
												{
													echo "<center><img style='width:95%;height:95%;' src=\"uploads/" . $userwall . "/" . $row1['multimedia'] . "\"></center><br>";
												}
												echo "<a id='".$row1['post_id']."likebutton'></a>";
												echo "&nbsp;&nbsp;&nbsp;<a id='".$row1['post_id']."countlikes' style='font-size:15px;cursor:pointer;' onclick=getlikes('".$row1['post_id']."') onmouseout=hidelikes('".$row1['post_id']."')></a>
														<div id='".$row1['post_id']."likes' style='visibility:hidden;position:fixed;z-index:10;background-color:lightgrey;width:200px;' onmouseover=document.getElementById('".$row1['post_id']."likes').style.visibility='visible'; onmouseout=hidelikes('".$row1['post_id']."')></div>";
												echo "<br><br>
																<textarea placeholder='
																						Write what you think about this..' style='text-align:center;width:60%;height:65px;overflow-x:hidden;' name='comment' id='".$row1['post_id']."comment'></textarea>
																<input type='button' class='btn btn-primary' style='position:relative;left:20px;top:-30px;' value='Post' onclick=commentfunc('".$row1['post_id']."')><br>";
												echo "<span id='".$row1['post_id']."comments'></span>";
												echo "<br></div></center><br><br>";				
												echo "	<script>hasuserliked(".$row1['post_id'].")</script>
														<script>countlikes(".$row1['post_id'].")</script>
														<script>getcomments(".$row1['post_id'].")</script>";
											}
					?>
									</div>
					
				</div>
				<div style="background-color:white;position:absolute;top:100%;left:38%;width:50%;height:4%;border-bottom:solid grey 1px; border-left:solid grey 1px; border-right:solid grey 1px;">
					
				</div>
				
				<div id="walldiv" style="background-color:white;position:absolute;top:110%;left:38%;width:50%; height:100%; overflow-x:hidden; overflow-y:scroll; border-left:solid grey 1px; border-right:solid grey 1px; border-top:solid grey 1px;">
					<br>
					<?php
						if(!strcmp($userwall,$username)==0)
						{
							echo "	<div style='position:absolute;left:0%;width:100%;text-align:center;'>
										<p style='text-align:center;font-family:arial;font-size:25px;'>".ucfirst($userwall)."'s Wall</p><br>
										<textarea style='width:70%;height:65px;text-align:center;' placeholder='
																												Leave Greetings or Ask Recommendations' id='wallposttext'></textarea><br>
										<input type='button' class='btn btn-primary' value='Post' style='position:absolute;right:5%;bottom:10%;' onclick=addwallpost('".$userwall."')>
									</div><br><br><br><br><br><br><br><br><br>";
						}
						else
						{
							echo "<p style='text-align:center;font-family:arial;font-size:25px;'>".ucfirst($userwall)."'s Wall</p><br>";
						}
						$result5 = mysqli_query($con,"SELECT * FROM user_post JOIN user ON user_post.user_name = user.user_name WHERE user_profile='" . $userwall . "' AND user_post.user_name!='". $userwall ."'");
						if(mysqli_num_rows($result5)==0)
						{
							echo "<p style='text-align:center;font-size:25px;'>No wall posts</p>";
						}
						while($row5 = mysqli_fetch_array($result5))
						{
							echo "<center><div style='width:90%;border:solid lightgrey 1px;background-color:#F0F0F0'><p style='text-align:center;padding-left:20px;padding-top:10px;font-size:20px;'><a style='cursor:pointer;' onclick=gotoprofile('".$row5['user_name']."')>".ucfirst($row5['first_name'])."</a> says<br><b>" . $row5['activity_title'] . "</b><br>" . $row5['activity_name'] . "</p><p style='text-align:center;padding-left:20px;'>" . $row5['activity_description'] . "</p>";
							if($row5['multimedia']!=Null)
							{
								echo "<center><img style='width:95%;height:95%;' src=\"uploads/" . $userwall . "/" . $row5['multimedia'] . "\"></center><br>";
							}
							echo "	<a id='".$row5['post_id']."likebutton'></a>";
							echo "	&nbsp;&nbsp;&nbsp;<a id='".$row5['post_id']."countlikes' style='font-size:15px;cursor:pointer;' onclick=getlikes('".$row5['post_id']."') onmouseout=hidelikes('".$row5['post_id']."')></a>
									<div id='".$row5['post_id']."likes' style='visibility:hidden;position:fixed;z-index:10;background-color:lightgrey;width:200px;' onmouseover=document.getElementById('".$row5['post_id']."likes').style.visibility='visible'; onmouseout=hidelikes('".$row5['post_id']."')></div>";
							echo "	<br><br>
									<textarea placeholder='
															Write what you think about this..' style='text-align:center;width:60%;height:65px;overflow-x:hidden;' name='comment' id='".$row5['post_id']."comment'></textarea>
									<input type='button' class='btn btn-primary' style='position:relative;left:20px;top:-30px;' value='Post' onclick=commentfunc('".$row5['post_id']."')>";
							echo "<span id='".$row5['post_id']."comments'></span>";
							echo "<br></div></center><br><br>";				
							echo "	<script>hasuserliked(".$row5['post_id'].")</script>
									<script>countlikes(".$row5['post_id'].")</script>
									<script>getcomments(".$row5['post_id'].")</script>";
						}
					?>
				</div>
				<div style="background-color:white;position:absolute;top:210%;left:38%;width:50%;height:4%; border-left:solid grey 1px; border-right:solid grey 1px; border-bottom:solid grey 1px;">
					
				</div>
			</div>
		<?php
			if($_SESSION['shared']==1)
			{
				echo "<script>document.getElementById('shared').style.visibility='visible';document.getElementById('tick').style.visibility='visible';</script>";
				$_SESSION['shared']=0;
			}
			else
			{
				echo "<script>document.getElementById('shared').style.visibility='hidden';document.getElementById('tick').style.visibility='hidden';</script>";
			}
		?>
		<!--Fixed Position DIVs -->
		<div id="allfriendsdiv" style="display:none; position:fixed;top:10%;left:20%;width:60%;height:90%;overflow-y:scroll;background-color:white;">
			<input type="button" id="closefriends" class="btn btn-default" value="X" style="color:grey;position:absolute;right:0%; top:0%;padding:2px;width:30px; height:30px;">
			<table>
			<tr>
			<?php
				$result10 = mysqli_query($con,"select user_name,photo,first_name,last_name from user where user_name in(Select `user_friend` from `user_friend` where `user_name` = '$userwall' UNION Select `user_name` from `user_friend` where`user_friend` = '$userwall') ORDER BY user_name");
				$column=0;
				$left=0;
				while($row = mysqli_fetch_array($result10))
				{
					if($column==5)
					{
						$column=0;
						$left=0;
						echo "</tr><tr>";
					}
					else
					{
						$column = $column + 1;
					}
					echo "<td style='position:absolute;left:".$left."%;width:20%;height:35%;'><div style='position:absolute;top:5%;left:20%;cursor:pointer;font-size:15px;text-align:center;'><a onclick='gotoprofile(\"". $row['user_name'] . "\")'>" . $row['first_name'] . " " . $row['last_name'] . "</div>";
					if($row['photo']!="0")
					{
						echo "<div style='cursor:pointer;position:absolute;left:5%;width:90%;top:15%;height:85%;background-image: url(\"uploads/".$row['user_name']."/profilepic.".$row['photo']."\");background-size:cover;' onclick='gotoprofile(\"". $row['user_name'] . "\")'></div></td>";
					}
					else
					{
						echo "<div style='cursor:pointer;position:absolute;left:5%;width:90%;top:15%;height:85%;background-color:lightgrey;' onclick='gotoprofile(\"". $row['user_name'] . "\")'><img src='img/face.png'></div></td>";
					}
					$left = $left + 20;
				}
			?>
			</tr>
			</table>
		</div>
		<form id="autoform" method="post" action="diary.php">
			<input type="hidden" id="passdata" name="userwall">
		</form>
	</body>
</html>