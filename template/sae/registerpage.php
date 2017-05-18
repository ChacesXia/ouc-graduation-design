
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
  <?php require_once("oj-header.php");?>
<div id=main>
  <form action="register.php" method="post">
  <br><br>
  <center>
  <table>
  <tr><td><h3 colspan=2 height=40 width=500><?php echo $MSG_REG_INFO?></h3></td><td><span style="color:red">*号为必填项</span></td></tr>
    <tr><td width=25%><?php echo $MSG_USER_ID?>:</td>
      <td width=75%><input name="user_id" size=20 type=text><span style="color:red">*</span></td>
    </tr>
    <tr><td><?php echo $MSG_NICK?>:</td>
      <td><input name="nick" size=50 type=text></td>
    </tr>
    <tr><td><?php echo $MSG_PASSWORD?>:</td>
      <td><input name="password" size=20 type=password><span style="color:red">*</span></td>
    </tr>
    <tr><td><?php echo $MSG_REPEAT_PASSWORD?>:</td>
      <td><input name="rptpassword" size=20 type=password><span style="color:red">*</span></td>
    </tr>
    <tr><td><?php echo $MSG_SCHOOL?>:</td>
      <td><input name="school" size=30 type=text></td>
    </tr>
    <tr><td><?php echo $MSG_EMAIL?>:</td>
      <td><input name="email" size=30 type=text></td>
    </tr>
    <?php if($OJ_VCODE){?>
    <tr><td><?php echo $MSG_VCODE?>:</td>
      <td><input name="vcode" size=4 type=text><img alt="click to change" src="vcode.php" onclick="this.src='vcode.php?'+Math.random()">*</td>
    </tr>
    <?php }?>
    <tr><td></td>
      <td><input class="btn btn-success" value="注册" name="submit" type="submit">
        &nbsp; &nbsp;
        <input class="btn btn-primary" value="重置" name="reset" type="reset"></td>
    </tr>
  </table></center>
  <br><br>
</form>

<div id=foot>
  <?php require_once("oj-footer.php");?>
</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
