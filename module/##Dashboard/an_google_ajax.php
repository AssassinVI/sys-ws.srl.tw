<?php
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';

 if($_POST){
   
   if($_POST['type']=='date_use'){
      $row=pdo_select("SELECT user_date FROM google_analytics WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']]);
      echo $row['user_date'];
   }
 }
?>