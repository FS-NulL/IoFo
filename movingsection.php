<?php

   // IoFo
   // Moving Section Page
   
   // 1, move other sections >=$new_location plus one location
   // 2, insert moving section
   // 3, need old location to move >$old_location minus one location
   
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
   
   //Check Exists

   $section_ID   = $_POST['section_ID'];
   $new_location     = $_POST['location'];
   $old_location = $_POST['old_location'];
   
   $sql = "SELECT location,section_ID FROM sections WHERE section_ID=".$section_ID;
   $result = mysql_query($sql,$connection);

   $test = mysql_result($result,0,'section_ID');
   $old_location = mysql_result($result,0,'location');

   if (($test != $section_ID)OR (!$section_ID)) die("ERROR FINDING SECTION");

   echo "<br>Section:".$section_ID;
   echo "<br>NewLoc :".$new_location;
   echo "<br>OldLoc :".$old_location;
   
   // 1,
   echo "<br>1";
   $sql = "SELECT * FROM sections WHERE location>=".$new_location;
   echo "<br>".$sql;
   $result = mysql_query($sql,$connection);
   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$sec_ID   = mysql_result($result,$i,'section_ID');
   	$location = mysql_result($result,$i,'location'  );
   	$location++;
   	$sql = "UPDATE sections SET location=".$location." WHERE section_ID=".$sec_ID;
   	mysql_query($sql,$connection);
   	echo "<br>".$sql;
   }

   // 2,
   echo "<br>2";
   $sql = "UPDATE sections SET location=".$new_location." WHERE section_ID=".$section_ID;
   mysql_query($sql,$connection);
   echo "<br>".$sql;

   // 3,
   echo "<br>3";
   $sql = "SELECT * FROM sections WHERE location>".$old_location;
   $result = mysql_query($sql,$connection);
   
   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$sec_ID   = mysql_result($result,$i,'section_ID');
   	$location = mysql_result($result,$i,'location'  );
   	$location--;
   	$sql = "UPDATE sections SET location=".$location." WHERE section_ID=".$sec_ID;
   	mysql_query($sql,$connection);
   	echo "<br>".$sql;
   }
   
   header("Location: admin.php");

?>
