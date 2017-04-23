<?php
  require_once("oj-header.php");

  echo "<title>发贴</title>";
  if (!isset($_SESSION['user_id'])){
    echo "<a href=../loginpage.php>Please Login First</a>";
    require_once("../oj-footer.php");
    exit(0);
  }
?>

<center>
<div style="width:90%; text-align:left">
<h2 style="margin:0px 10px">创建一个新帖子:<?php if (array_key_exists('cid',$_REQUEST) && $_REQUEST['cid']!='') echo ' For Contest '.$_REQUEST['cid'];?></h2>
<form action="post.php?action=new" method=post>
  <input type=hidden name=cid value="<?php if (array_key_exists('cid',$_REQUEST)) echo $_REQUEST['cid'];?>">
  <div style="margin:0px 10px">问题编号 : </div>
  <div><input name=pid style="border:1px dashed:#8080FF; width:100px; height:30px; font-size:75%;margin:0 10px; padding:2px 10px" value="<?php if(array_key_exists('pid',$_REQUEST)) echo $_REQUEST['pid']; ?>"></div>
  <div style="margin:0px 10px">主题 : </div>
  <div><input name=title style="border:1px dashed:#8080FF; width:700px; height:30px; font-size:75%;margin:0 10px; padding:2px 10px"></div>
  <div style="margin:0px 10px">内容 : </div>
  <div>
    <textarea name=content style="border:1px dashed:#8080FF; width:700px; height:400px; font-size:75%; margin:0 10px; padding:10px;placehold:'aa'">
    </textarea>
  </div>
  <div>
    <input class="btn btn-success" type="submit" style="margin:5px 10px" value="Submit" />
  </div>
</form>
</div>
</center>
<?php require("../template/".$OJ_TEMPLATE."/oj-footer.php");?>
