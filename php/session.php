<?php
   require_once('config.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $result = $conn->query("select * from `users` where `email_address` = '$user_check'");
   
   $row = $result->fetch_assoc();
   
   $login_session = $row;
   
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
      die();
   }
?>