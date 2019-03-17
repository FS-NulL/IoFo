<?php

   // IoFo
   // Thread/Reply Posting
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   if ($_SESSION['loggedin'] != "true") header("Location: index.php");
   
   $type = $_POST['type'];

   if ($type == "thread")
   {
   	$sub_ID = $_POST['forum'];
   	$title  = $_POST['title'];
   	$text   = $_POST['message'];
   	$text   = strip_tags($text);

   	// Check Sub Exists
   	
   	$sql = "SELECT count(*) FROM subforum WHERE sub_ID=".$sub_ID;
        $result = mysql_query($sql,$connection);
        $count = mysql_result($result,0,'count(*)');
        if ($count != 1)
        {
           echo '<meta http-equiv="refresh" content="2;index.php">Forum Not Found';
   	   die();
        }
   	

        $sql = "SELECT thread_ID FROM thread ORDER BY thread_ID DESC LIMIT 1";
        $result = mysql_query($sql,$connection);

        $thread_ID = mysql_result($result,0,'thread_ID');
        if (!$thread_ID) $thread_ID = 1;
        else $thread_ID++;

        $posttime = time();

        $sql = "INSERT INTO thread VALUES ($thread_ID,$sub_ID,'$title',$posttime)";
        mysql_query($sql,$connection);

        $sql = "INSERT INTO posts (thread_ID,user_ID,post_time,contents) VALUES ($thread_ID,".$_SESSION['user_ID'].",$posttime,'".$text."')";
        mysql_query($sql,$connection);

        $newurl = "viewthread.php?t=$thread_ID";
        
        echo '
             <meta http-equiv="refresh" content="2;'. $newurl .'">
             <a href="'. $newurl .'">You Are Being Redirected To your Thread</a>
        ';

   }
   elseif ($type == "reply")
   {
        $thread_ID = $_POST['thread'];
        $text      = $_POST['message'];
        $text      = strip_tags($text);

   	// Check thread Exists
   	$sql = "SELECT count(*) FROM thread WHERE thread_ID=".$thread_ID;
        $result = mysql_query($sql,$connection);
        $count  = mysql_result($result,0,'count(*)');

        if ($count != 1)
        {
           echo '<meta http-equiv="refresh" content="2;index.php">Forum Not Found';
   	   die();
        }
        
        $posttime = time();

        $sql = "INSERT INTO posts (thread_ID,user_ID,post_time,contents) VALUE ($thread_ID,". $_SESSION['user_ID'] .",$posttime,'". $text ."')";
        mysql_query($sql,$connection);
        
        $sql = "UPDATE thread SET lastpost=$posttime WHERE thread_ID=$thread_ID";
        mysql_query($sql,$connection);

        $newurl = "viewthread.php?t=$thread_ID";

        echo '
             <meta http-equiv="refresh" content="2;'. $newurl .'">
             <a href="'. $newurl .'">You Are Being Redirected To your Post</a>
        ';

   }
   else header("Location: index.php");

?>
