<?php 
  require('../include/db_info.inc.php');
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href='../template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
<?php function checktest($MSG_test){
    require_once("../include/db_info.inc.php");
    $sql="SELECT count(*) FROM `test` WHERE `end_time`>NOW() AND `defunct`='N'";
    $result=mysqli_query($mysqli,$sql);
    $row=mysqli_fetch_row($result);
    if (intval($row[0])==0) $retmsg=$MSG_test;
    else $retmsg=$row[0]."<font color=red>&nbsp;$MSG_test</font>";
    mysqli_free_result($result);
    return $retmsg;
  }
  function checkmail(){
    require_once("../include/db_info.inc.php");
    $sql="SELECT count(1) FROM `mail` WHERE 
        new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
    $result=mysqli_query($mysqli,$sql);
    if(!$result) return false;
    $row=mysqli_fetch_row($result);
    $retmsg="<font color=red>(".$row[0].")</font>";
    mysqli_free_result($result);
    return $retmsg;
  }
  
  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
    if(file_exists("../faqs.$OJ_LANG.php")){
      $OJ_FAQ_LINK="faqs.$OJ_LANG.php";
    }
  }else{
    require_once("../lang/en.php");
  }
  

  if($OJ_ONLINE){
    require_once('../include/online.php');
    $on = new online();
  }
?>
</head>
<body>
<div id="wrapper">
<div id=head>
<h2><img id=logo width=160 src=../image/logo.png><span style="color: #417dbb"><?php echo $OJ_NAME?></span></h2>
</div><!--end head-->
<div id=subhead> 
<div id=menu class=navbar>
    <?php $ACTIVE="btn-warning";?>
  <a  class='btn'  href="../<?php echo $OJ_HOME?>"><i class="icon-home"></i>
    <?php echo $MSG_HOME?>            
    </a>
    
    <a  class='btn <?php if ($url==$OJ_BBS.".php") echo " $ACTIVE";?>'  href="../bbs.php">
    <i class="icon-comment"></i><?php echo $MSG_BBS?></a>
    <a  class='btn <?php if ($url=="problemset.php") echo " $ACTIVE";?>' href="../problemset.php">
    <i class="icon-question-sign"></i><?php echo $MSG_PROBLEMS?></a>
        
    <a  class='btn <?php if ($url=="status.php") echo "  $ACTIVE";?>' href="../status.php">
    <i class="icon-check"></i><?php echo $MSG_STATUS?></a>
    
    <a class='btn <?php if ($url=="ranklist.php") echo "  $ACTIVE";?>' href="../ranklist.php">
    <i class="icon-signal"></i><?php echo $MSG_RANKLIST?></a>
    
    <a class='btn <?php if ($url=="test.php") echo "  $ACTIVE";?>'  href="../test.php">
    <i class="icon-fire"></i><?php echo checktest($MSG_test)?></a>
    
<!--     <a class='btn <?php if ($url=="recent-test.php") echo " $ACTIVE";?>' href="../recent-test.php">
    <i class="icon-share"></i><?php echo "$MSG_RECENT_test"?></a> -->
    
    <a class='btn <?php if ($url==(isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php")) echo " $ACTIVE";?>' href="../<?php echo isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php"?>">
                <i class="icon-info-sign"></i><?php echo "$MSG_FAQ"?></a>
    
    <?php if(isset($OJ_DICT)&&$OJ_DICT&&$OJ_LANG=="cn"){?>
            
    <span div class='btn '  style="color:1a5cc8" id="dict_status"></span>
           
  <script src="../include/underlineTranslation.js" type="text/javascript"></script>
            <script type="text/javascript">dictInit();</script>
    <?php }?>
  </div><!--end menu-->
<div id=profile >

<?php if (isset($_SESSION['user_id'])){
        $sid=$_SESSION['user_id'];
        print "<i class=icon-user></i>&nbsp;<a href=../modifypage.php>$MSG_USERINFO
          </a><a href='../userinfo.php?user=$sid'>
        <font>$sid</font></a>&nbsp;";
        $mail=checkmail();
        if ($mail)
          print "<a href=../mail.php>$mail</a>";
        print "<a href=../logout.php>$MSG_LOGOUT</a>";
      }else{
        print "<a href=../loginpage.php>$MSG_LOGIN</a>";
        print "<a href=../registerpage.php>$MSG_REGISTER</a>";
      }
      if (isset($_SESSION['administrator'])||isset($_SESSION['test_creator'])){
        // print "<a href=../admin>$MSG_ADMIN</a>";
      }
    ?>


</div><!--end profile-->
</div><!--end subhead-->
<!-- <div id=broadcast>
<?php echo "<marquee id=broadcast scrollamount=1 direction=up scrolldelay=250>";
  echo "<font color=red>";
  echo file_get_contents($OJ_SAE?"saestor://web/msg.txt":"../admin/msg.txt");
  echo "</font>";
  echo "</marquee>";
?>
</div> -->
 
<!-- <div id=main> -->
