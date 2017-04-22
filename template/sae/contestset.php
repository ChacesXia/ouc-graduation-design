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
      <center>
      <h3>考试列表</h3>
        服务器当前时间:<span id=nowdate></span><br>
        <table class="table table-striped">
            <thead>
              <tr class=toprow>
                <th width=10%>ID</th>
                <th width=40%>考试名</th>
                <th width=30%>状态</th>
                <th width=10%>是否公开</th>
                <th width=10%>发起人</th>
              </tr>
            </thead>
            <tbody>
                <?php 
                $cnt=0;
                foreach($view_contest as $row){
                  echo "<tr class='".($cnt?'oddrow':'evenrow')."'>";
                  foreach($row as $table_cell){
                    echo "<td>";
                    echo "\t".$table_cell;
                    echo "</td>";
                  }
                  echo "</tr>";
                  $cnt=!$cnt;
                }
                ?>
              </tbody>    
            </table>
          </center>
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
  <div id=foot>
    <?php require_once("oj-footer.php");?>

  </div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
