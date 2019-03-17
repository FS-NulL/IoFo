<?php

   // IoFo
   // User Profiles
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );

   $user_ID = $_GET['u'];

   // Check exists

   $sql = "SELECT * FROM user WHERE user_ID=$user_ID";
   $result = mysql_query($sql,$connection);
   $test = mysql_result($result,0,'user_ID');

   if (($user_ID != $test)OR(!$test))
   {
   	echo '<meta http-equiv="refresh" content="2;index.php">User Not Found';
   	die();
   }
   
   $username = mysql_result($result,0,'username');
   $email    = mysql_result($result,0,'email');

   $sql = "SELECT count(*) FROM posts WHERE user_ID=".$user_ID;
   $result = mysql_query($sql,$connection);
   $postcount = mysql_result($result,0,'count(*)');


?>

Username: <?php echo $username ?>
<br>
Email: <?php echo $email ?>
<br>
Post Count: <?php echo $postcount ?>
<br>
<a href="search.php?mode=author&u=<?php echo $user_ID ?>">Find all posts by this user</a>
