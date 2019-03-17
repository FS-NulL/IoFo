<?php

   // IoFo
   // New Thread Page
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );
   
   // Check logged in
   
   if ($_SESSION['loggedin'] != "true")
   {
   	echo '<meta http-equiv="refresh" content="2;index.php">Please Login First';
   	die();
   }
   
   $sub_ID = $_GET['f'];
   
   // Check forum exists
   
   $sql = "SELECT count(*) FROM subforum WHERE sub_ID=".$sub_ID;
   $result = mysql_query($sql,$connection);
   $count = mysql_result($result,0,'count(*)');
   if ($count != 1)
   {
        echo '<meta http-equiv="refresh" content="2;index.php">Forum Not Found';
   	die();
   }

?>

<form action="posting.php" method="POST">
      Title:<br><input type="text" name="title" value=""><br>
      Message:<br><textarea name="message" style="width: 35%" rows="10"></textarea><br>
      <input type="hidden" name="forum" value="<?php echo $sub_ID ?>">
      <input type="hidden" name="type" value="thread">
      <input type="submit" value="Post Thread">
</form>
