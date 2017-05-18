<?php require_once("admin-header.php");?>
<?php if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
if(isset($_POST['do'])){
  require_once("../include/check_post_key.php");
  $user_id=mysqli_real_escape_string($mysqli,$_POST['user_id']);
  $rightstr =$_POST['rightstr'];
  $sql="insert into `privilege` values('$user_id','$rightstr','N')";
  mysqli_query($mysqli,$sql);
  if (mysqli_affected_rows($mysqli)==1) echo "$user_id $rightstr added!";
  else echo "No such user!";
}
?>
<body style='padding:10'>
<center><h2>添加用户管理权限</h2></center>
<form method=post>
<?php require("../include/set_post_key.php");?>
  用户ID:<input type=text size=10 name="user_id"><br />
  权限:
  <select name="rightstr">
<?php
$rightarray=array("administrator","teacher","problem_editor","source_browser","test_creator","http_judge","password_setter");
while(list($key, $val)=each($rightarray)) {
  if (isset($rightstr) && ($rightstr == $val)) {
    echo '<option value="'.$val.'" selected>'.$val.'</option>';
  } else {
    echo '<option value="'.$val.'">'.$val.'</option>';
  }
}
?></select><br />
  <input type='hidden' name='do' value='do'>
  <input class="btn btn-success" type=submit value='添加'>
</form>
<center><h2>添加用户考试权限</h2></center>
<form method=post>
  用户ID:<input type=text size=10 name="user_id"><br />
  考试编号:<input type=text size=10 name="rightstr"><br>t1000 for test1000<br />
  <input type='hidden' name='do' value='do'>
  <input class="btn btn-success" type=submit value='添加'>
  <input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
</form>
