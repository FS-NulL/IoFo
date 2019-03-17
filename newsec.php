<?php
   
   // IoFo
   // New Section Page (admin)
   
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

   if ($_GET['location'] === "") die("NoLocation");
   if ($_GET['title']    === "") die("NoTitle");
   
   $location = strip_tags( $_GET['location'] );
   $title    = strip_tags( $_GET['title']    );

   $sql = "INSERT INTO sections (location,title) VALUES ( ".$location." , '".$title."' )";
   mysql_query( $sql , $connection ) or die(mysql_error());
   header("Location: admin.php");


?>
