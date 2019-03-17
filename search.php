<?php

   // IoFo
   // Search
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   $mode = $_GET['mode'];
   
   if ($mode == "author")
   {
   	$user_ID = $_GET['u'];
   	$sql="SELECT posts.thread_ID,posts.post_time,posts.contents,thread.title FROM posts,thread WHERE posts.thread_ID = thread.thread_ID AND user_ID=$user_ID ORDER BY post_time DESC";
   }
   else
   {

   }

   $result = mysql_query($sql,$connection);
   
   echo '<a href="index.php">Forum Index</a><br>';
   echo '<table width="80%" bgcolor="#606060" cellspacing="4">';

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$thread_ID = mysql_result($result,$i,'posts.thread_ID');
   	$title     = mysql_result($result,$i,'thread.title');
   	$time      = mysql_result($result,$i,'posts.post_time');
   	$contents  = mysql_result($result,$i,'posts.contents');

   	echo '<tr><td bgcolor="#909090"><b><a href="viewthread.php?t='.$thread_ID.'">'.$title.'</a>  </b>'. date("H:i:s d/m/Y",$time) .' </td></tr>';
   	echo '<tr><td bgcolor="#808080">'.$contents.'</td></tr>';
   }

   echo '</table>';

?>
