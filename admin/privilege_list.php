<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
echo "<title>Privilege List</title>"; 
echo "<center><h2>Privilege List</h2></center>";
$sql="select * FROM privilege where rightstr in ('administrator','source_browser','test_creator','http_judge','problem_editor') ";
$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
echo "<center><table class='table table-striped' width=60% border=1>";
echo "<thead><tr><td>user<td>right<td>defunc</tr></thead>";
for (;$row=mysqli_fetch_object($result);){
  echo "<tr>";
  echo "<td>".$row->user_id;
  echo "<td>".$row->rightstr;
//  echo "<td>".$row->start_time;
//  echo "<td>".$row->end_time;
//  echo "<td><a href=test_pr_change.php?cid=$row->test_id>".($row->private=="0"?"Public->Private":"Private->Public")."</a>";
  echo "<td><a href=privilege_delete.php?uid=$row->user_id&rightstr=$row->rightstr&getkey=".$_SESSION['getkey'].">Delete</a>";
//  echo "<td><a href=test_edit.php?cid=$row->test_id>Edit</a>";
//  echo "<td><a href=test_add.php?cid=$row->test_id>Copy</a>";
  echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>
