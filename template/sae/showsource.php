<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title?></title>
  <!-- <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css"> -->
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>

</head>
<body>
<div id="wrapper">
  <?php require_once("oj-header.php");?>
<div id=main>
  
<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 

<script src='highlight/scripts/shCore.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>

<script language='javascript'> 
  SyntaxHighlighter.config.bloggerMode = false;
  SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
  SyntaxHighlighter.config.source =false;
  SyntaxHighlighter.config.collapse = false;
  SyntaxHighlighter.config.gutter = 1;
  SyntaxHighlighter.all('code');
</script>

<?php
   if ($ok==true){
    if($view_user_id!=$_SESSION['user_id'])
      echo "<a href='mail.php?to_user=$view_user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
    $brush=strtolower($language_name[$slanguage]);
    if ($brush=='pascal') $brush='delphi';
    if ($brush=='obj-c') $brush='c';
    if ($brush=='freebasic') $brush='vb';
    echo "<pre name='code' class=\"brush:".$brush.";\">";
    ob_start();
    echo "/**************************************************************\n";
    echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
    echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
    if ($sresult==4){
      echo "\tTime:".$stime." ms\n";
      echo "\tMemory:".$smemory." kb\n";
    }
    echo "****************************************************************/\n\n";
    $auth=ob_get_contents();
    ob_end_clean();
    // 
    echo htmlentities(str_replace("\n\r","\n",$view_source),ENT_QUOTES,"UTF-8")."\n".$auth."</pre>";
    
  }else{
    echo "I am sorry, You could not view this code!";
  }
?>
<?php
  $solution_id=$_GET['id'];
  $user = $_SESSION['user_id'];
  $sql = "select user_id from problem where problem.problem_id in (
    select solution.problem_id from solution where solution.solution_id =".$solution_id."
    )";
  $result=mysqli_query($mysqli,$sql) or die("Error! ".mysqli_error($mysqli));

  $to_user = mysqli_fetch_object($result)->user_id;
?>
<!-- // 聊天展示界面 -->
<table style="width:50%; margin:auto; border:'6'; clear: both">
  <thead style="background-color: '#417dbb'">
  <tr align=center class='toprow'>
    <td style="text-align:center;">
      与老师聊天窗口
    </td>
    <td style="text-align:center;">
      当前时间：<span id=nowdate></span>
    </td>
  </tr>
</thead>
<tbody>
<?php
  $sql="SELECT * FROM `chatlog` WHERE `solution_id`=".$solution_id ." and (`to_user`=".$user." or `from_user`=".$user.") ORDER BY `time`";
  // echo $sql;
  $chatlist=mysqli_query($mysqli,$sql) or die("Error! ".mysqli_error($mysqli));
  $rows_cnt = mysqli_num_rows($chatlist);
  $cnt=0;
  for ($i=0;$i<$rows_cnt;$i++){
    // mysqli_data_seek($chatlist,$i);
    $row=mysqli_fetch_object($chatlist);
    if($row->from_user==$row->to_user){
      echo "
            <tr>
              <td>自己给自己发消息，有意思吗？</td>
            </tr>
          ";
    }else if($row->from_user==$user){
      echo "
            <tr>
              <td></td>
              <td>".$row->message." @".$row->time."</td>
            </tr>
          ";
    }else if($row->to_user == $user){
      echo "
            <tr>
              <td>".$row->message." @".$row->time."</td>
              <td></td>
            </tr>
          ";
    }else{
      echo "<tr>
              <td>自己给自己发消息，有意思吗？</td>
            </tr>
            ";
    }
  }
?>
</tbody>
</table>

<?php if (isset($_SESSION['user_id'])){?>
  <div>
    <form class="form-horizontal" action="include/chatpost.php" method=post >
      <div class="control-group">
        <div class="input-append">
          <input type=hidden name=solution_id value=<?php echo $solution_id; ?>>
          <input type=hidden name=from_user value=<?php echo $user; ?>>
          <input type=hidden name=to_user value=<?php echo $to_user; ?>>
          <input class="span" type="text" placeholder="message" name=message style="height: inherit;">
          <button class="btn btn-success"  type="submit" style="margin-left: -3;">发送</button>
        </div>
      </div>
    </form>
  </div>
<?php }
?>

  <div id=foot>
    <?php require_once("oj-footer.php");?>
  </div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
{
  var x,h,m,s,n,xingqi,y,mon,d;
  var x = new Date(new Date().getTime()+diff);
  y = x.getYear()+1900;
  if (y>3000) y-=1900;
  mon = x.getMonth()+1;
  d = x.getDate();
  xingqi = x.getDay();
  h=x.getHours();
  m=x.getMinutes();
  s=x.getSeconds();
  
  n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout("clock()",1000);
    } 
    clock();
  </script>
</body>
</html>
