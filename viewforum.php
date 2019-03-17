<?php

   // IoFo
   // Viewforum.php
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   $sql = "SELECT adminflag FROM user WHERE username='".$_SESSION['username']."'";
   $result = mysql_query($sql,$connection);
   $adminflag = mysql_result($result,0,'adminflag');

   $sub_ID = $_GET['f'];

   if ($sub_ID === "") header("Location: index.php");

   // Check exists

   $sql    = "SELECT * FROM subforum WHERE sub_ID=".$sub_ID;
   $result = mysql_query($sql,$connection);

   $test   = mysql_result($result,0,'sub_ID');

   if (($test != $sub_ID) OR (!$test)) header("Location: index.php");
   
   $title = mysql_result($result,0,'title');

?>

<?php

   // Start
   //threads replies author lastpost

   echo '
        <a href="index.php">Index</a> >> <a href="viewforum.php?f='.$sub_ID.'">'.$title.'</a>
        <br><br>
   ';

   if ($_SESSION['loggedin'] == "true")
   {
   	echo 'Welcome '.$_SESSION['username'];
   	echo ' <a href="newthread.php?f='. $sub_ID .'">New Thread</a>';
   	echo ' <a href="logout.php">Logout</a>';
   }
   else
   {
      echo ' <a href="signup.php">Signup</a>';
      echo ' <a href="login.php" >Login</a>';
   }
   if ($adminflag==true)
   {
   	echo ' <a href="admin.php">Admin Pannel</a>';
   }
   echo '

   <table width="80%" bgcolor="#606060" cellspacing="4">
   <tr>
   <td width="66%" align="center" bgcolor="#9a9a9a" colspan="2">Thread</td>
   <td width="7%" align="center" bgcolor="#9a9a9a">Replies</td>
   <td width="7%" align="center" bgcolor="#9a9a9a">Author</td>
   <td width="20%" align="center" bgcolor="#9a9a9a">Last Post</td>
   </tr>
   ';

   // select distinct thread.thread_ID,thread.title from thread,posts WHERE thread.thread_ID = posts.thread_ID AND thread.sub_ID=X ORDER BY posts.post_time
   
   // SELECT user.username,posts.post_time FROM posts,user WHERE posts.user_ID = user.user_ID AND posts.thread_ID =X ORDER BY posts.post_time DESC;

   $sql = "SELECT DISTINCT thread.thread_ID,thread.title FROM thread WHERE thread.sub_ID=".$sub_ID." ORDER BY thread.lastpost DESC";
   $result = mysql_query($sql,$connection);

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$thread_ID = mysql_result($result,$i,'thread.thread_ID');
   	$title     = mysql_result($result,$i,'thread.title');
   	
   	$sql = "SELECT user.user_ID,user.username,posts.post_time FROM posts,user WHERE posts.user_ID = user.user_ID AND posts.thread_ID=".$thread_ID." ORDER BY posts.post_time DESC LIMIT 1";
   	//echo $sql;
   	$thrresult = mysql_query($sql,$connection);
   	$lastpostuser = mysql_result($thrresult,0,'user.username');
   	$lastpostuser_ID = mysql_result($thrresult,0,'user.user_ID');
   	$lastposttime = mysql_result($thrresult,0,'posts.post_time');
   	$lastposttime = date("H:i:s d/m/Y",$lastposttime);

   	$sql = "SELECT user.user_ID,user.username,posts.post_time FROM user,posts WHERE user.user_ID = posts.user_ID AND posts.thread_ID=".$thread_ID." ORDER BY posts.post_time ASC LIMIT 1";
   	$thrresult = mysql_query($sql,$connection);
   	$author = mysql_result($thrresult,0,'user.username');
   	$author_ID = mysql_result($thrresult,0,'user.user_ID');
   	
   	$sql = "SELECT count(*) FROM posts WHERE posts.thread_ID=".$thread_ID;
   	$thrresult = mysql_query($sql,$connection);
   	$replies = mysql_result($thrresult,0,'count(*)');
   	$replies--;



   	echo '

   	     <tr>
   	         <td width="1%" bgcolor="#808080"></td>
                 <td bgcolor="#808080"><a href="viewthread.php?t='.$thread_ID.'">'.$title.'</a></td>
                 <td bgcolor="#808080" align="center">'.$replies.'</td>
                 <td bgcolor="#808080" align="center"><a href="profile.php?u='.$author_ID.'">'.$author.'</a></td>
                 <td bgcolor="#808080" align="center"><a href="profile.php?u='.$lastpostuser_ID.'">'. $lastpostuser .'</a> '. $lastposttime .'</td>
             </tr>

           ';

   }
   if ($i==0) echo '<tr><td colspan="4" align="center">No Posts</td></tr>';


   echo '</table>';

?>
