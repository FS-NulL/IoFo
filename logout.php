<?php

  session_start();
  $_SESSION['loggedin'] = "false";
  $_SESSION['username'] = "";
  $_SESSION = array();
  session_destroy();

  $referer = $_SERVER['HTTP_REFERER'];
  
  if ($referer == "") header("Location: index.php");
  else header("Location: $referer");

?>
