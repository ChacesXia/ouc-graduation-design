<?php
  session_start();
  require_once("./db_info.inc.php");
  // $mysqli=$GLOBALS['mysqli'];
  $solution_id = $_POST['solution_id'];
  $score = $_POST['score'];
  if(empty($_POST['score'])){
    $score = 60;
  }
  $sql = "update `solution` set `score` =".$score." where `solution_id` =".$solution_id;
  $result = mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli));
  header('Location: ../status.php');
  exit(0);
?>