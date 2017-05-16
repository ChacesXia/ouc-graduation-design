<?php require("admin-header.php");

if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}?>
<?php if(isset($_POST['do'])){
  require_once("../include/check_post_key.php");
  if (isset($_POST['rjpid'])){
    $rjpid=intval($_POST['rjpid']);
    $sql="UPDATE `solution` SET `result`=1 WHERE `problem_id`=".$rjpid;
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $sql="delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`=".$rjpid.")";
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $url="../status.php?problem_id=".$rjpid;
    echo "Rejudged Problem ".$rjpid;
    echo "<script>location.href='$url';</script>";
  }
  else if (isset($_POST['rjsid'])){
    $rjsid=intval($_POST['rjsid']);
    $sql="UPDATE `solution` SET `result`=1 WHERE `solution_id`=".$rjsid;
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $sql="delete from `sim` WHERE `s_id`=".$rjsid;
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $url="../status.php?top=".($rjsid+1);
    echo "Rejudged Runid ".$rjsid;
    echo "<script>location.href='$url';</script>";
  }else if (isset($_POST['rjcid'])){
    $rjcid=intval($_POST['rjcid']);
    $sql="UPDATE `solution` SET `result`=1 WHERE `test_id`=".$rjcid;
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $url="../status.php?cid=".($rjcid);
    echo "Rejudged test id :".$rjcid;
    echo "<script>location.href='$url';</script>";
  }
  if($OJ_REDIS){
    $redis = new Redis();
    $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
    $sql="select solution_id from solution where result=1 and problem_id>0";
     $result=mysqli_query($mysqli,$sql);
    while ($row=mysqli_fetch_object($result)){
            echo $row->solution_id."\n";
            $redis->lpush($OJ_REDISQNAME,$row->solution_id);
    }
    mysqli_free_result($result);
  }
}
?>
  <center><h2>重判题目</h2></center>
  <ol>
  <li>Problem
  <form action='rejudge.php' method=post>
    <input type=input name='rjpid'>  <input type='hidden' name='do' value='do'>
    <input type=submit value=submit>
    <?php require_once("../include/set_post_key.php");?>
  </form>
  <li>Solution
  <form action='rejudge.php' method=post>
    <input type=input name='rjsid'>  <input type='hidden' name='do' value='do'>
    <input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
    <input type=submit value=submit>
  </form>
  <li>test
  <form action='rejudge.php' method=post>
    <input type=input name='rjcid'>  <input type='hidden' name='do' value='do'>
    <input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
    <input type=submit value=submit>
  </form>
  </ol>
