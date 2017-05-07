<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
?>
<form action='problem_export_xml.php' method=post>
  <b>Export Problem:</b><br />
  from pid:<input type=text size=10 name="start" value=1000>
  to pid:<input type=text size=10 name="end" value=1000><br />
  or in<input type=text size=40 name="in" value=""><br />
  <input type='hidden' name='do' value='do'>
  <input type=submit name=submit value='Export'>
 <input type=submit value='Download'>
 <?php require_once("../include/set_post_key.php");?>
</form>
<h3>
* 如果from-to为空,则in内容工作.<br>
* 如果用了in,from-to将不能使用.<br>
* in中可以使用','来分割问题集,如:[1000,1020].
</h3>