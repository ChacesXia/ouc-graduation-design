<?php
////////////////////////////Common head
  $cache_time=10;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $view_title= "新闻";
  if(isset($_GET['news_id'])){
    $news_id = $_GET["news_id"];
    $sql = "select * from news where news_id =".$news_id;
    $result=mysqli_query($mysqli,$sql);
    $news=mysqli_fetch_object($result);
    require("template/".$OJ_TEMPLATE."/newsdetail.php");
  }
/////////////////////////Template

/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>