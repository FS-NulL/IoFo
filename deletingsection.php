<?php

   // IoFo
   // Deleting Section Page
   
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

   $option     = $_POST['options'];
   $confirm    = $_POST['confirm'];   // NULL / on
   $section_ID = $_POST['section_ID'];

   $sql = "SELECT location FROM sections WHERE section_ID=".$section_ID;
   $result = mysql_query($sql,$connection);
   $del_location = mysql_result($result,0,'location');


   // Jump back if not confirmed
   if ($confirm != 'on')
   {
   	header("Location: admin.php");
   	die("Not Confirmed");
   }
   
   if ($option == "kill")
   {
        $sql = "DELETE FROM sections WHERE section_ID=".$section_ID;
        mysql_query($sql,$connection);

        $sql = "SELECT sub_ID,section_ID FROM subforum WHERE section_ID=".$section_ID;
        $result = mysql_query($sql,$connection);
        //echo "<br>".$sql;
        for ($i=0;$i<mysql_num_rows($result);$i++)
        {
        	$sub_ID = mysql_result($result,$i,'sub_ID');
        	// recure lower for threads

        	//echo "<br>HERE1";


        	$sql="SELECT thread_ID,sub_ID FROM thread WHERE sub_ID=".$sub_ID;
        	$thrresult = mysql_query($sql,$connection);
        	for ($j=0;$j<mysql_num_rows($thrresult);$j++)
        	{
                    $thread_ID = mysql_result($thrresult,$j,'thread_ID');
                    $sql="DELETE FROM posts WHERE thread_ID=".$thread_ID;
                    mysql_query($sql,$connection);
                    //echo "<br>".$sql;
                    // delete thread
                    $sql="DELETE FROM thread WHERE thread_ID=".$thread_ID;
                    mysql_query($sql,$connection);
                    //echo "<br>".$sql;
        	}
        }
        $sql = "DELETE FROM subforum WHERE section_ID=".$section_ID;
        mysql_query($sql,$connection);
   }
   else
   {
   	// NEED TO UPDATE LOCATIONS
   	// GET MAX location from subforum for section to move to;

   	$sql = "DELETE FROM sections WHERE section_ID=".$section_ID;
   	mysql_query($sql,$connection);

   	$sql = "SELECT location FROM subforum WHERE section_ID=". $option ." ORDER BY location DESC LIMIT 1";
   	$result = mysql_query($sql,$connection);

   	$location = mysql_result($result,0,'location');
   	
   	if (!$location) $location=-1;

   	// loop through moving subs giving new location

   	$sql = "SELECT * FROM subforum WHERE section_ID=".$section_ID." ORDER BY location ASC";

   	$result = mysql_query($sql,$connection);

        for ($i=0;$i<mysql_num_rows($result);$i++)
        {
        	$location++;
        	$sql = "UPDATE subforum SET location=".$location.", section_ID=".$option." WHERE sub_ID=".mysql_result($result,$i,"sub_ID");
        	mysql_query($sql,$connection);
        }
   }
   
   // Update locations
   
   $sql = "SELECT * FROM sections WHERE location > ".$del_location;
   $result = mysql_query($sql,$connection);
   
   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
   	$location   = mysql_result($result,$i,'location');
   	$location--;
   	$section_ID = mysql_result($result,$i,'section_ID');

   	$sql = "UPDATE sections SET location=". $location ." WHERE section_ID=".$section_ID;
   	mysql_query($sql,$connection);
   }

   header("Location: admin.php");
?>
