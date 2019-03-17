<?php

   // IoFo
   // Move Forum Page
   
   // posable locations
   // above other subs
   // end of section

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
   
   $sql = "SELECT * FROM subforum WHERE sub_ID=".$sub_ID;
   $result = mysql_query($sql,$connection);
   
   $test         = mysql_result($result,0,'sub_ID');
   $old_location = mysql_result($result,0,'location');
   $old_section  = mysql_result($result,0,'section_ID');
   
   if (($test != $sub_ID) OR (!$test)) die("ERROR FINDING FORUM");

?>

<?php

   $sql = "SELECT * FROM sections ORDER BY location ASC";
   $result = mysql_query($sql,$connection);

   echo '
          <table width="80%" border="1" cellspacing="4">
   ';

   for ($i=0;$i<mysql_num_rows($result);$i++)
   {
        echo '
              <tr><td bgcolor="#9a9a9a" colspan="2">
              <b>Section: </b>'.mysql_result($result,$i,"title").'
              </td></tr>

        ';
        
        // Subs
        $section_ID = mysql_result($result,$i,'section_ID');
        $sql = "SELECT * FROM subforum WHERE section_ID=".$section_ID." ORDER BY location ASC";
        $subResult = mysql_query($sql,$connection);
        for ($j=0;$j<mysql_num_rows($subResult);$j++)
        {
            $title       = mysql_result($subResult,$j,'title');
            $draw_sub_ID = mysql_result($subResult,$j,'sub_ID');
            $location    = mysql_result($subResult,$j,'location');
            
            if (($sub_ID == $draw_sub_ID) OR (($section_ID==$old_section) AND ($location-1 == $old_location) ))
            {
            }
            else
            echo '

                 <tr><td></td><td>
                 <form action="movingforum.php" method="POST">
                       <input type="hidden" name="section" value="'. $section_ID .'">
                       <input type="hidden" name="location" value="'. $location .'">
                       <input type="hidden" name="old_location" value="'. $old_location .'">
                       <input type="hidden" name="old_section" value="'. $old_section .'">
                       <input type="hidden" name="sub_ID"   value="'. $sub_ID .'">
                       <input type="submit" value="Move Here">
                 </form>
                 </td>
                 </tr>

                 ';

            if (($section_ID == $old_section) AND ($draw_sub_ID == $sub_ID))
            echo '

                 <tr><td width="50px"></td><td bgcolor="#FFDD50">
                 <b>Sub Forum: </b>'. $title .'
                 </td></tr>

                 ';
            else
            echo '

                 <tr><td width="50px"></td><td bgcolor="#cecece">
                 <b>Sub Forum: </b>'. $title .'
                 </td></tr>

                 ';

        }
        if (($section_ID != $old_section)OR($sub_ID != $draw_sub_ID))
        echo '

             <tr><td></td><td>
             <form action="movingforum.php" method="POST">
                   <input type="hidden" name="location" value="'. $j .'">
                   <input type="hidden" name="section" value="'. $section_ID .'">
                   <input type="hidden" name="sub_ID"   value="'. $sub_ID .'">
                   <input type="hidden" name="old_location" value="'. $old_location .'">
                   <input type="hidden" name="old_section" value="'. $old_section .'">
                   <input type="submit" value="Move Here">
             </form>
             </td>
             </tr>

             ';

   }
   echo '
   </table>
   ';

?>
