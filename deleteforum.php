<?php
   
   // IoFo
   // Delete Sub Forum
   
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
   
   $sub_ID = $_GET['sub_ID'];
   
   $sql = "SELECT * FROM subforum WHERE sub_ID=".$sub_ID;
   
   $result = mysql_query($sql,$connection);
   
   $test   = mysql_result($result,0,'sub_ID');
   $title  = mysql_result($result,0,'title');
   
   if ($test != $sub_ID) die("ERROR FINDING FORUM");

?>

<form action="deletingforum.php" method="POST">

      Confirm:<input type="checkbox" name="confirm">
      <input type="hidden" name="sub_ID" value="<?php echo $sub_ID ?>">
      <br>
      <select name="options">
              <option value="kill">Drop Threads</option>
              <?php
              
                   $sql = "SELECT title,sub_ID FROM subforum WHERE sub_ID!=".$sub_ID;
                   $result = mysql_query($sql,$connection);
                   for ($i=0;$i<mysql_num_rows($result);$i++)
                   {
                   	echo "<option value='". mysql_result($result,$i,"sub_ID") ."'>Move Threads To ". mysql_result($result,$i,"title") ." Forum</option>";
                   }

              ?>
      </select>
      
      <input type="submit" value="Delete">

</form>
