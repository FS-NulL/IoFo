<?php

   // IoFo
   // Move Section
   
   // display posable locations
   // button above sections
   // highlight selected
   // button at end

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

   $sql = "SELECT location,section_ID FROM sections WHERE section_ID=".$section_ID;
   $result = mysql_query($sql,$connection);
   
   $test = mysql_result($result,0,'section_ID');
   $old_location = mysql_result($result,0,'location');
   
   if (($test != $section_ID)OR (!$section_ID)) die("ERROR FINDING SECTION");



?>


<?php

     $sql = "SELECT * FROM sections ORDER BY location ASC";

     $result = mysql_query($sql,$connection);

     echo '<table width="80%" border="1">';

     for ($i=0;$i<mysql_num_rows($result);$i++)
     {
     	$sec_ID   = mysql_result($result,$i,'section_ID');
     	$title    = mysql_result($result,$i,'title');
     	$location = mysql_result($result,$i,'location');

     	if (($sec_ID == $section_ID))
        {
        	echo'<tr><td bgcolor="#FFDD50"><b>Section:</b>'. $title .'</td></tr>';
        }
        elseif (($location-1)==$old_location)
        {
        	echo'<tr><td bgcolor="#9a9a9a"><b>Section:</b>'. $title .'</td></tr>';
        }
        else
        {
            echo '<tr><td align="center">

                 <form action="movingsection.php" method="POST">
                       <input type="submit" value="Move Here">
                       <input type="hidden" name="location" value="'.$location.'">
                       <input type="hidden" name="old_location" value="'.$old_location.'">
                       <input type="hidden" name="section_ID" value="'.$section_ID.'">
                 </form>

             </td></tr>';

     	    echo'<tr><td bgcolor="#9a9a9a"><b>Section:</b>'. $title .'</td></tr>';
        }
     }

     if ($sec_ID != $section_ID)
     {
        echo '<tr><td align="center">

                 <form action="movingsection.php" method="POST">
                       <input type="submit" value="Move Here">
                       <input type="hidden" name="location" value="'.$i.'">
                       <input type="hidden" name="old_location" value="'.$old_location.'">
                       <input type="hidden" name="section_ID" value="'.$section_ID.'">
                 </form>

              </td></tr>';
     }

     echo '</table>';

?>
