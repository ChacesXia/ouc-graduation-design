<?php
@session_start();
 ini_set("display_errors","Off");
if (!isset($_SESSION['user_id'])){
        $view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
}
$test_id=intval($_GET['cid']);
if (!(isset($_SESSION['m'.$test_id])||isset($_SESSION['administrator']))){
        $view_errors= "<a href=./loginpage.php>No privileges!</a>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
}
header ( "content-type:   application/file" );
                header ( "content-disposition:   attachment;   filename=\"logs-$test_id.txt\"" );
require_once('./include/db_info.inc.php');
$sql="select user_id,problem_id,result,source   from source_code right join
                (select solution_id,problem_id,user_id,result from solution where test_id='".$test_id."' ) S
                on source_code.solution_id=S.solution_id order by S.solution_id";
require_once("./include/const.inc.php");
#echo $sql;
$result=mysqli_query($mysqli,$sql);
while($row=mysqli_fetch_object($result)){
        echo "$row->user_id:Problem".$row->problem_id.":".$judge_result[$row->result];
        echo "\r\n$row->source";
        echo "\r\n------------------------------------------------------\r\n";
}
mysqli_free_result($result);
?>
