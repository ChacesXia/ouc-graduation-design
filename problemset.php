<?php 
$OJ_CACHE_SHARE=false;
$cache_time=60;
require_once('./include/db_info.inc.php');
require_once('./include/cache_start.php');
require_once('./include/setlang.php');
$view_title= "问题集";
$first=1000;
//if($OJ_SAE) $first=1;
$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=100;
$result=mysqli_query($mysqli,$sql);
echo mysqli_error($mysqli);
$row=mysqli_fetch_object($result);
$cnt=intval($row->upid)-$first;
$cnt=$cnt/$page_cnt;

//分页设置
$page="1";
if (isset($_GET['page'])){
  $page=intval($_GET['page']);
  if(isset($_SESSION['user_id'])){
   $sql="update users set volume=$page where user_id='".$_SESSION['user_id']."'";
   mysqli_query($mysqli,$sql);
 }
}else{
  if(isset($_SESSION['user_id'])){
    $sql="select volume from users where user_id='".$_SESSION['user_id']."'";
    $result=@mysqli_query($mysqli,$sql);
    $row=mysqli_fetch_array($result);
    $page=intval($row[0]);
  }
  if(!is_numeric($page)||$page<0)
    $page='1';
}
//end of remember page

$pstart=$first+$page_cnt*intval($page)-$page_cnt;
$pend=$pstart+$page_cnt;

$sub_arr=Array();
// submit
if (isset($_SESSION['user_id'])){
  $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
 //  " AND `problem_id`>='$pstart'".
 // " AND `problem_id`<'$pend'".
  " group by `problem_id`";
  $result=@mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while ($row=mysqli_fetch_array($result))
    $sub_arr[$row[0]]=true;
}
$acc_arr=Array();
// 成功的题目
if (isset($_SESSION['user_id'])){
  $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
     //  " AND `problem_id`>='$pstart'".
     //  " AND `problem_id`<'$pend'".
  " AND `result`=4".
  " group by `problem_id`";
  $result=@mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while ($row=mysqli_fetch_array($result))
    $acc_arr[$row[0]]=true;
}

if(isset($_GET['search'])&&trim($_GET['search'])!=""){
  $search=mysqli_real_escape_string($mysqli,$_GET['search']);
  $filter_sql=" ( title like '%$search%' or source like '%$search%')";
}else{
 $filter_sql="  `problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
}
if (isset($_SESSION['administrator'])){
  $sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted`,`in_date`,`user_id` FROM `problem` WHERE $filter_sql ";
}
else{
  $now=strftime("%Y-%m-%d %H:%M",time());
  $sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted`,`in_date`,`user_id`FROM `problem` "."WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN(
  SELECT `problem_id` FROM `test_problem` WHERE `test_id` IN (
  SELECT `test_id` FROM `test` WHERE 
  (`end_time`>'$now' or private=1) and `defunct`='Y')) ";
}
$sql.="and `defunct`='N' ORDER BY `problem_id`";
// echo $sql;
$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$view_total_page=$cnt+1;
$cnt=0;
$view_problemset=Array();
// i 作为循环参数
$i=0;
while ($row=mysqli_fetch_object($result)){
  $view_problemset[$i]=Array();
  if (isset($sub_arr[$row->problem_id])){
    $view_problemset[$i][0] = isset($acc_arr[$row->problem_id])?"<div class='center'>Y</div>":"<div class='center'>N</div>";
  }else{
    $view_problemset[$i][0]= "<div class=none> </div>";
  }
  $view_problemset[$i][1]="<div class='left'>".$row->problem_id."</div>";
  $view_problemset[$i][2]="<div class='left'><a href='problem.php?id=".$row->problem_id."'>".$row->title."</a></div>";
  $view_problemset[$i][3]="<div class='left'>".mb_substr($row->in_date, 0,10)."</div>";
  $view_problemset[$i][4]="<div class='left'><nobr>".$row->user_id."</nobr></div >";
  $view_problemset[$i][5]="<div class='center'><a href='status.php?problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a></div>";
  $view_problemset[$i][6]="<div class='center'><a href='status.php?problem_id=".$row->problem_id."'>".$row->submit."</a></div>";
  $i++;
}
mysqli_free_result($result);

require("template/".$OJ_TEMPLATE."/problemset.php");
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>
