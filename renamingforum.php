<?php

   // IoFo
   // Renaming Forum Page
   
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
   
   $sub_ID = $_POST['sub_ID'];
   $title  = strip_tags($_POST['title']);
   
   $sql = "UPDATE subforum SET title='". $title ."' WHERE sub_ID=".$sub_ID;

   mysql_query($sql,$connection);
   
   header("Location: admin.php")

?>
