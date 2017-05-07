<?php
//公共头文件信息
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "教育系OJ系统";

//MAIN

// 新闻部分设置
$view_news="";

$sql=  "SELECT * FROM `news`WHERE `defunct`!='Y' ORDER BY `importance` ASC,`time` DESC LIMIT 10";

$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));

if (!$result){
  $view_news= "<h3>查询失败，无消息!</h3>";
  $view_news.= mysqli_error($mysqli);
}else{
  $view_news.= "<table width=100% margin-top=20px;>";
  while ($row=mysqli_fetch_object($result)){
    $view_news.= "
    <tr >
      <td width=80%>
      <b> •  <a href=./newsdetail.php?news_id=".$row->news_id.">".$row->title." </a></b>
      </td>
      <td>
        <small>".$row->time."</small>
      </td>
    </tr>
    ";
  }
  mysqli_free_result($result);
  $view_news.= "</table>";
}
$view_apc_info="";

// 提交的日期和数量
  $sql=  "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc limit 100";
  $result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
  $chart_data_all= array();
//echo $sql;

  while ($row=mysqli_fetch_array($result)){
    $chart_data_all[$row['md']]=$row['c'];
  }
// 解决的日期和数量
  $sql=  "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc limit 100";
  $result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
  $chart_data_ac= array();
//echo $sql;

  while ($row=mysqli_fetch_array($result)){
    $chart_data_ac[$row['md']]=$row['c'];
  }
  if(function_exists('apc_cache_info')){
    $_apc_cache_info = apc_cache_info(); 
    $view_apc_info =_apc_cache_info;
  }

//Template
  require("template/".$OJ_TEMPLATE."/index.php");
//Common foot 隐藏模块
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
  ?>