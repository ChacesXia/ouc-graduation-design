<?php 
require("admin-header.php");
if(isset($OJ_LANG)){
        require_once("../lang/$OJ_LANG.php");
}
echo "<title>Problem List</title>";
echo "<body style='padding:10'>";
echo "<center><h2>考试列表</h2></center>";
require_once("../include/set_get_key.php");
$sql="SELECT max(`test_id`) as upid, min(`test_id`) as btid  FROM `test`";
$page_cnt=50;
$result=mysqli_query($mysqli,$sql);
echo mysqli_error($mysqli);
$row=mysqli_fetch_object($result);
$base=intval($row->btid);
$cnt=intval($row->upid)-$base;
$cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);
if (isset($_GET['page'])){
        $page=intval($_GET['page']);
}else $page=$cnt;
$pstart=$base+$page_cnt*intval($page-1);
$pend=$pstart+$page_cnt;
for ($i=1;$i<=$cnt;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<span class=red>$i</span>";
        else echo "<a href='test_list.php?page=".$i."'>".$i."</a>";
}
$sql="select `test_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `test` where test_id>=$pstart and test_id <=$pend order by `test_id` desc";
$keyword=$_GET['keyword'];
$keyword=mysqli_real_escape_string($mysqli,$keyword);
if($keyword) $sql="select `test_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `test` where title like '%$keyword%' ";
$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
?>
<form action=test_list.php class=center><input name=keyword><input type=submit value="<?php echo $MSG_SEARCH?>" ></form>


<?php
echo "<center><table class='table table-striped' width=90% border=1>";
echo "<tr><td>testID<td>Title<td>StartTime<td>EndTime<td>Private<td>Status<td>Edit<td>Copy<td>Export<td>Logs";
echo "</tr>";
for (;$row=mysqli_fetch_object($result);){
        echo "<tr>";
        echo "<td>".$row->test_id;
        echo "<td><a href='../test.php?cid=$row->test_id'>".$row->title."</a>";
        echo "<td>".$row->start_time;
        echo "<td>".$row->end_time;
        $cid=$row->test_id;
        if(isset($_SESSION['administrator'])||isset($_SESSION["m$cid"])){
                echo "<td><a href=test_pr_change.php?cid=$row->test_id&getkey=".$_SESSION['getkey'].">".($row->private=="0"?"<span class=green>Public</span>":"<span class=red>Private<span>")."</a>";
                echo "<td><a href=test_df_change.php?cid=$row->test_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span class=green>Available</span>":"<span class=red>Reserved</span>")."</a>";
                echo "<td><a href=test_edit.php?cid=$row->test_id>Edit</a>";
                echo "<td><a href=test_add.php?cid=$row->test_id>Copy</a>";
                if(isset($_SESSION['administrator'])){
                        echo "<td><a href=\"problem_export_xml.php?cid=$row->test_id&getkey=".$_SESSION['getkey']."\">Export</a>";
                }else{
                  echo "<td>";
                }
     echo "<td> <a href=\"../export_test_code.php?cid=$row->test_id&getkey=".$_SESSION['getkey']."\">Logs</a>";
        }else{
                echo "<td colspan=5 align=right><a href=test_add.php?cid=$row->test_id>Copy</a><td>";

        }

        echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>
