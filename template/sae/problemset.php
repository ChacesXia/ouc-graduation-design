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
      <script type="text/javascript" src="include/jquery-latest.js"></script> 
      <script type="text/javascript" src="include/jquery.tablesorter.js"></script> 
      <script type="text/javascript">
        $(document).ready(function() 
        { 
          $("#problemset").tablesorter(); 
        } 
        ); 
      </script>

      <!-- 当前页码显示 -->
      <h3 align='center'>
        <?php
        for ($i=1;$i<=$view_total_page;$i++){
          if ($i>1) echo '&nbsp;';
          if ($i==$page) echo "<span class=red>$i</span>";
          else echo "<a href='problemset.php?page=".$i."'>".$i."</a>";
        }
        ?>
      </h3>
      <center>
        <table>
          <tr align='center' class='evenrow'><td width='5'></td>
            <td width='50%' colspan='1'>
              <form class=form-search action=problem.php>
                Problem ID<input class="input-small search-query" type='text' name='id' size=5 style="height:24px">
                <button class="btn btn-mini" type='submit'  >Go</button></form>
            </td>
            <td width='50%' colspan='1'>
              <form class="form-search">
                <input style="height:24px" type="text" name=search class="input-large search-query">
                <button type="submit" class="btn btn-mini"><?php echo $MSG_SEARCH?></button>
              </form>
            </td>
          </tr>
        </table>

        <table id='problemset' width='90%'class='table table-striped'>
          <thead>
            <tr class='toprow' style="">
              <th width=10><?php echo '已通过↕' ?></th>
              <!-- <th width='15%'><?php echo $MSG_PROBLEM_ID.'↕'?></th> -->
              <th width='15%'><?php echo 'ID↕'?></th>
              <th><?php echo $MSG_TITLE.'↕'?></th>
              <th width='10%'><?php echo '添加时间↕' ?></th>
              <th width='10%'><?php echo '出题老师↕' ?></th>
              <!-- <th style="cursor:hand"  width=60 ><?php echo $MSG_AC.'↕' ?></th> -->
              <th width=60 ><?php echo $MSG_AC.'↕' ?></th>
              <th width=60 ><?php echo $MSG_SUBMIT.'↕' ?></th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $cnt=0;
            foreach($view_problemset as $row){
              echo "<tr class=".($cnt?'oddrow':'evenrow').">";
              foreach($row as $table_cell){
                echo "<td>".$table_cell."</td>";
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
</html>
