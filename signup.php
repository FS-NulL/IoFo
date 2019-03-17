<?php

   // IoFo
   // Signup Page

   session_start();
   
   if ($_SESSION['loggedin'] == "true")
   {
   	echo '<meta http-equiv="refresh" content="4;index.php">';
   	echo 'you are already logged in';
   	die();
   }
   
   // Process input
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );

   $username = $_POST['username'];
   $pass1    = $_POST['pass1'];
   $pass2    = $_POST['pass2'];
   $email    = $_POST['email'];
   $submit   = $_POST['submit'];

   if ($submit === "true")
   {
   	// Atempt registration
   	
   	$sql = "SELECT username FROM user WHERE username='".$username."'";
   	$result = mysql_query($sql,$connection);
   	$test = mysql_result($result,0,'username');
   	
        $error = 0;
   	if ($test == $username)
        {
           $error_username_taken = "true";
           $error++;
        }
        if (!ereg("^[a-zA-Z0-9]{3,}$",$username))
        {
           $error_username_chars = "true";
           $error++;
        }
        if ($pass1 == "")
        {
           $error_pass1_null = "true";
           $error++;
        }
        if ($pass2 == "") 
        {
           $error_pass2_null = "true";
           $error++;
        }
        if (strlen($pass1) < 8) 
        {
           $error_pass1_len = "true";
           $error++;
        }
        if (strlen($pass2) < 8)
        {
           $error_pass2_len = "true";
           $error++;
        }
        if (!strpos($email,'@') > 0)
        {
           $error_email_format = "true"; 
           $error++;
        }
        if ($pass1 != $pass2)
        {
           $error_pass_equal = "true"; 
           $error++;
        }

        if ($error == 0)
        {
           // Enter into database
           // send to other page
           
           $pass_enc = crypt($pass1,$db_cryptkey);

           $sql = "INSERT INTO user (username,password,email) values ('".$username."','".$pass_enc."','".$email."')";
           mysql_query($sql,$connection);
           
           //header("Location: signup_complete.php");
           echo '
                <meta http-equiv="refresh" content="4;index.php">
                Sign up success<br>
                <a href="index.php">You are beeing returned to index.php</a>
           ';
           die();

        }

   }

?>

<form action="signup.php" method="POST" enctype="multipart/form-data">
      Username:<input type="text" name="username" value="<?php echo $username ?>"> 
      <?php
           if($error_username_chars == "true") echo 'Username Invalid Format';
           else if ($error_username_taken == "true") echo 'Username Taken';
      ?>
      <br>
      Password:<input type="password" name="pass1"    value="<?php echo $pass1 ?>"> 
      <?php
           if ($error_pass1_null == "true") echo 'Enter password';
           elseif ($error_pass1_len == "true") echo 'Must Be longer than 7 chars';
      ?>
      <br>
      Password:<input type="password" name="pass2"    value="<?php echo $pass2 ?>">
      <?php
           if ($error_pass2_null == "true") echo 'Enter password';
           elseif ($error_pass2_len == "true") echo 'Must Be longer than 7 chars';
           elseif ($error_pass_equal == "true") echo 'Passwords not equal';
      ?>
      <br>
      Email:   <input type="text" name="email"    value="<?php echo $email ?>"> 
      <?php
           if ($error_email_format == "true") echo 'Enter corrent email';
      ?>
      <br>
      <input type="hidden" name="submit" value="true">
      <input type="submit" value="Register">
</form>

