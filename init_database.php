<?php
   // Io Forum 2005
   // Create Database Init Page

   require($_SERVER["DOCUMENT_ROOT"] . "/iofo/config/db_config.php");

   $connection = mysql_connect( $db_host, $db_user, $db_pass) or die(mysql_error());
   
   $sql = "CREATE DATABASE " . $db_data;
   mysql_query($sql,$connection);
   mysql_selectdb($db_data,$connection);

   $sql = "CREATE TABLE sections (section_ID int unsigned not null unique primary key auto_increment,location int unsigned not null, title varchar(64) not null)";
   mysql_query($sql,$connection) or die("ERROR: Create sections: ". mysql_error());

   $sql = "CREATE TABLE subforum (sub_ID int unsigned primary key not null auto_increment, section_ID int unsigned not null,location int not null, title varchar(64) not null)";
   mysql_query($sql,$connection) or die("ERROR: Create subs: ". mysql_error());

   $sql = "CREATE TABLE thread   (thread_ID int unsigned primary key unique not null auto_increment, sub_ID int unsigned not null, title varchar(64),lastpost int unsigned)";
   mysql_query($sql,$connection) or die("ERROR: Create threads: ". mysql_error());

   $sql = "CREATE TABLE posts    (post_ID int unsigned primary key unique auto_increment, thread_ID int unsigned not null, user_ID int unsigned not null, post_time int unsigned not null, contents text)";
   mysql_query($sql,$connection) or die("ERROR: Create posts: ". mysql_error());

   $sql = "CREATE TABLE user     (user_ID int unsigned primary key auto_increment unique, username varchar(64) not null, password varchar(64) not null, icon mediumblob, adminflag bool, email varchar(64))";
   mysql_query($sql,$connection) or die("ERROR: Create users: ". mysql_error());
   
   $sql = "INSERT INTO user (username,password,adminflag) values ('admin','".crypt($db_adminpass,$db_cryptkey)."',TRUE)";
   mysql_query($sql,$connection);

   $sql = "INSERT INTO user (username,password,adminflag) values ('administrator','".crypt($db_adminpass,$db_cryptkey)."',TRUE)";
   mysql_query($sql,$connection);
        
?>
