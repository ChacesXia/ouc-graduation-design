<html>
<head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
      .left{
        margin-left: 30px;
      }
      .text-center{
        text-align: center;
      }
    </style>
</head>
<body >

<?php require_once("../include/db_info.inc.php");?>
<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
?>
<?php
  include_once("kindeditor.php") ;
?>
<div class="left">
  <h2 class="text-center">添加新闻</h2>
  <hr>
  <form method=POST action=news_add.php>
    <p align=left><span >标题:  </span><input class="input-xxlarge" type=text name=title size=70 width="200"></p>
    <p align=left>
         内容:<br>
         <textarea class=kindeditor name=content ></textarea>
    </p>
    <input class="btn btn-success" type=submit value=Submit name=submit>
    <?php require_once("../include/set_post_key.php");?>
 </form>
</div>
<?php require_once("../oj-footer.php");?>
