		<?php
		
			error_reporting(0);
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
			$likeQuery = "SELECT up.post_id,first_name,up.user_profile,up.user_name,up.activity_title ,up.activity_name ,up.activity_description, l.location_id,l.location_name,l.longitude,l.latitude ,up.permission ,multimedia ,up.time_stamp,'like',count(*) as total_like
			FROM user_post as up inner join user_like as ul on up.post_id=ul.post_id
			left outer join location as l on up.location_id=l.location_id join user on up.user_name = user.user_name
			where up.user_profile='$username' and ul.time_stamp > (SELECT last_login FROM user where user_name='$username')
			group by up.post_id order by ul.time_stamp";
			$commentQuery = "SELECT up.post_id,first_name,up.user_profile,up.user_name,up.activity_title ,up.activity_name ,up.activity_description, l.location_id,l.location_name,l.longitude,l.latitude ,up.permission ,multimedia ,up.time_stamp,'comment',count(*) as total_comment
			FROM user_post as up inner join user_comment as uc on up.post_id=uc.post_id
			left outer join location as l on up.location_id=l.location_id join user on up.user_name = user.user_name
			where up.user_profile='$username' and uc.time_stamp > (SELECT last_login FROM user where user_name='$username')
			group by up.post_id order by uc.time_stamp";
    
			$postQuery = "SELECT up.post_id,first_name,up.user_profile,up.user_name,up.activity_title ,up.activity_name ,up.activity_description, l.location_id,l.location_name,l.longitude,l.latitude ,up.permission ,multimedia ,up.time_stamp,'post','total_post'
			FROM user_post as up left outer join location as l on up.location_id=l.location_id join user on up.user_name = user.user_name
			where up.user_profile='$username' and 
			up.time_stamp > (SELECT last_login FROM user where user_name='$username')
			order by up.time_stamp";
			
			$result1 = mysqli_query($con,$likeQuery);
			while($row1=mysqli_fetch_array($result1))
			{
				$json[] = array(	'postid' => $row1['post_id'],
									'likes' => $row1['total_like'],
									'username' => $row1['user_name'],
									'firstname' => $row1['first_name'],
									'flag' => 1);
			}
			$result2 = mysqli_query($con,$commentQuery);
			while($row2=mysqli_fetch_array($result2))
			{
				$json[] = array(	'postid' => $row2['post_id'],
									'comments' => $row2['total_comment'],
									'username' => $row2['user_name'],
									'firstname' => $row2['first_name'],
									'flag' => 2);
			}
			$result3 = mysqli_query($con,$postQuery);
			while($row3=mysqli_fetch_array($result3))
			{
				$json[] = array(	'postid' => $row3['post_id'],
									'username' => $row3['user_name'],
									'firstname' => $row3['first_name'],
									'flag' => 3);
			}
			$jsonstring = json_encode($json);
			echo $jsonstring;
		?>