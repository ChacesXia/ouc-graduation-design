<?php require_once("admin-header.php");?>
<?php if (!(isset($_SESSION['administrator'])|| isset($_SESSION['password_setter']) )){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
if(isset($_POST['do'])){
  //echo $_POST['user_id'];
  require_once("../include/check_post_key.php");
  //echo $_POST['passwd'];
  require_once("../include/my_func.inc.php");
  
  $user_id=$_POST['user_id'];
    $passwd =$_POST['passwd'];
    if (get_magic_quotes_gpc ()) {
    $user_id = stripslashes ( $user_id);
    $passwd = stripslashes ( $passwd);
  }
  $user_id=mysqli_real_escape_string($mysqli,$user_id);
  $passwd=pwGen($passwd);
  $sql="update `users` set `password`='$passwd' where `user_id`='$user_id'  and user_id not in( select user_id from privilege where rightstr='administrator') ";
  mysqli_query($mysqli,$sql);
  if (mysqli_affected_rows($mysqli)==1) echo "Password Changed!";
  else echo "No such user! or He/Her is an administrator!";
}
?>
<body style='padding:10'>
<center><h2>更改用户密码</h2></center>
<form action='changepass.php' method=post>
  User:<input type=text size=10 name="user_id"><br />
  Pass:<input type=text size=10 name="passwd"><br />
  <?php require_once("../include/set_post_key.php");?>
  <input type='hidden' name='do' value='do'>
  <input class="btn btn-success" type=submit value='Change'>
</form>
