<?php

   // IoFo
   // Delete Section Page
   
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
   
   // Check Exists
   
   $section_ID = $_GET['section_ID'];
   
   $sql = "SELECT section_ID, title FROM sections WHERE section_ID=".$section_ID;
   
   $result = mysql_query($sql,$connection);
   
   $test  = mysql_result($result,0,"section_ID");
   $title = mysql_result($result,0,"title");
   
   if ($test != $section_ID) die("ERROR FINDING FORUM");

?>

<form action="deletingsection.php" method="POST">

      Confirm:
      <input type="checkbox" name="confirm">
      <input type="hidden" name="section_ID" value="<?php echo $section_ID  ?>">
      <br>
      <select name="options">

      <option value="kill">Drop Sub Forums</option>

      <?php

      $sql = "SELECT section_ID, title FROM sections WHERE section_ID!=".$section_ID;
      $result = mysql_query($sql,$connection);

      for ($i=0;$i<mysql_num_rows($result);$i++)
      {
          echo "<option value='". mysql_result($result,$i,"section_ID") ."'>Move Sub Forums To ". mysql_result($result,$i,"title") ." Section</option>";
      }

      ?>

      </select>
      <input type="submit" value="Delete">

</form>
