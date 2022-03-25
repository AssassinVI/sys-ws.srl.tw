<?php
 require_once '../../core/inc/config.php';
 require_once '../../core/inc/function.php';
 include "../../core/inc/security.php"; //載入安全設定
 require_once '../../core/inc/pdo_fun_calss.php';

 $token = filter_input(INPUT_POST, 'tk', FILTER_SANITIZE_STRING);

  if (!$token || $token !== $_SESSION['token']) {
    // show an error message
    echo '<p class="error">Error: invalid form submission---</p>';
    // return 405 http status code
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
  }

 if($_POST){

  

   $pdo=new PDO_fun;
   $row_img=$pdo->select("SELECT count(*) as total, com_img FROM an_completion_img WHERE case_id=:case_id AND anchor_id=:anchor_id",
                          ['case_id'=>$_POST['case_id'], 'anchor_id'=>$_POST['anchor_id']], 'one');
   if($row_img['total']=='0'){
     
     $Tb_index='img'.date('YmdHis').rand(0,99);
     $type=explode('.',$_FILES['ch_img']['name']);
     $com_img=$Tb_index.'.'.$type[count($type)-1];
     fire_upload('ch_img', $com_img ,$_POST['case_id']);
     
     $param=[
        'Tb_index'=>$Tb_index,
        'case_id'=>$_POST['case_id'],
        'anchor_id'=>$_POST['anchor_id'],
        'com_img'=>$com_img,
        'set_time'=>date('Y-m-d')
     ];
     $pdo->insert('an_completion_img', $param);
   }
   else{


     fire_upload('ch_img', $row_img['com_img'] ,$_POST['case_id']);
     
    //  $param=['com_img'=>$com_img,];
    //  $where=['case_id'=>$_POST['case_id'],'anchor_id'=>$_POST['anchor_id']];
    //  $pdo->update('an_completion_img', $param, $where);
   }

   echo '更新完成!';
 }
?>