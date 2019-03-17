<?php

   // IoFo
   // View Thread
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   $sql = "SELECT adminflag FROM user WHERE username='".$_SESSION['username']."'";
   $result = mysql_query($sql,$connection);
   $adminflag = mysql_result($result,0,'adminflag');

   $thread_ID = $_GET['t'];

   if ($thread_ID == "") header("Location: index.php");

   // Check exists

   //$sql = "SELECT title,thread_ID,sub_ID from thread WHERE thread_ID=".$thread_ID;
   $sql = "select thread.thread_ID,subforum.sub_ID,thread.title,subforum.title FROM subforum,thread WHERE subforum.sub_ID = thread.sub_ID AND thread.thread_ID = ".$thread_ID;

   $result = mysql_query($sql,$connection);

   $test   = mysql_result($result,0,'thread.thread_ID');
   $sub_ID = mysql_result($result,0,'subforum.sub_ID');

   if ($test != $thread_ID) header("Location: index.php");

   $subtitle = mysql_result($result,0,'subforum.title');
   $thrtitle = mysql_result($result,0,'thread.title');


?>

<?php

   echo '
        <a href="index.php">Index</a> >> <a href="viewforum.php?f='.$sub_ID.'">'.$subtitle.'</a> >> <a href="viewthread.php?t='.$thread_ID.'">'.$thrtitle.'</a>
        <br><br>
   ';

   if ($_SESSION['loggedin'] == "true")
   {
   	echo 'Welcome '.$_SESSION['username'];
   	echo '<a href="newthread.php?f='. $sub_ID .'">New Thread</a>
        <a href="postreply.php?t='. $thread_ID .'">Post Reply</a>   ';
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
   	echo ' <a href="deletethread.php?t='. $thread_ID .'">Delete Thread</a>';
   }

   echo '

        <table width="80%" bgcolor="#606060" cellspacing="4">
        <tr><td width="15%" align="center" bgcolor="#9a9a9a">Author</td><td width="85%" align="center" bgcolor="#9a9a9a">Post</td></tr>
   ';
   
   $sql = "SELECT user.email,user.user_ID,user.username,posts.contents,posts.post_time,posts.post_ID FROM posts,user WHERE posts.user_ID = user.user_ID AND thread_ID=".$thread_ID." ORDER BY posts.post_time ASC";
   $result = mysql_query($sql,$connection);

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$username = mysql_result($result,$i,'user.username');
   	$contents = strip_tags( nl2br( mysql_result($result,$i,'posts.contents') ) );
   	$posttime = date("H:i:s d/m/Y" , mysql_result($result,$i,'posts.post_time') );
   	$user_ID  = mysql_result($result,$i,'user.user_ID');
   	$email    = mysql_result($result,$i,'user.email');
   	$post_ID  = mysql_result($result,$i,'posts.post_ID');

   	echo '

             <tr>
             <td bgcolor="#808080" valign="top">
                 '.$username.'
                 <br>
                 '.
                 //<img src="viewicon.php?u='.$user_ID.'">
                 '
             </td>
             <td bgcolor="#808080" valign="top">
             
             <table width="100%">
                    <tr>
                        <td bgcolor="#808080">'.$posttime.'</td>
                        <td bgcolor="#808080" align="right">';
                        
                        if ($adminflag==true) if ($i!=0) echo '<a href="deletepost.php?p='. $post_ID .'">Delete</a> ';

                        if ($email) echo '<a href="mailto:'.$email.'">email</a>';

                        echo '  <a href="profile.php?u='.$user_ID.'">profile</a></td>

                    </tr>
                    <tr>
                        <td colspan="2" bgcolor="#909090">'.$contents.'</td>
                    </tr>
             </table>

             </td>
             </tr>

           ';

   }


   echo '</table>';
   
   if ($_SESSION['loggedin']=="true") echo '
   
        <a href="newthread.php?f='. $sub_ID .'">New Thread</a>
        <a href="postreply.php?t='. $thread_ID .'">Post Reply</a>

   ';

?>
