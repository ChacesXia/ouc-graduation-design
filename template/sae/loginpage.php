<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
  <div id="wrapper">
    <?php require_once("oj-header.php");?>
    <div id=main class="span8 offset1">
      <form action=login.php method=post>
        <center>
          <table width=480 algin=center>
            <tr>
              <td width=100><?php echo $MSG_USER_ID?>:</td>
              <td width=200><input style="height:24px" name="user_id" type="text" size=20></td>
            </tr>
            <tr>
              <td><?php echo $MSG_PASSWORD?>:</td>
              <td><input name="password" type="password" size=20 style="height:24px"></td>
            </tr>
            <tr>
              <td></td>
              <td><input class="btn btn-success" name="submit" type="submit" size=10 value="登陆"> &nbsp; &nbsp;<a href="lostpassword.php">忘记密码</a> </td>
            </tr>

            <?php if($OJ_VCODE){ ?>
              <tr>
                <td><?php echo $MSG_VCODE?>:</td>
                <td><input name="vcode" size=4 type=text style="height:24px"><img alt="click to change" src=vcode.php onclick="this.src='vcode.php?'+Math.random()">*</td>
              </tr>
            <?php }?>
            
          </table>
        </center>
      </form>

    <div id=foot>
     <?php require_once("oj-footer.php");?>
   </div><!--end foot-->
 </div><!--end main-->
</div><!--end wrapper-->
</body>
</html>

