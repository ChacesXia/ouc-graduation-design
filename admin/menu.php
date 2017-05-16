<?php require_once("admin-header.php");
  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
?>
<html>
<head>
  <title><?php echo $MSG_ADMIN?></title>
  <style type="text/css">
    li{
      margin-top: 4;
    }
  </style>
</head>

<body>
<hr>
<h4>
<ol >
  <li>
    <a class='btn btn-primary' href="../status.php" target="main"><b><?php echo $MSG_SEEOJ?></b></a>
<?php if (isset($_SESSION['administrator'])){
  ?>
  <li>
    <a class='btn btn-primary' href="news_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_NEWS?></b></a>
  <li>
    <a class='btn btn-primary' href="news_list.php" target="main"><b><?php echo $MSG_NEWS.$MSG_LIST?></b></a>
    
<?php }
if (isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])){
?>
  <li>
    <a class='btn btn-primary' href="problem_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_PROBLEM?></b></a>
<?php }
if (isset($_SESSION['administrator'])||isset($_SESSION['test_creator'])||isset($_SESSION['problem_editor'])){
?>
  <li>
    <a class='btn btn-primary' href="problem_list.php" target="main"><b><?php echo $MSG_PROBLEM.$MSG_LIST?></b></a>
<?php }
if (isset($_SESSION['administrator'])||isset($_SESSION['test_creator'])){
?>    
<li>
  <a class='btn btn-primary' href="test_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_test?></b></a>
<?php }
if (isset($_SESSION['administrator'])||isset($_SESSION['test_creator'])){
?>
<li>
  <a class='btn btn-primary' href="test_list.php" target="main"><b><?php echo $MSG_test.$MSG_LIST?></b></a>
<?php }
if (isset($_SESSION['administrator'])){
?>
<!-- <li>
  <a class='btn btn-primary' href="team_generate.php" target="main"><b><?php echo $MSG_TEAMGENERATOR?></b></a>
</li> -->

<!-- <li>
  <a class='btn btn-primary' href="setmsg.php" target="main"><b><?php echo $MSG_SETMESSAGE?></b></a>
  </li> -->
<?php }
if (isset($_SESSION['administrator'])||isset( $_SESSION['password_setter'] )){
?><li>
  <a class='btn btn-primary' href="changepass.php" target="main"><b><?php echo $MSG_SETPASSWORD?></b></a>
<?php }
if (isset($_SESSION['administrator'])){
?>
<!-- <li>
  <a class='btn btn-primary' href="rejudge.php" target="main"><b><?php echo $MSG_REJUDGE?></b></a> -->
<?php }
if (isset($_SESSION['administrator'])){
?><li>
  <a class='btn btn-primary' href="privilege_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_PRIVILEGE?></b></a>
<?php }
if (isset($_SESSION['administrator'])){
?><li>
  <a class='btn btn-primary' href="privilege_list.php" target="main"><b><?php echo $MSG_PRIVILEGE.$MSG_LIST?></b></a>
<?php }
if (isset($_SESSION['administrator'])){
?>
<!-- <li>
  <a class='btn btn-primary' href="source_give.php" target="main"><b><?php echo $MSG_GIVESOURCE?></b></a> -->
<?php }
if (isset($_SESSION['administrator'])){
?>
<li>
  <a class='btn btn-primary' href="problem_export.php" target="main"><b><?php echo $MSG_EXPORT.$MSG_PROBLEM?></b></a>
<?php }
if (isset($_SESSION['administrator'])){
?>
<!-- <li>
  <a class='btn btn-primary' href="problem_import.php" target="main"><b><?php echo $MSG_IMPORT.$MSG_PROBLEM?></b></a> -->
<?php }
if (isset($_SESSION['administrator'])){
?>
<!-- <li>
  <a class='btn btn-primary' href="update_db.php" target="main"><b><?php echo $MSG_UPDATE_DATABASE?></b></a> -->
<?php }
if (isset($OJ_ONLINE)&&$OJ_ONLINE){
?>
<!-- <li>
  <a class='btn btn-primary' href="../online.php" target="main"><b><?php echo $MSG_ONLINE?></b></a> -->
<?php }
?>
  </li>
  <li><a class='btn btn-info' href=".." target="_blank"><b>返回首页</b></a></li>
</ol>
<?php if (isset($_SESSION['administrator'])&&!$OJ_SAE){
?>
<!--   <a href="problem_copy.php" target="main" title="Create your own data"><font color="eeeeee">CopyProblem</font></a> <br>
  <a href="problem_changeid.php" target="main" title="Danger,Use it on your own risk"><font color="eeeeee">ReOrderProblem</font></a> -->
   
<?php }
?>
<h4>
</body>
</html>
