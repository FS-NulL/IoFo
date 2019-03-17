<?php

   // IoFo
   // Login Page
   
   session_start();
   
   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");
   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   mysql_selectdb( $db_data );

   $submit = $_POST['submit'];
   $username = $_POST['username'];
   $password = $_POST['password'];
   
   if ($_SESSION['loggedin']=="true")
   {
   	die('<meta http-equiv="refresh" content="2;index.php">Already Logged In As:'.$_SESSION['username']);
   }

   if (($submit=="true") AND ($password) AND ($username))
   {

        // Process Login

        $cryptpass = crypt($password,$db_cryptkey);

        $sql = "SELECT user_ID,username,password FROM user WHERE username='".$username."'";
        $result = mysql_query($sql,$connection);

        $testuser = mysql_result($result,0,'username');
        $testpass = mysql_result($result,0,'password');
        
        $username = strtolower($username);
        $testuser = strtolower($testuser);

        if (($testuser == $username) AND ($testpass == $cryptpass))
        {
            // Login
            $_SESSION['loggedin'] = "true";
            $_SESSION['username'] = mysql_result($result,0,'username');
            $_SESSION['user_ID']  = mysql_result($result,0,'user_ID');
            
            echo '<meta http-equiv="refresh" content="2;index.php">';
            echo 'Logged in as: '.$_SESSION['username'];
            die();
        }
        else
        {
            //echo "user:$username pass:$password cryptpass:$cryptpass fdb_user:$testuser fdb_pass:$testpass<br>";
            echo 'Try Again<br>';
        }


   }



?>

<form action="login.php" method="POST">
      <input type="hidden" name="submit" value="true">
      User:<input type="text" name="username" value="">
      <br>
      Pass:<input type="password" name="password" value="">
      <br>
      <input type="submit" value="login">
</form>
