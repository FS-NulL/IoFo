<?php

   // IoFo
   // New Subforum Page
   
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
   
   if ($_GET['location']     === "") die("NoLocation");
   if ($_GET['title']        === "") die("NoTitle");     //User Error
   if ($_GET['section_ID']   === "") die("NoSection");

   $location   = strip_tags( $_GET['location']   );
   $title      = strip_tags( $_GET['title']      );
   $section_ID = strip_tags( $_GET['section_ID'] );
   
   $sql = "INSERT INTO subforum (section_ID,location,title) VALUES (". $section_ID .",". $location .",'". $title ."')";
   mysql_query( $sql , $connection ) or die(mysql_error());
   header("Location: admin.php");

?>
