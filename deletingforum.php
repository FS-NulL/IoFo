<?php

   // IoFo
   // Deleting Forum Page
   
   session_start();

   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   // Check admin status
   
   $sql = "SELECT adminflag FROM user WHERE username='".$_SESSION['username']."'";
   $result = mysql_query($sql,$connection);
   $testflag = mysql_result($result,0,'adminflag');
   
   if ($testflag != true)
   {
      header("Location: index.php");
      die();
   }
   
   $option     = $_POST['options'];
   $confirm    = $_POST['confirm'];   // NULL / on
   $sub_ID     = $_POST['sub_ID'];
   
   $sql = "SELECT location FROM subforum WHERE sub_ID=".$sub_ID;
   $result = mysql_query($sql,$connection);
   $del_location = mysql_result($result,0,'location');

   // Jump back if not confirmed
   if ($confirm != 'on')
   {
   	header("Location: admin.php");
   	die("Not Confirmed");
   }
   
   if ($option == "kill")
   {
   	// kill subforum
   	// kill threads
   	// kill posts
   	// one looop
   	
   	$sql = "DELETE FROM subforum WHERE sub_ID=".$sub_ID;
   	mysql_query($sql,$connection);
   	
   	$sql = "SELECT * FROM thread WHERE sub_ID=".$sub_ID;
   	$result = mysql_query($sql,$connection);

   	for ($i=0;$i<mysql_num_rows($result);$i++)
   	{
   	    $thread_ID = mysql_result($result,$i,'thread_ID');
            $sql = "DELETE FROM posts WHERE thread_ID=".$thread_ID;
            mysql_query($sql,$connection);
   	}

   	$sql = "DELETE FROM thread WHERE sub_ID=".$sub_ID;
   	mysql_query($sql,$connection);

   }
   else
   {
      	// change threads with $sub_ID to $option

   	$sql = "UPDATE thread SET sub_ID=".$option." WHERE sub_ID=".$sub_ID;
   	mysql_query($sql,$connection);

   	$sql = "DELETE FROM subforum WHERE sub_ID=".$sub_ID;
   	mysql_query($sql,$connection);
   }
   
   
   // Re locate subforums
   
   $sql = "SELECT * FROM subforum WHERE location > ".$del_location;
   $result = mysql_query($sql,$connection);
   
   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$location = mysql_result($result,$i,'location');
   	$location--;
   	$sub_ID   = mysql_result($result,$i,'sub_ID');
   	
   	$sql = "UPDATE subforum SET location=".$location." WHERE sub_ID=".$sub_ID;
        mysql_query($sql,$connection);
   }
   
   header("Location: admin.php");


?>
