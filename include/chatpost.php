<?php
  session_start();
  require_once("./db_info.inc.php");
  // $mysqli=$GLOBALS['mysqli'];
  $solution_id = $_POST['solution_id'];
  $from_user = $_POST['from_user'];
  $to_user = $_POST['to_user'];
  $message = $_POST['message'];
  $now = time();
  $sql = "insert into `chatlog` (`solution_id`,`from_user`,`to_user`,`message`,`time`) values('".$solution_id."','".$from_user."','".$to_user."','".$message."',NOW())";
  $result = mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
  header('Location: ../showsource.php?id='.$solution_id);
  exit(0);
?>