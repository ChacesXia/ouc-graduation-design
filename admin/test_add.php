<?php require_once("admin-header.php");?>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Add a test</title>

<?php
  require_once("../include/db_info.inc.php");
  require_once("../lang/$OJ_LANG.php");
  require_once("../include/const.inc.php");

$description="";
 if (isset($_POST['syear'])){
  require_once("../include/check_post_key.php");
  $starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
  $endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
  //  echo $starttime;
  //  echo $endtime;
    $title=$_POST['title'];
    $private=$_POST['private'];
    $password=$_POST['password'];
    $description=$_POST['description'];
    if (get_magic_quotes_gpc ()){
        $title = stripslashes ($title);
        $private = stripslashes ($private);
        $password = stripslashes ($password);
        $description = stripslashes ($description);
    }

    $title=mysqli_real_escape_string($mysqli,$title);
    $private=mysqli_real_escape_string($mysqli,$private);
    $password=mysqli_real_escape_string($mysqli,$password);
    $description=mysqli_real_escape_string($mysqli,$description);
    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t){
      $langmask+=1<<$t;
  } 
$langmask=((1<<count($language_ext))-1)&(~$langmask);
  //echo $langmask;  
  
  $sql="INSERT INTO `test`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`) VALUES('$title','$starttime','$endtime','$private',$langmask,'$description','$password')";
  // echo $sql;
  mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  $cid=mysqli_insert_id($mysqli);
  echo "Add test ".$cid;
  $sql="DELETE FROM `test_problem` WHERE `test_id`=$cid";
  $plist=trim($_POST['cproblem']);
  $pieces = explode(",",$plist );
  if (count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1="INSERT INTO `test_problem`(`test_id`,`problem_id`,`num`) 
      VALUES ('$cid','$pieces[0]',0)";
    for ($i=1;$i<count($pieces);$i++){
      $sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
    }
    //echo $sql_1;
    mysqli_query($mysqli,$sql_1) or die(mysqli_error($mysqli));
    $sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  }
  $sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
  mysqli_query($mysqli,$sql);
  $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','m$cid')";
  mysqli_query($mysqli,$sql);
  $_SESSION["m$cid"]=true;
  $pieces = explode("\n", trim($_POST['ulist']));
  if (count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) 
      VALUES ('".trim($pieces[0])."','c$cid')";
    for ($i=1;$i<count($pieces);$i++)
      $sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
    //echo $sql_1;
    mysqli_query($mysqli,$sql_1) or die(mysqli_error($mysqli));
  }
  echo "<script>window.location.href=\"test_list.php\";</script>";
}
else{
   if(isset($_GET['cid'])){
       $cid=intval($_GET['cid']);
       $sql="select * from test WHERE `test_id`='$cid'";
       $result=mysqli_query($mysqli,$sql);
       $row=mysqli_fetch_object($result);
       $title=$row->title;
       mysqli_free_result($result);
      $plist="";
      $sql="SELECT `problem_id` FROM `test_problem` WHERE `test_id`=$cid ORDER BY `num`";
      $result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
      for ($i=mysqli_num_rows($result);$i>0;$i--){
        $row=mysqli_fetch_row($result);
        $plist=$plist.$row[0];
        if ($i>1) $plist=$plist.',';
      }
      mysqli_free_result($result);
   }
else if(isset($_POST['problem2test'])){
     $plist="";
     //echo $_POST['pid'];
     sort($_POST['pid']);
     foreach($_POST['pid'] as $i){        
      if ($plist) 
        $plist.=','.$i;
      else
        $plist=$i;
     }
}else if(isset($_GET['spid'])){
  require_once("../include/check_get_key.php");
       $spid=intval($_GET['spid']);
     
      $plist="";
      $sql="SELECT `problem_id` FROM `problem` WHERE `problem_id`>=$spid ";
      $result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
      for ($i=mysqli_num_rows($result);$i>0;$i--){
        $row=mysqli_fetch_row($result);
        $plist=$plist.$row[0];
        if ($i>1) $plist=$plist.',';
      }
      mysqli_free_result($result);
}  
  include_once("kindeditor.php") ;
?>
<body style='padding:10'>
  <h2 align=center>添加考试</h2>
  <form method=POST >
  
  <p align=left>考试科目:<input class=input-xxlarge  type=text name=title size=71 value="<?php echo isset($title)?$title:""?>"></p>
  <p align=left>开始时间设置:<br>&nbsp;&nbsp;&nbsp;
  Year:<input  class=input-mini type=text name=syear value=<?php echo date('Y')?> size=4 >
  Month:<input class=input-mini  type=text name=smonth value=<?php echo date('m')?> size=2 >
  Day:<input class=input-mini type=text name=sday size=2 value=<?php echo date('d')?> >&nbsp;
  Hour:<input class=input-mini    type=text name=shour size=2 value=<?php echo date('H')?>>&nbsp;
  Minute:<input class=input-mini    type=text name=sminute value=00 size=2 ></p>
  <p align=left>结束时间设置:<br>&nbsp;&nbsp;&nbsp;
  Year:<input class=input-mini    type=text name=eyear value=<?php echo date('Y')?> size=4 >
  Month:<input class=input-mini    type=text name=emonth value=<?php echo date('m')?> size=2 >
  
  Day:<input class=input-mini  type=text name=eday size=2 value=<?php echo date('d')+(date('H')+4>23?1:0)?>>&nbsp;
  Hour:<input class=input-mini  type=text name=ehour size=2 value=<?php echo (date('H')+4)%24?>>&nbsp;
  Minute:<input class=input-mini  type=text name=eminute value=00 size=2 ></p>
  是否公开:<select name=private><option value=0>Public</option><option value=1>Private</option></select>
  <br >
  进入密码:<input type=text name=password value="">
  所用语言:<select name="lang[]" multiple="multiple" style="height:220px">
  <?php
    $lang_count=count($language_ext);
    $langmask=$OJ_LANGMASK;
    for($i=0;$i<$lang_count;$i++){
       echo "<option value=$i selected>
              ".$language_name[$i]."
       </option>";
    }
  ?>
  </select>
  <?php require_once("../include/set_post_key.php");?>
  <br>试题集编号:<input class=input-xxlarge type=text size=60 name=cproblem value="<?php echo isset($plist)?$plist:""?>"><span style="color: red; font-size: 2;">&nbsp; *用空格分割</span>
  <br>
  <p align=left>描述信息:<br><textarea class=kindeditor rows=13 name=description cols=80></textarea>
  学生学号集合:<textarea name="ulist" rows="20" cols="20"></textarea>
  <br />
  <p>*可以将学生学号从Excel整列复制过来，然后要求他们用学号做UserID注册,就能进入Private的比赛作为作业和测验。
  </p>
  <input class="btn btn-success" type=submit value=Submit name=submit>
  <input class="btn btn-private" type=reset value=Reset name=reset>
  </form>
<?php }
require_once("../oj-footer.php");
?>

