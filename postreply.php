<?php

   // IoFo
   // Post Reply Page
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );

   if ($_SESSION['loggedin'] != "true")
   {
   	echo '<meta http-equiv="refresh" content="2;index.php">Please Login First';
   	die();
   }
   
   $thread_ID = $_GET['t'];
   
   // Check Thread Exists
   
   $sql = "SELECT count(*) FROM thread WHERE thread_ID=".$thread_ID;
   $result = mysql_query($sql,$connection);
   $count  = mysql_result($result,0,'count(*)');
   
   if ($count != 1)
   {
        echo '<meta http-equiv="refresh" content="2;index.php">Forum Not Found';
   	die();
   }

?>


<form action="posting.php" method="POST">
      Message:<br><textarea name="message" style="width: 35%" rows="10"></textarea><br>
      <input type="hidden" name="thread" value="<?php echo $thread_ID ?>">
      <input type="hidden" name="type" value="reply">
      <input type="submit" value="Post Reply">
</form>
