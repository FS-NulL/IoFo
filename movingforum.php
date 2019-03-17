<?php

   // IoFo
   // Moving Forum Page
   
   // 1, move other forums >=$location plus one location
   // 2, insert moving forum location and section_ID
   // 3, need old location to move >$old_location (in $old_section only ) minus one location
   
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

   $section      = $_POST['section'];
   $location     = $_POST['location'];
   $old_section  = $_POST['old_section'];
   $old_location = $_POST['old_location'];
   $sub_ID       = $_POST['sub_ID'];
   
   echo "<BR>".$section;
   echo "<BR>".$location;
   echo "<BR>".$old_section;
   echo "<BR>".$old_location;
   echo "<BR>".$sub_ID;
   
   echo "<BR>Moving Subforum $sub_ID From Section $old_section Location $old_location To Section $section Location $location";
   
   // Check exists
   
   $sql    = "SELECT * FROM subforum WHERE sub_ID=".$sub_ID;
   $result = mysql_query($sql,$connection);
   
   $test = mysql_result($result,0,'sub_ID');
   if ($test != $sub_ID) die("ERROR FINDING FORUM");
   
   // 1,
   echo "<br>1";

   $sql = "SELECT * FROM subforum WHERE location >=". $location ." AND section_ID=".$section;
   $result = mysql_query($sql,$connection);
   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
       $s1_location = mysql_result($result,$i,'location');
       $s1_sub_ID   = mysql_result($result,$i,'sub_ID');
       $s1_location++;
       $sql = "UPDATE subforum SET location=".$s1_location." WHERE sub_ID=".$s1_sub_ID;
       mysql_query($sql,$connection);
       echo "<br>".$sql;
   }

   // 2,
   echo "<br>2";

   $sql = "UPDATE subforum SET location=".$location.", section_ID=".$section." WHERE sub_ID=".$sub_ID;
   mysql_query($sql,$connection);
   echo "<br>".$sql;

   // 3,
   // need old location to move >$old_location (in $old_section only ) minus one location
   echo "<br>3";
   $sql = "SELECT * FROM subforum WHERE location>".$old_location." AND section_ID=".$old_section;
   $result = mysql_query($sql,$connection);

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$s3_location = mysql_result($result,$i,'location');
   	$s3_sub_ID   = mysql_result($result,$i,'sub_ID'  );
   	$s3_location--;

   	$sql = "UPDATE subforum SET location=".$s3_location." WHERE sub_ID=".$s3_sub_ID;
   	mysql_query($sql);
   	echo "<br>".$sql;
   }

   header("Location: admin.php");

?>

