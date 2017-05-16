<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
  <div id="wrapper">
    <!-- <?php //require_once("test-header.php");?> -->
    <?php require_once("oj-header.php");?>
    <div id=main>
      <script src="include/sortTable.js"></script>
      <center>
      <!-- 考试问题界面 -->
        <div>
          <h3>考试编号<?php echo $view_cid?> - <?php echo $view_title ?></h3>
          <p><?php echo $view_description?></p>
          <!-- <br> -->
          开始时间: <font color=#993399><?php echo $view_start_time?></font>
          结束时间: <font color=#993399><?php echo $view_end_time?></font>
          当前时间: <font color=#993399><span id=nowdate > <?php echo date("Y-m-d H:i:s")?></span></font>
          <br>
          状态:<?php
          if ($now>$end_time) 
            echo "<span class=red>已结束</span>";
          else if ($now<$start_time) 
            echo "<span class=red>未开始</span>";
          else 
            echo "<span class=red>进行中</span>";
          ?>
          <?php
          if ($view_private=='0') 
            echo "<span class=blue>公开</span>";
          else 
            echo "<span class=red>私有</span>"; 
          ?>
          <br>
<!--           [<a href='status.php?cid=<?php echo $view_cid?>'>状态</a>]
          [<a href='testrank.php?cid=<?php echo $view_cid?>'>排名统计</a>] -->
          <!-- [<a href='teststatistics.php?cid=<?php echo $view_cid?>'>Statistics</a>] -->
        </div>
        <!-- 问题列表 -->
        <table id='problemset' class="table table-striped" width='90%'>
          <thead>
            <tr align=center class='toprow'>
              <th width='5'>状态</th>
              <th style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" width=20%>
                <A><?php echo $MSG_PROBLEM_ID?></A>
              </th>
              <th width='50%'><?php echo $MSG_TITLE?></th>
              <th width='10%' style="overflow:hidden"><?php echo $MSG_SOURCE?></th>
              <th style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='5%'><A><?php echo $MSG_AC?></A></th>
              <th style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='5%'><A><?php echo $MSG_SUBMIT?></A></th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $cnt=0;
            foreach($view_problemset as $row){
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

      <div id=foot>
        <?php require_once("oj-footer.php");?>
      </div><!--end foot-->
    </div><!--end main-->
  </div><!--end wrapper-->
</body>
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
</html>
