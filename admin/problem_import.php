<?php function writable($path){
  $ret=false;
  $fp=fopen($path."/testifwritable.tst","w");
  $ret=!($fp===false);
  fclose($fp);
  unlink($path."/testifwritable.tst");
  return $ret;
}
require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
   $maxfile=min(ini_get("upload_max_filesize"),ini_get("post_max_size"));
?>
导入 FPS 数据,请确保你的文件小于[<?php echo $maxfile?>]<br>
或者修改php的配置文件[PHP.ini]中upload_max_filesize 和 post_max_size<br>
如果你导入超过10M的文件,请修改php.in中的 [memory_limit] <br>

<?php 
    $show_form=true;
   if(!isset($OJ_SAE)||!$OJ_SAE){
     if(!writable($OJ_DATA)){
       echo "你目前需要将 $OJ_DATA 添加进你的open_basedir中 @ php.ini<br>
          或者你需要执行如下命令:<br>
             <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
          ";
      $show_form=false;
     }
     if(!file_exists("../upload"))mkdir("../upload");
     if(!writable("../upload")){
        
       echo "../upload is not writable, <b>chmod 770</b> to it.<br>";
       $show_form=false;
     }
  }  
  if($show_form){
?>
<br>
<form action='problem_import_xml.php' method=post enctype="multipart/form-data">
  <b>Import Problem:</b><br />
  
  <input type=file name=fps >
  <?php require_once("../include/set_post_key.php");?>
    <input type=submit value='Import'>
</form>
<?php 
     }
?>
<br>
