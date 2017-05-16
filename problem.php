<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$now=strftime("%Y-%m-%d %H:%M",time());

if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
else $ucid="";

if(isset($OJ_LANG)){
    require_once("./lang/$OJ_LANG.php");
}

$pr_flag=false;
$co_flag=false;
if (isset($_GET['id'])){
   // practice
    $id=intval($_GET['id']);
  //require("oj-header.php");
    if(!isset($_SESSION['administrator']) && $id!=1000&&!isset($_SESSION['test_creator'])){
        $sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id`    NOT IN (
                SELECT `problem_id` FROM `test_problem` WHERE `test_id` IN(
                SELECT `test_id` FROM `test` WHERE `end_time`>'$now' or `private`='1'))
        ";}
    else{
        $sql="SELECT * FROM `problem` WHERE `problem_id`=$id";}
    $pr_flag=true;
  }else if (isset($_GET['cid']) && isset($_GET['pid'])){
    // test
    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);

    if (!isset($_SESSION['administrator']))
        $sql="SELECT langmask,private,defunct FROM `test` WHERE `defunct`='N' AND `test_id`=$cid AND `start_time`<='$now'";
    else
        $sql="SELECT langmask,private,defunct FROM `test` WHERE `defunct`='N' AND `test_id`=$cid";
    $result=mysqli_query($mysqli,$sql);
    $rows_cnt=mysqli_num_rows($result);
    $row=mysqli_fetch_row($result);
    $test_ok=true;
    if ($row[1] && !isset($_SESSION['c'.$cid])) $test_ok=false;
    if ($row[2]=='Y') $test_ok=false;
    if (isset($_SESSION['administrator'])) $test_ok=true;

    $ok_cnt=$rows_cnt==1;              
    $langmask=$row[0];
    mysqli_free_result($result);
    if ($ok_cnt!=1){
                // not started
        $view_errors=  "No such test!";

        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }else{
        // started
        $sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
        SELECT `problem_id` FROM `test_problem` WHERE `test_id`=$cid AND `num`=$pid
        )";
    }
    // public
    if (!$test_ok){
        $view_errors= "Not Invited!";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    $co_flag=true;
  }else{
      $view_errors=  "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
  }
  $result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($result)!=1){
 $view_errors="";
 if(isset($_GET['id'])){
    $id=intval($_GET['id']);
    mysqli_free_result($result);
    $sql="SELECT  test.`test_id` , test.`title`,test_problem.num FROM `test_problem`,`test` WHERE test.test_id=test_problem.test_id and `problem_id`=$id and defunct='N'  ORDER BY `num`";
             //echo $sql;
    $result=mysqli_query($mysqli,$sql);
    if($i=mysqli_num_rows($result)){
        $view_errors.= "This problem is in test(s) below:<br>";
        for (;$i>0;$i--){
          $row=mysqli_fetch_row($result);
          $view_errors.= "<a href=problem.php?cid=$row[0]&pid=$row[2]>test $row[0]:$row[1]</a><br>";
        }
    }else{
      $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
      $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
    }
  }else{
    $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
    $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
}
require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}else{
    $row=mysqli_fetch_object($result);
    $view_title= $row->title;
}

mysqli_free_result($result);

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/problem.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');

?>

