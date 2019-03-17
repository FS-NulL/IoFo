<?php

   // IoFo
   // Rename Section
   
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

   $section_ID = $_GET['section_ID'];
   
   // check exists
   
   $sql = "SELECT section_ID,title FROM sections WHERE section_ID =".$section_ID;

   $result = mysql_query($sql,$connection);

   $test   = mysql_result($result,0,"section_ID");
   $title  = mysql_result($result,0,"title");

   if ($test != $section_ID) die ("ERROR FINDING SECTION");

?>

<form action="renamingsection.php" method="POST">

      <input type="hidden" value="<?php echo $section_ID ?>" name="section_ID">
      <input type="text"   value="<?php echo $title ?>" name="title">
      <input type="submit" value="Rename">

</form>
