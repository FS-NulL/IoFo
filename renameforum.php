<?php

   // IoFo
   // RenameForum Page
   
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
   
   $sub_ID = $_GET['sub_ID'];
   
   // Check Exists

   $sql = "SELECT sub_ID,title FROM subforum WHERE sub_Id=".$sub_ID;
   
   $result = mysql_query($sql,$connection);
   
   $test  = mysql_result($result,0,"sub_ID");
   $title   = mysql_result($result,0,"title");

   if ($test != $sub_ID) die("ERROR FINDING FORUM");

?>

<form action="renamingforum.php" method="POST">

      <input type="hidden" value="<?php echo $sub_ID ?>" name="sub_ID">
      <input type="text"   value="<?php echo $title ?>" name="title">
      <input type="submit" value="Rename">

</form>
