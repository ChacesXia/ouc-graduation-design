<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//Common head
$cache_time=2;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "$MSG_STATUS";

require_once("./include/my_func.inc.php");
if(isset($OJ_LANG)){
  require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");

// 模板选择
if($OJ_TEMPLATE!="classic") 
  $judge_color=Array("btn gray","btn btn-info","btn btn-warning","btn btn-warning","btn btn-success","btn btn-danger","btn btn-danger","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-info");

$str2="";
$lock=false;
$lock_time=date("Y-m-d H:i:s",time());
$sql="SELECT * FROM `solution` WHERE problem_id>0 ";
if (isset($_GET['cid'])){
  $cid=intval($_GET['cid']);
  $sql=$sql." AND `test_id`='$cid' and num>=0 ";
  $str2=$str2."&cid=$cid";
  $sql_lock="SELECT `start_time`,`title`,`end_time` FROM `test` WHERE `test_id`='$cid'";
  $result=mysqli_query($mysqli,$sql_lock) or die(mysqli_error($mysqli));
  $rows_cnt=mysqli_num_rows($result);
  $start_time=0;
  $end_time=0;
  if ($rows_cnt>0){
    $row=mysqli_fetch_array($result);
    $start_time=strtotime($row[0]);
    $title=$row[1];
    $end_time=strtotime($row[2]);
  }
  $lock_time=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
  //$lock_time=date("Y-m-d H:i:s",$lock_time);
  $time_sql="";
        //echo $lock.'-'.date("Y-m-d H:i:s",$lock);
  if(time()>$lock_time&&time()<$end_time){
          //$lock_time=date("Y-m-d H:i:s",$lock_time);
          //echo $time_sql;
   $lock=true;
   }else{
     $lock=false;
    }
}else{
  if(isset($_SESSION['administrator'])
    ||isset($_SESSION['source_browser'])
    ||(isset($_SESSION['user_id'])
      &&(isset($_GET['user_id'])&&$_GET['user_id']==$_SESSION['user_id']))
    ){
    if ($_SESSION['user_id']!="guest")
      $sql="SELECT * FROM `solution` WHERE test_id is null ";
  }else{
    $sql="SELECT * FROM `solution` WHERE problem_id>=0 and test_id is null ";
  }
}
$start_first=true;
$order_str=" ORDER BY `solution_id` DESC ";

// check the top arg
if (isset($_GET['top'])){
  $top=strval(intval($_GET['top']));
  if ($top!=-1) $sql=$sql."AND `solution_id`<='".$top."' ";
}
// check the problem arg
$problem_id="";
if (isset($_GET['problem_id'])&&$_GET['problem_id']!=""){

  if(isset($_GET['cid'])){
    $problem_id=htmlentities($_GET['problem_id'],ENT_QUOTES,'UTF-8');
    $num=strpos($PID,$problem_id);
    $sql=$sql."AND `num`='".$num."' ";
    $str2=$str2."&problem_id=".$problem_id;
  }else{
    $problem_id=strval(intval($_GET['problem_id']));
    if ($problem_id!='0'){
      $sql=$sql."AND `problem_id`='".$problem_id."' ";
      $str2=$str2."&problem_id=".$problem_id;
    }
    else $problem_id="";
  }
}
// check the user_id arg
$user_id="";
if (isset($_GET['user_id'])){
  $user_id=trim($_GET['user_id']);
  if (is_valid_user_name($user_id) && $user_id!=""){
    $sql=$sql."AND `user_id`='".$user_id."' ";
    if ($str2!="") $str2=$str2."&";
    $str2=$str2."user_id=".$user_id;
  }else $user_id="";
}
if (isset($_GET['language'])) $language=intval($_GET['language']);
else $language=-1;

if ($language>count($language_ext) || $language<0) $language=-1;
if ($language!=-1){
  $sql=$sql."AND `language`='".strval($language)."' ";
  $str2=$str2."&language=".$language;
}
if (isset($_GET['jresult'])) $result=intval($_GET['jresult']);
else $result=-1;

if ($result>12 || $result<0) $result=-1;
if ($result!=-1&&!$lock){
  $sql=$sql."AND `result`='".strval($result)."' ";
  $str2=$str2."&jresult=".$result;
}

if($OJ_SIM){
  $old=$sql;
  $sql="select * from ($sql order by solution_id desc limit 1000) solution left join `sim` on solution.solution_id=sim.s_id WHERE 1 ";
  if(isset($_GET['showsim'])&&intval($_GET['showsim'])>0){
    $showsim=intval($_GET['showsim']);
    $sql="select * from ($old ) solution 
    left join `sim` on solution.solution_id=sim.s_id WHERE result=4 and sim>=$showsim limit 1000";
    $sql="SELECT * FROM ($sql) `solution`
    left join(select solution_id old_s_id,user_id old_user_id from solution limit 1000) old
    on old.old_s_id=sim_s_id WHERE  old_user_id!=user_id and sim_s_id!=solution_id ";
    $str2.="&showsim=$showsim";
  }
  //$sql=$sql.$order_str." LIMIT 20";
}
$sql=$sql.$order_str." LIMIT 20";
//echo $sql;

if($OJ_MEMCACHE){
  require("./include/memcache.php");
  $result = mysql_query_cache($sql);// or die("Error! ".mysqli_error($mysqli));
  if($result) $rows_cnt=count($result);
  else $rows_cnt=0;
  }else{
  $result = mysqli_query($mysqli,$sql);// or die("Error! ".mysqli_error($mysqli));
  if($result) $rows_cnt=mysqli_num_rows($result);
  else $rows_cnt=0;
  }
$top=$bottom=-1;
$cnt=0;
if ($start_first){
  $row_start=0;
  $row_add=1;
}else{
  $row_start=$rows_cnt-1;
  $row_add=-1;
}
$view_status=Array();
$last=0;
for ($i=0;$i<$rows_cnt;$i++){
  if($OJ_MEMCACHE)
    $row=$result[$i];
  else
    $row=mysqli_fetch_array($result);
        //$view_status[$i]=$row;
  if($i==0&&$row['result']<4) $last=$row['solution_id'];
  
  if ($top==-1) $top=$row['solution_id'];
  $bottom=$row['solution_id'];
  $flag=(!is_running(intval($row['test_id']))) ||
  isset($_SESSION['source_browser']) ||
  isset($_SESSION['administrator']) || 
  (isset($_SESSION['user_id'])&&!strcmp($row['user_id'],$_SESSION['user_id']));
  $cnt=1-$cnt;
  $view_status[$i][0]=$row['solution_id'];
  if ($row['test_id']>0) {
    $view_status[$i][1]= "<a href='testrank.php?cid=".$row['test_id']."&user_id=".$row['user_id']."#".$row['user_id']."'>".$row['user_id']."</a>";
  }else{
    $view_status[$i][1]= "<a href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>";
  }
  if ($row['test_id']>0) {
    $view_status[$i][2]= "<div class=center><a href='problem.php?cid=".$row['test_id']."&pid=".$row['num']."'>";
    if(isset($cid)){
      $view_status[$i][2].= $PID[$row['num']];
    }else{
      $view_status[$i][2].= $row['problem_id'];
    }
    $view_status[$i][2].="</div></a>";
  }else{
    $view_status[$i][2]= "<div class=center><a href='problem.php?id=".$row['problem_id']."'>".$row['problem_id']."</a></div>";
  }
  $view_status[$i][3]="";
  if (intval($row['result'])==11 && ((isset($_SESSION['user_id'])&&$row['user_id']==$_SESSION['user_id']) || isset($_SESSION['source_browser']))){
    $view_status[$i][3].= "<a href='ceinfo.php?sid=".$row['solution_id']."' class='".$judge_color[$row['result']]."'  title='$MSG_Click_Detail'>".$MSG_Compile_Error."</a>";
  }else if ((((intval($row['result'])==5||intval($row['result'])==6)&&$OJ_SHOW_DIFF)||$row['result']==10||$row['result']==13) && ((isset($_SESSION['user_id'])&&$row['user_id']==$_SESSION['user_id']) || isset($_SESSION['source_browser']))){
    $view_status[$i][3].= "<a href='reinfo.php?sid=".$row['solution_id']."' class='".$judge_color[$row['result']]."' title='$MSG_Click_Detail'>".$judge_result[$row['result']]."</a>";
  }else{
    if(!$lock||$lock_time>$row['in_date']||$row['user_id']==$_SESSION['user_id']){
      if($OJ_SIM&&$row['sim']>80&&$row['sim_s_id']!=$row['s_id']) {
        $view_status[$i][3].= "<span class='".$judge_color[$row['result']]."'>*".$judge_result[$row['result']]."</span>";
        if( isset($_SESSION['source_browser'])){
          $view_status[$i][3].= "<a href=comparesource.php?left=".$row['sim_s_id']."&right=".$row['solution_id']."  class='btn btn-info'  target=original>".$row['sim_s_id']."(".$row['sim']."%)</a>";
        }else{
          $view_status[$i][3].= "<span class='btn btn-info'>".$row['sim_s_id']."</span>";
        }
        if(isset($_GET['showsim'])&&isset($row[13])){
          $view_status[$i][3].= "$row[13]";
        }
      }else{
        $view_status[$i][3]= "<span class='".$judge_color[$row['result']]."'>".$judge_result[$row['result']]."</span>";
      }
    }else{
      echo "<td>----";
    }
  }
  if ($row['result']!=4&&isset($row['pass_rate'])&&$row['pass_rate']>0&&$row['pass_rate']<.98)
    $view_status[$i][3].="<span class='btn btn-info'>". (100-$row['pass_rate']*100)."%</span>";
  if(isset($_SESSION['http_judge'])) {
   $view_status[$i][3].="<form class='http_judge_form form-inline' >
   <input type=hidden name=sid value='".$row['solution_id']."'>";
   $view_status[$i][3].="</form>";
 }
 if ($flag){
  if ($row['result']>=4){
    $view_status[$i][4]= "<div id=center class=red>".$row['memory']."</div>";
    $view_status[$i][5]= "<div id=center class=red>".$row['time']."</div>";
            //echo "=========".$row['memory']."========";
  }else{
    $view_status[$i][4]= "---";
    $view_status[$i][5]= "---";
  }
  //echo $row['result'];
  // 查询当前用户是不是管理员 或者 老师
  $user = $_SESSION['user_id'];
  $sql = "select * from privilege where user_id ='$user' and rightstr in ('administrator','teacher')";
  $rrs=mysqli_query($mysqli,$sql);
  $havePrivate=(mysqli_num_rows($rrs)>0);

  // echo $havePrivate."---";
  // !(isset($_SESSION['user_id'])&&strtolower($row['user_id'])==strtolower($_SESSION['user_id']) || isset($_SESSION['source_browser']))
  if ($havePrivate || strtolower($row['user_id'])==strtolower($_SESSION['user_id'])){
    $view_status[$i][6]= "<a target=_blank href=showsource.php?id=".$row['solution_id'].">".$language_name[$row['language']]."</a>";
    if($row["problem_id"]>0){
      if (isset($cid)) {
        $view_status[$i][6].= "/<a target=_self href=\"submitpage.php?cid=".$cid."&pid=".$row['num']."&sid=".$row['solution_id']."\">Edit</a>";
      }else{
        $view_status[$i][6].= "/<a target=_self href=\"submitpage.php?id=".$row['problem_id']."&sid=".$row['solution_id']."\">Edit</a>";
      }
    }
    }else{
      $view_status[$i][6]=$language_name[$row['language']];
    }
    $view_status[$i][7]= $row['code_length']." Byte";
    }else{
      $view_status[$i][4]="----";
      $view_status[$i][5]="----";
      $view_status[$i][6]="----";
      $view_status[$i][7]="----";
  }
  $view_status[$i][8]= $row['score'];
  $view_status[$i][9]= $row['in_date'];
  $view_status[$i][10]= $row['judger'];
}
  if(!$OJ_MEMCACHE && $result)mysqli_free_result($result);
?>

<?php
  /////////////////////////Template
  if (isset($_GET['cid']))
    require("template/".$OJ_TEMPLATE."/teststatus.php");
  else
    require("template/".$OJ_TEMPLATE."/status.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

