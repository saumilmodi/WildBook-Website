<html>
	<head>
		<title>WildBook</title>
		<meta name="viewport" content-"width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<style>
			#nextbackground,#prevbackground
			{
			color:black; background-image:none;
			}
			#prevbtn,#nextbtn
			{
			border-radius:100%; width:60px; height:60px; color:blue; background-color:white; border-width:10px;
			}
			#prevbtn
			{
			position:absolute; top:50%; left:20%;
			}
			#nextbtn
			{
			position:absolute; top:50%; right:2%;
			}
			
		</style>
		<script>
			$(document).ready(function(){
				$('#myCarousel').carousel({
					interval: 3000
				})
				$(document).mousemove(function(event){ 
					mousex = event.pageX;
					mousey = event.pageY;
				});
			});
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
			function like(postid)
			{
				$.post("gotopost.php",
				{
					postid:postid
				},
				function(data,status)
				{
					
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
			
			function gotoactivity(name)
			{
				document.getElementById("autoform").action="activity.php";
				document.getElementById("passdata").name="activityname";
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
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
				}
				);
			}
			function getboxdata()
			{
				$.post("boxdata.php",
				{
				},
				function(data,status)
				{
					data = data.split("&");
					if(data[0]==0)
						document.getElementById("box").innerHTML="";
					else if(data[0]==1)
						document.getElementById("box").innerHTML="<p style='padding-left:50px; font-size:20px;'><a style='cursor:pointer; font-size:40px;position:relative;top:6px;' onclick=friendrequests()>"+data[0]+"</a>&nbsp;&nbsp; friend request</p>";
					else if(data[0]>1)
						document.getElementById("box").innerHTML="<p style='padding-left:50px;font-size:20px;'><a style='cursor:pointer; font-size:40px;position:relative;top:6px;' onclick=friendrequests()>"+data[0]+"</a>&nbsp;&nbsp; friend requests</p>";
					temp = document.getElementById("box").innerHTML;
					if(data[1]==0)
						document.getElementById("box").innerHTML = temp;
					if(data[1]==1)
						document.getElementById("box").innerHTML = temp + "<p style='padding-left:50px;font-size:20px;'><a style='cursor:pointer; font-size:40px;position:relative;top:6px;' onclick=notifications()>"+data[1]+"</a>&nbsp;&nbsp;notification</p>";
					else if(data[1]>1)
						document.getElementById("box").innerHTML = temp + "<p style='padding-left:50px;font-size:20px;'><a style='cursor:pointer; font-size:40px;position:relative;top:6px;' onclick=notifications()>"+data[1]+"</a>&nbsp;&nbsp;notifications</p>";
				}
				);
			}
			function friendrequests()
			{
				$.post("friendrequests.php",
				{
				},
				function(data,status)
				{
					temp1 = JSON.parse(data);
					if(temp1)
					{
						for(var i=0;i<temp1.length;i++)
						{
							if(i==0)
								temp2 = "<br>";
							else
								temp2 = "<br>" + document.getElementById("box1").innerHTML;
							if(temp1[i]['photo']!="0")
								document.getElementById("box1").innerHTML=temp2+"&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]['user_name']+"')><img width='75' heigh'75' src='uploads/"+temp1[i]['user_name']+"/profilepic."+temp1[i]['photo']+"'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer' onclick=gotoprofile('"+temp1[i]['user_name']+"')>"+temp1[i]['first_name']+" "+temp1[i]['last_name']+"</a><br><br>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' id='acceptfriend' onclick=acceptfriend('"+temp1[i]['user_name']+"') class='btn btn-success' style='text-align:center;' value='Accept'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' id='denyfriend' onclick=denyfriend('"+temp1[i]['user_name']+"') class='btn btn-danger' style='text-align:center;' value='Deny'><br><br>";
							else
								document.getElementById("box1").innerHTML=temp2+"&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]['user_name']+"')><img width='75' heigh'75' src='img/face.png'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer' onclick=gotoprofile('"+temp1[i]['user_name']+"')>"+temp1[i]['first_name']+" "+temp1[i]['last_name']+"</a><br><br>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' id='acceptfriend' onclick=acceptfriend('"+temp1[i]['user_name']+"') class='btn btn-success' style='text-align:center;' value='Accept'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' id='denyfriend' onclick=denyfriend('"+temp1[i]['user_name']+"') class='btn btn-danger' style='text-align:center;' value='Deny'><br><br>";
						}
					}
					else
					{
						document.getElementById("box1").innerHTML="";
					}
					document.getElementById("box1").style.visibility="visible";
					if(document.getElementById("box2").style.visibility=="visible")
						document.getElementById("box1").style.top="73.5%";
					else
						document.getElementById("box1").style.top="47%";
				}
				);
			}
			function notifications()
			{
				$.post("notifications.php",
				{
				},
				function(data,status)
				{
					temp1 = JSON.parse(data);
					if(temp1)
					{
						temp2 = "<br>";
						document.getElementById("box2").innerHTML="";
						for(var i=0;i<temp1.length;i++)
						{
							if(i==0)
								temp2 = "<br>" + document.getElementById("box2").innerHTML;
							else
								temp2 = document.getElementById("box2").innerHTML;
							if(temp1[i]["flag"]==1)
							{
								if(temp1[i]["likes"]==1)
									document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> liked your<a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
								else
								{
									count = temp1[i]["likes"]-1;
									if(count==1)
										document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> and "+count+" one other person liked your <a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
									else
										document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> and "+count+" others liked your <a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
								}
							}
							else if(temp1[i]["flag"]==2)
							{
								if(temp1[i]["comments"]==1)
									document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> commented on your<a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
								else
								{
									count = temp1[i]["comments"]-1;
									if(count==1)
										document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> and "+count+" one other person commented on your <a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
									else
										document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> and "+count+" others commented on your <a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')> post</a></p>";
								}
							}
							else if(temp1[i]["flag"]==3)
							{
								document.getElementById("box2").innerHTML = temp2 + "<p style='padding-left:15px;'><a style='cursor:pointer;' onclick=gotoprofile('"+temp1[i]["username"]+"')>"+temp1[i]["firstname"]+"</a> left a <a style='cursor:pointer;' onclick=gotopost('"+temp1[i]["postid"]+"')>message</a> on your wall</p>";
							}
						}
					}
					document.getElementById("box2").style.visibility="visible";
					if(document.getElementById("box1").style.visibility=="visible")
						document.getElementById("box2").style.top="73.5%";
					else
						document.getElementById("box2").style.top="47%";
				}
				);
			}
			function gotoprofile(name)
			{
				document.getElementById("passdata").value=name;
				document.getElementById("autoform").submit();
			}
			function acceptfriend(name)
			{
				var flag="yes";
				$.post("acceptfriend.php",
				{
					userfriend:name,
					flag:flag
				},
				function(data,status)
				{
					getboxdata();
					friendrequests();
				}
				);
			}
			function denyfriend(name)
			{
				var flag="no";
				$.post("acceptfriend.php",
				{
					userfriend:name,
					flag:flag
				},
				function(data,status)
				{
					getboxdata();
					friendrequests();
				}
				);
			}
			setInterval(function(){
				getboxdata();
				if(document.getElementById("box1").style.visibility=="visible")
					friendrequests();
				if(document.getElementById("box2").style.visibility=="visible")
					notifications();
			},1000);
		</script>
	</head>
	<body>
			<?php
				error_reporting(0);
				include("navbar.php");
				include("initdb.php");
				session_start();
				if(is_null($_SESSION['username']))
				{
					header('Location:index.html');
				}
				else
				{
					$username=$_SESSION['username'];
					#mysqli_query($con,"UPDATE user SET last_login = now() WHERE user_name='$username'");
				}
			?>
			<div style="position:absolute; bottom:0%; width:100%; height:90%; overflow:hidden;">
				<div style="position:absolute; bottom:0%; left:0%; width:20%; height:100%;">
					<!-- For displaying mini profile -->
					<?php
						$result2 = mysqli_query($con,"SELECT * FROM user WHERE user_name='".$username."'");
						while($row2=mysqli_fetch_array($result2))
						{
							if(strcmp($row2['photo'],"0")!=0)
								echo "<a style='cursor:pointer;' href=diary.php><img style='position:absolute;top:10px;left:10px;height:100px;width:100px;' src='uploads/".$username."/profilepic.".$row2['photo']."'>";
							else
								echo "<a style='cursor:pointer;' href=diary.php><div style='position:absolute;top:10px;left:10px;height:100px;width:100px;background-color:#F0F0F0'><img height='100' width='100' src='img/face.png'></div>";
							echo "<p style='font-size:20px; position:absolute;left:125px;top:50px;'>" . $row2['first_name']." ".$row2['last_name']."</p></a>";
						}
					?>
					<div id="box" style="position:absolute; top:25%; left:0%; width:100%; max-height:22%; border-top:solid lightgrey 1px; border-bottom:solid lightgrey 1px;">
						
					</div>
					<div id="box1" style="visibility:hidden; position:absolute; top:60%; left:0%; width:99%; height:26.5%; border-top:solid lightgrey 1px; border-bottom:solid lightgrey 1px; overflow-y:scroll;">
						
					</div>
					<div id="box2" style="visibility:hidden; position:absolute; top:80%; left:0%; width:99%; height:26.5%; border-top:solid lightgrey 1px; border-bottom:solid lightgrey 1px; overflow-y:scroll;">
						
					</div>
					<script>getboxdata()</script>
				</div>
				
				<div id="newsfeeddiv" style="position:absolute; bottom:0%; left:20%; width:50%; height:100%; overflow-x:hidden; overflow-y:scroll; border-left:solid lightgrey 1px; border-right:solid lightgrey 1px;">
					<!-- For displaying news feed -->
					<?php
						$array = array();
						//$result0 = mysqli_query($con,"select `user_friend` from `user_friend` where `user_name` = '$username' or `user_friend` = '$username'");
						$result0 = mysqli_query($con,"select `user_friend` from `user_friend` where `user_name` = '$username'");
						$result0_1 = mysqli_query($con,"select `user_name` from `user_friend` where `user_friend` = '$username'");
						if (mysqli_num_rows($result0) > 0) {
							
							while ($query_row = mysqli_fetch_assoc($result0)){
								//echo $query_row['user_friend'].'<br>'; 
								array_push($array, $query_row['user_friend']);
							}
						}
						if (mysqli_num_rows($result0_1) > 0) {
							
							while ($query_row = mysqli_fetch_assoc($result0_1)){
								//echo $query_row['user_friend'].'<br>'; 
								array_push($array, $query_row['user_name']);
							}
						}
						
						if (sizeof($array) > 0) {
							$result = mysqli_query($con,"select * from `user_post` join `user` on `user`.`user_name` = `user_post`.`user_profile` where `user_profile` IN ('".implode("','",$array)."') and `user_post`.`permission` >= 1 order by `time_stamp` DESC");
							if (mysqli_num_rows($result) > 0) {
								
								while($row1 = mysqli_fetch_array($result))
									{
										
										echo "<center><div style='width:95%;border:solid lightgrey 1px;background-color:#F0F0F0'>";
										
										if($row1['photo']!= '0')
										{
											//echo "<center><div style='width:95%;background-color:#F0F0F0'><p style='text-align:left;padding-left:20px;padding-top:10px;font-size:20px;'><img style='width:15%;height:15%;' src=\"uploads/" . $row1['user_profile'] . "/profilepic." . $row1['photo'] . "\"><a onclick=gotoprofile('". $row1['user_name'] . "')><b>" . $row1['first_name'] . "</b></a></p><p style='text-align:left;padding-left:20px;font-size:10px;'>";
											echo "<center><div style='width:95%;background-color:#F0F0F0'><p style='text-align:left;padding-left:20px;padding-top:10px;font-size:20px;'><a onclick=gotoprofile('". $row1['user_name'] . "') style='cursor: pointer;'><img style='width:15%;height:15%;' src=\"uploads/" . $row1['user_profile'] . "/profilepic." . $row1['photo'] . "\"></a>&nbsp;&nbsp;<a onclick=gotoprofile('". $row1['user_name'] . "') style='cursor: pointer;'><b>" . $row1['first_name'] . "</b></a></p><p style='text-align:left;padding-left:20px;font-size:13px;'>";
											
											$tm = round(((strtotime("now") - strtotime($row1['time_stamp']))/60)/60);
											
											if ($tm < 10){
												echo "<b> Posted </b>".$tm . " hours ago </p></div></center>";
											} else {
												echo "<b> Posted on </b>".$row1['time_stamp'] . "</p></div></center>";
											}
											
										}
										else {
											//echo "<center><div style='width:95%;background-color:#F0F0F0'><p style='text-align:left;padding-left:20px;padding-top:10px;font-size:20px;'><img style='width:15%;height:15%;' src=\"img/face.png\"><b>" . $row1['first_name'] . "</b></p><p style='text-align:left;padding-left:20px;font-size:10px;'><b> Posted at: </b>" . $row1['time_stamp'] . "</p></div></center>";
											echo "<center><div style='width:95%;background-color:#F0F0F0'><p style='text-align:left;padding-left:20px;padding-top:10px;font-size:20px;'><a onclick=gotoprofile('". $row1['user_name'] . "') style='cursor: pointer;'><img style='width:15%;height:15%;' src=\"img/face.png\"></a><a onclick=gotoprofile('". $row1['user_name'] . "') style='cursor: pointer;'><b>" . $row1['first_name'] . "</b></a></p><p style='text-align:left;padding-left:20px;font-size:10px;'><b> Posted at: </b>";
											echo $row1['time_stamp'] . "</p></div></center>";
										}
						
										echo "<center><div style='width:95%;background-color:#F0F0F0'><p style='text-align:center;padding-left:20px;padding-top:10px;font-size:20px;'><b>" . $row1['activity_title'] . "</b><br>" . $row1['activity_name'] . "</p><p style='text-align:center;padding-left:20px;'>" . $row1['activity_description'] . "</p>";
										if($row1['multimedia']!=Null)
										{
											echo "<center><img style='width:95%;height:95%;' src=\"uploads/" . $row1['user_profile'] . "/" . $row1['multimedia'] . "\"></center><br>";
										}
										echo "<a id='".$row1['post_id']."likebutton'></a>";
										echo "&nbsp;&nbsp;&nbsp;<a id='".$row1['post_id']."countlikes' style='font-size:15px;cursor:pointer;' onclick=getlikes('".$row1['post_id']."') onmouseout=hidelikes('".$row1['post_id']."')></a>
											<div id='".$row1['post_id']."likes' style='visibility:hidden;position:fixed;z-index:10;background-color:lightgrey;width:200px;' onmouseover=document.getElementById('".$row1['post_id']."likes').style.visibility='visible'; onmouseout=hidelikes('".$row1['post_id']."')></div>";
											echo "<br><br><textarea placeholder='
																					Write what you think about this..' style='text-align:center;width:60%;height:65px;' name='comment' id='".$row1['post_id']."comment'></textarea>
												<input type='button' class='btn btn-primary' style='position:relative;left:20px;top:-30px;' value='Post' onclick=commentfunc('".$row1['post_id']."')>";
											echo "<span id='".$row1['post_id']."comments'></span>";
											//echo "<br></div></center><br><br>";				
											echo "<br><br></div></center></div></center><br><br>";				
											echo "	<script>hasuserliked(".$row1['post_id'].")</script>
												<script>countlikes(".$row1['post_id'].")</script>
												<script>getcomments(".$row1['post_id'].")</script>";
									}
							}
						}
						
					?>
				</div>
				
				<div style="position:absolute; bottom:0%; left:70%; width:30%; height:100%;">
					<!-- For displaying suggested activities or trending or friend requests or notifications etc etc -->
						<?php
						echo "<div style='position:absolute; top:0%; width:100%; height:35%; background-color:#F0F0F0;border:solid lightgrey 1px;'></div>";
							$result1 = mysqli_query($con,"SELECT activity_name FROM activity WHERE activity_name NOT IN (SELECT activity FROM user_activity WHERE user_name ='".$username."') LIMIT 30");
								if(mysqli_num_rows($result1)>0)
								{
									echo "	<div style='position:absolute; top:60%; width:100%; height:40%; background-color:#F0F0F0;border:solid lightgrey 1px;'>
											<div id='myCarousel' class='carousel slide' style='position:absolute;left:0%;top:0%;width:100%;height:100%;'>
												<p style='text-align:center; padding-top:5px;font-size:18px;'>Suggested Activities</p>
												<div class='carousel-inner' style='position:absolute;left:0%;top:0%;width:100%;height:100%;'>";
									$count = 0;
									while($row1=mysqli_fetch_array($result1))
									{
										if($count==0)
										{
											echo "	<div class='item active' style='position:absolute;width:100%;height:100%;'><br><br><p style='font-size:20px;cursor:pointer;text-align:center;'><a  onclick=gotoactivity('".$row1["activity_name"]."')>" . $row1["activity_name"] . "</p>
													<center><img src='img/".$row1["activity_name"].".jpg' style='width:60%;height:70%;cursor:pointer;'></center></a></div>";
											$count = $count+1;
										}
										else
										{
											echo "	<div class='item' style='position:absolute;width:100%;height:100%;'><br><br><p style='font-size:20px;cursor:pointer;text-align:center;'><a  onclick=gotoactivity('".$row1["activity_name"]."')>" . $row1["activity_name"] . "</p>
													<center><img src='img/".$row1["activity_name"].".jpg' style='width:60%;height:70%;cursor:pointer;'></center></a></div>";
										}
									}
										echo "		</div>
												<a id='prevbackground' class='carousel-control left' href='#myCarousel' data-slide='prev'>
													<button id='prevbtn' class='icon-prev'></button>
												</a>
												<a id='nextbackground' class='carousel-control right' href='#myCarousel' data-slide='next'>
													<button id='nextbtn' class='icon-next'></button>
												</a>
												
											</div>
											</div>";
								}
						?>
				</div>
			</div>
		<form id="autoform" method="post" action="diary.php">
			<input type="hidden" id="passdata" name="userwall">
		</form>
		<script src="js/bootstrap.js"></script>
	</body>
</html>