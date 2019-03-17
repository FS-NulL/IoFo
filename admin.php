<?php

   // IoFo
   // Admin Page
   
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

?>


<?php

     $sql = "SELECT * FROM sections ORDER BY location ASC";

     $secResult = mysql_query($sql,$connection);
     
     echo '
          <a href="index.php">Forum Index</a>
          <br>
          <table width="80%" border="1" cellspacing="4">

     ';
     
     for ($i=0;$i<mysql_num_rows($secResult);$i++)
     {
     	// Output TODO:: Formatting
     	echo "<tr><td bgcolor='#9a9a9a' colspan='2'>
             <div align='left'>
                  <b>Section: </b>".mysql_result($secResult,$i,"title")."
             </div>
             <div align='right'>
             <table>
             <tr>
             <td>
                  <form action='renamesection.php' method='GET'>
                        <input type='hidden' name='section_ID' value='". mysql_result($secResult,$i,"section_ID") ."'><input type='submit' value='Rename'>
                  </form>
             </td>
             <td>
                  <form action='deletesection.php' method='GET'>
                        <input type='hidden' name='section_ID' value='". mysql_result($secResult,$i,"section_ID") ."'><input type='submit' value='Delete'>
                  </form>
             </td>
             <td>
                  <form action='movesection.php' method='GET'>
                        <input type='hidden' name='section_ID' value='". mysql_result($secResult,$i,"section_ID") ."'><input type='submit' value='Move'>
                  </form>
             </td>
             </tr>
             </table>
             </div>
             
             
             </td></tr>";

     	// subs
     	$sql = "SELECT * FROM subforum WHERE section_ID = ". mysql_result($secResult,$i,"section_ID") ." ORDER BY location ASC";
     	$subResult = mysql_query( $sql , $connection );
     	

     	for ($j=0;$j<mysql_num_rows($subResult);$j++)
     	{
           // Output TODO:: Formatting
           echo "<tr><td width='50px'></td><td bgcolor='#cecece'>
           <div align='left'>
                <b>Sub Forum: </b>".mysql_result($subResult,$j,"title")."
           </div>
           <div align='right'>
           <table>
           <tr>
           <td>
               <form action='renameforum.php' method='GET'>
                     <input type='hidden' name='sub_ID' value='". mysql_result($subResult,$j,"sub_ID") ."'><input type='submit' value='Rename'>
               </form>
           </td>
           <td>
               <form action='deleteforum.php' method='GET'>
                     <input type='hidden' name='sub_ID' value='". mysql_result($subResult,$j,"sub_ID") ."'><input type='submit' value='Delete'>
               </form>
           </td>
           <td>
               <form action='moveforum.php' method='GET'>
                     <input type='hidden' name='sub_ID' value='". mysql_result($subResult,$j,"sub_ID") ."'><input type='submit' value='Move'>
               </form>
           </td>
           </tr>
           </table>
           </div>

           </td></tr>";

     	}
     	// add sub
     	// pass sec_ID

     	$newloc = mysql_result($subResult,$j,"location");
     	$newloc++;

     	echo '

        <tr><td width="50px"></td> <td bgcolor="#cecece">
        <form action="newsub.php" method="GET">
     	      <b>Create New Forum</b> Title:<input type="text" name="title">
     	      <input type="hidden" name="location" value="'. $j .'" />
     	      <input type="hidden" name="section_ID" value="'. mysql_result($secResult,$i,"section_ID") .'" />
     	      <input type="submit" value="New Forum">
     	</form>
     	</td>

     	';

     }
     //addsec
     echo '
     
     <tr><td bgcolor="#9a9a9a" colspan="2">
     <form action="newsec.php" method="GET">
           <b>Crate New Section</b> Title:<input type="text" name="title">
           <input type="hidden" name="location" value="'. $i .'" />
           <input type="submit" value="New Section">
     </form>
     </td></tr>
     
     </table>

     ';
     
?>
<br>
<form action="grantadmin.php" method="POST">
      Grant Admin Status:
      <select name="select">

      <?php

           $sql = "SELECT user_id,username,adminflag FROM user";
           $result = mysql_query($sql,$connection);
           for ($i=0;$i<mysql_num_rows($result);$i++)
           {
               $user_ID  = mysql_result($result,$i,'user_ID');
               $username = mysql_result($result,$i,'username');
               $adminflag = mysql_result($result,$i,'adminflag');
               if (!$adminflag) echo '<option value="'. $user_ID .'">'.$username.'</option>';
           }

      ?>

      </select>
      <input type="submit" value="grant">
</form>

<form action="removeadmin.php" method="POST">
      Remove Admin Status:
      <select name="select">

      <?php

           $sql = "SELECT user_id,username,adminflag FROM user WHERE adminflag AND user_ID !=".$_SESSION['user_ID'];
           $result = mysql_query($sql,$connection);
           for ($i=0;$i<mysql_num_rows($result);$i++)
           {
               $user_ID  = mysql_result($result,$i,'user_ID');
               $username = mysql_result($result,$i,'username');
               echo '<option value="'. $user_ID .'">'.$username.'</option>';
           }

      ?>

      </select>
      <input type="submit" value="Remove">
</form>




