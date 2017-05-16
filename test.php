 <?php
  $OJ_CACHE_SHARE=!isset($_GET['cid']);
  require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
  require_once('./include/my_func.inc.php');
  require_once('./include/const.inc.php');
  require_once('./include/setlang.php');
    $view_title= $MSG_test;
  function formatTimeLength($length){
  $hour = 0;
  $minute = 0;
  $second = 0;
  $result = '';
  
  if ($length >= 60)
  {
    $second = $length % 60;
    if ($second > 0)
    {
      $result = $second . '秒';
    }
    $length = floor($length / 60);
    if ($length >= 60)
    {
      $minute = $length % 60;
      if ($minute == 0)
      {
        if ($result != '')
        {
          $result = '0分' . $result;
        }
      }
      else
      {
        $result = $minute . '分' . $result;
      }
      $length = floor($length / 60);
      if ($length >= 24)
      {
        $hour = $length % 24;
        if ($hour == 0)
        {
          if ($result != '')
          {
            $result = '0小时' . $result;
          }
        }
        else
        {
          $result = $hour . '小时' . $result;
        }
        $length = floor($length / 24);
        $result = $length . '天' . $result;
      }
      else
      {
        $result = $length . '小时' . $result;
      }
    }
    else
    {
      $result = $length . '分' . $result;
    }
  }
  else
  {
    $result = $length . '秒';
  }
  return $result;
}

  if (isset($_GET['cid'])){
      $cid=intval($_GET['cid']);
      $view_cid=$cid;
            //  print $cid;
      // check test valid
      $sql="SELECT * FROM `test` WHERE `test_id`='$cid' ";
      $result=mysqli_query($mysqli,$sql);
      $rows_cnt=mysqli_num_rows($result);
      $test_ok=true;
      $password="";  
            if(isset($_POST['password'])) $password=$_POST['password'];
      if (get_magic_quotes_gpc ()) {
              $password = stripslashes ( $password);
      }
      if ($rows_cnt==0){
        mysqli_free_result($result);
        $view_title= "考试已经关闭!";
        
      }else{
            $row=mysqli_fetch_object($result);
            $view_private=$row->private;
            if($password!=""&&$password==$row->password) $_SESSION['c'.$cid]=true;
            if ($row->private && !isset($_SESSION['c'.$cid])) $test_ok=false;
            if ($row->defunct=='Y') $test_ok=false;
            if (isset($_SESSION['administrator'])) $test_ok=true;
                                
            $now=time();
            $start_time=strtotime($row->start_time);
            $end_time=strtotime($row->end_time);
            $view_description=$row->description;
            $view_title= $row->title;
            $view_start_time=$row->start_time;
            $view_end_time=$row->end_time;
            if (!isset($_SESSION['administrator']) && $now<$start_time){
                $view_errors=  "<h2>$MSG_PRIVATE_WARNING</h2>";
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
            }
        }
        if (!$test_ok){
                 $view_errors=  "<h2>$MSG_PRIVATE_WARNING <br><a href=testrank.php?cid=$cid>$MSG_WATCH_RANK</a></h2>";
                 $view_errors.=  "<form method=post action='test.php?cid=$cid'>$MSG_test $MSG_PASSWORD:<input class=input-mini type=password name=password><input class=btn type=submit></form>";
            require("template/".$OJ_TEMPLATE."/error.php");
            exit(0);
        }
        $sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source,test_problem.num as pnum
            FROM `test_problem`,`problem`
            WHERE `test_problem`.`problem_id`=`problem`.`problem_id` 
            AND `test_problem`.`test_id`=$cid ORDER BY `test_problem`.`num` 
            ) problem
            left join (select problem_id pid1,count(distinct(user_id)) accepted from solution where result=4 and test_id=$cid group by pid1) p1 on problem.pid=p1.pid1
            left join (select problem_id pid2,count(1) submit from solution where test_id=$cid  group by pid2) p2 on problem.pid=p2.pid2
            order by pnum";
        //AND `problem`.`defunct`='N'
        $result=mysqli_query($mysqli,$sql);
        $view_problemset=Array();
        
        $cnt=0;
        while ($row=mysqli_fetch_object($result)){
            $view_problemset[$cnt][0]="";
            if (isset($_SESSION['user_id'])) 
                $view_problemset[$cnt][0]=check_ac($cid,$cnt);
            $view_problemset[$cnt][1]= "$row->pid Problem &nbsp;".$PID[$cnt];
            $view_problemset[$cnt][2]= "<a href='problem.php?cid=$cid&pid=$cnt'>$row->title</a>";
            $view_problemset[$cnt][3]=$row->source ;
            $view_problemset[$cnt][4]=$row->accepted ;
            $view_problemset[$cnt][5]=$row->submit ;
            $cnt++;
        }
        mysqli_free_result($result);
}else{
  $keyword="";
  if(isset($_POST['keyword'])){
      $keyword=mysqli_real_escape_string($mysqli,$_POST['keyword']);
  }
  //echo "$keyword";
  $mytests="";
  foreach($_SESSION as $key => $value){
      if(($key[0]=='m'||$key[0]=='c')&&intval(substr($key,1))>0){
//      echo substr($key,1)."<br>";
         $mytests.=",".intval(substr($key,1));
      }
  }
  if(strlen($mytests)>0) $mytests=substr($mytests,1);
//  echo "$mytests";
  $wheremy="";
  if(isset($_GET['my'])) $wheremy=" and test_id in ($mytests)";
  $sql="SELECT * FROM `test` WHERE `defunct`='N' ORDER BY `test_id` DESC limit 1000";
  $sql="select *  from test left join (select * from privilege where rightstr like 'm%') p on concat('m',test_id)=rightstr where test.defunct='N' and test.title like '%$keyword%' $wheremy  order by test_id desc limit 1000;";
    $result=mysqli_query($mysqli,$sql);
      
      $view_test=Array();
      $i=0;
      while ($row=mysqli_fetch_object($result)){
        
        $view_test[$i][0]= $row->test_id;
        $view_test[$i][1]= "<a href='test.php?cid=$row->test_id'>$row->title</a>";
        $start_time=strtotime($row->start_time);
        $end_time=strtotime($row->end_time);
        $now=time();
                                
                                
        $length=$end_time-$start_time;
        $left=$end_time-$now;
  // past

  if ($now>$end_time) {
    $view_test[$i][2]= "<span class=green>$MSG_Ended@$row->end_time</span>";
  
  // pending

  }else if ($now<$start_time){
    $view_test[$i][2]= "<span class=blue>$MSG_Start@$row->start_time</span>&nbsp;";
    $view_test[$i][2].= "<span class=green>$MSG_TotalTime".formatTimeLength($length)."</span>";
  // running

  }else{
    $view_test[$i][2]= "<span class=red> $MSG_Running</font>&nbsp;";
    $view_test[$i][2].= "<span class=green> $MSG_LeftTime ".formatTimeLength($left)." </span>";
  }
        $private=intval($row->private);
        if ($private==0)
                                        $view_test[$i][4]= "<span class=blue>$MSG_Public</span>";
                                else
                                        $view_test[$i][5]= "<span class=red>$MSG_Private</span>";
        $view_test[$i][6]=$row->user_id;
        $i++;
      }
      
      mysqli_free_result($result);
}
/////////////////////////Template
if(isset($_GET['cid']))
  require("template/".$OJ_TEMPLATE."/test.php");
else
  require("template/".$OJ_TEMPLATE."/testset.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>
