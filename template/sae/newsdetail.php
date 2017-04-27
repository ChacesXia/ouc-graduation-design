<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title; ?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
  <?php require_once("oj-header.php");?>
<div id=main>

<!-- <hr> -->

<center>
  <font size="+3"><?php echo $news->title;?></font>
  <br>
  <?php 
    echo "<span class=green>发布人: </span>$news->user_id";
    echo "<span class=green>发布时间: </span>".$news->time;
  ?>
</center>
<hr>
<?php 
  echo "<p>$news->content</p>";
  ?>
<hr>
  <div id=foot>
    <?php require_once("oj-footer.php");?>
  </div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
