<?php

   // IoFo
   // Index Page
   
   session_start();

   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   $sql = "SELECT adminflag FROM user WHERE username='".$_SESSION['username']."'";
   $result = mysql_query($sql,$connection);
   $adminflag = mysql_result($result,0,'adminflag');

?>

<?

   $sql = "SELECT * FROM sections ORDER BY location ASC";
   $result = mysql_query($sql,$connection);

   if ($_SESSION['loggedin'] == "true")
   {
   	echo 'Welcome '.$_SESSION['username'];
   	echo '<a href="logout.php">Logout</a>';
   }
   else
   {
      echo ' <a href="signup.php">Signup</a>';
      echo ' <a href="login.php" >Login</a>';
   }
   if ($adminflag==true)
   {
   	echo ' <a href="admin.php">Admin Pannel</a> ';
   }

   echo '<table width="80%" border="0" bgcolor="#606060" cellspacing="4">
         <tr>
         <td colspan="2" width="75%" align="center" bgcolor="#808080"><b>Forum</b></td>
         <td width="2%"  align="center" bgcolor="#808080"><b>Threads</b></td>
         <td width="2%"  align="center" bgcolor="#808080"><b>Posts</b></td>
         <td width="19%" align="center" bgcolor="#808080"><b>Last Post</b></td>
         </tr>

   ';

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$section_ID = mysql_result($result,$i,'section_ID');
   	$title      = mysql_result($result,$i,'title');

   	echo '

   	<tr><td bgcolor="9a9a9a" colspan="5" width="100%"><b><font color="#ffffff" size="+0">
   	'. $title .'
   	</font></b></td></tr>

   	';

        $sql = "SELECT * FROM subforum WHERE section_ID=".$section_ID." ORDER BY location ASC";
        $subresult = mysql_query($sql,$connection);

        //echo "test ".mysql_num_rows($subresult)." test";

        for ($j=0;$j<mysql_num_rows($subresult);$j++)
        {
            $sub_ID = mysql_result($subresult,$j,'sub_ID');
            $title  = mysql_result($subresult,$j,'title' );

            // Get thread count

            $sql = "SELECT count(*) FROM thread WHERE sub_ID=".$sub_ID;
            $thrresult = mysql_query($sql,$connection);
            $thrcount  = mysql_result($thrresult,0,'count(*)');

            $sql = "select count(*) FROM posts,thread,subforum WHERE posts.thread_ID = thread.thread_ID AND thread.sub_ID = subforum.sub_ID and subforum.sub_ID=".$sub_ID;
            $postresult = mysql_query($sql,$connection);
            $postcount = mysql_result($postresult,0,'count(*)');

            $sql = "select user.user_ID,user.username,posts.post_time FROM posts,thread,subforum,user WHERE posts.user_ID = user.user_ID AND posts.thread_ID = thread.thread_ID AND thread.sub_ID=".$sub_ID." ORDER BY posts.post_time DESC LIMIT 1";
            $lastresult = mysql_query($sql,$connection);
            $lastpostuser =   mysql_result($lastresult,0,'user.username');
            $lastposttime =   mysql_result($lastresult,0,'posts.post_time');
            $lastpostshowtime = date("H:i:s d/m/Y",$lastposttime);
            $lastpostuser_ID  = mysql_result($lastresult,0,'user.user_ID');

            echo '<tr>
            <td width="3%"></td>
            <td width="72%" bgcolor="#808080"><a href=viewforum.php?f='. $sub_ID .'>'.$title   .'</a></td>
            <td width="2%"  align="center" bgcolor="#808080">'.$thrcount.'</td>
            <td width="2%"  align="center" bgcolor="#808080">'.$postcount.'</td>
            ';

            if ($postcount == 0)
            echo '<td width="21%" align="center" bgcolor="#808080">No Posts</td>';
            else
            echo '<td width="21%" align="center" bgcolor="#808080"><a href="profile.php?u='. $lastpostuser_ID .'">'. $lastpostuser .'</a> @ '. $lastpostshowtime .'</td>';

            echo'</tr>';

        }

   }
   
   echo '</table>';
   
?>
