<?php

   // IoFo
   // Admin Delete Thread Page
   
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

   if ($_POST['post'] == "true")
   {
      if ($_POST['confirm'] == "on")
      {
         $sql = "DELETE FROM posts WHERE thread_ID=".$_POST['t'];
         mysql_query($sql,$connection);

         $sql = "DELETE FROM thread WHERE thread_ID=".$_POST['t'];
         mysql_query($sql,$connection);
      }
      $referer = $_POST['backurl'];
      if ($referer == "") header("Location: index.php");
      else header("Location: $referer");
      die();
   }
   else
   {
       $referer = $_SERVER['HTTP_REFERER'];
   }



?>


<form action="deletethread.php" method="POST">
      Confirm:
      <input type="hidden" name="backurl" value="<?php echo $referer ?>">
      <input type="hidden" name="post" value="true">
      <input type="hidden" name="t" value="<?php echo $_GET['t'] ?>"
      <input type="checkbox" name="confirm">
      <input type="submit" value="Delete">

</form>
