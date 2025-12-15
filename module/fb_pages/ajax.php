<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
include "../../core/inc/ajax_fun.php";
include "../../core/inc/file_class.php"; // file class
require '../../core/inc/pdo_fun_calss.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $pdo=new PDO_fun;

    switch ($_POST['type']) {

        //-- 更新 --
        case 'update':

          $param=[
              'line_bot_group'=> $_POST['groupId'],
          ];

          $where=['pageId'=>$_POST['pageId']];
          $pdo->update('appFBpage', $param, $where);
          echo json_encode(['success'=>true, 'msg'=>'更新成功!']);
        break;
        
        //-- 查詢 --
        case 'select_one':
          $row=$pdo->select("SELECT * FROM appFBpage WHERE pageId=:pageId", ['pageId'=>$_POST['pageId']], 'one');
         
          echo json_encode(['success'=>true, 'data'=>$row, 'msg'=>'查詢']);
        break;

        //-- 查詢LINE群組 --
        case 'select_line_group':
          $group=$pdo->select("SELECT groupId, groupName 
                               FROM line_msg_bot_group
                               LEFT JOIN appFBpage as ac ON ac.line_bot_group=groupId
                               WHERE ac.rowid IS NULL");
          $use_group=$pdo->select("SELECT groupId, groupName 
                                  FROM line_msg_bot_group
                                  INNER JOIN appFBpage as ac ON ac.line_bot_group=groupId
                                  WHERE ac.pageId = :pageId", ['pageId'=>$_POST['pageId']], 'one');
          
          //-- 合併$group、$use_group --
          if(!empty($use_group)){
            $group[]=$use_group;
          }
          
          if(!empty($group)){
            echo json_encode(['success'=>true, 'data'=>$group, 'msg'=>'查詢']);
          }else{
            echo json_encode(['success'=>false, 'msg'=>'查無資料']);
          }
        break;

        //-- 表單 --
        case 'get_form':
          $form=$pdo->select("SELECT COUNT(*) as total, form_name, form_id FROM appFBpage_form WHERE pageId=:pageId GROUP BY form_id ORDER BY form_id DESC", ['pageId'=>$_POST['pageId']]);
          echo json_encode(['success'=>true, 'data'=>$form, 'msg'=>'獲取表單']);
        break;

        //-- 刪除圖片 --
        case 'delete_img':
          $pdo->update('appFBpage', [$_POST['data_img']=>''], ['Tb_index'=>$_POST['Tb_index']]);
          unlink('../../img/'.$_POST['old_img']);
          echo json_encode(['success'=>true, 'msg'=>'成功刪除圖片'.$_POST['old_img']]);
        break;


        //-- 刪除多圖片 --
        case 'delete_m_img':
          $img_arr=$_POST['old_all_img'];
          $img_index=array_search($_POST['old_m_img'], $img_arr);
          array_splice($img_arr, $img_index, 1);
          $img_arr=implode(',', $img_arr);
          $pdo->update('appFBpage', [$_POST['data_img']=>$img_arr], ['Tb_index'=>$_POST['Tb_index']]);
          unlink('../../img/'.$_POST['old_m_img']);
          echo json_encode(['success'=>true, 'msg'=>'成功刪除圖片']);
        break;


        //-- 刪除多圖片(區塊) --
        case 'delete_mb_img':
          $img_arr=$_POST['old_all_img'];
          $img_index=array_search($_POST['old_b_img'], $img_arr);
          array_splice($img_arr, $img_index, 1);
          $img_arr=implode(',', $img_arr);
          $pdo->update('appFBpage_txt', [$_POST['data_img']=>$img_arr], ['rowid'=>$_POST['rowid']]);
          unlink('../../img/'.$_POST['old_b_img']);
          echo json_encode(['success'=>true, 'msg'=>'成功刪除圖片']);
        break;


        //----- 批次置頂 ------
        case 'batch_top':
          foreach ($_POST['page_id_arr'] as $pages_id) {
            $pdo->update('appFBpage', ['is_top'=>1], ['pageId'=>$pages_id]);
          }
          echo json_encode(['success'=>true, 'msg'=>'完成批次置頂粉專']);
        break;

        //----- 批次取消置頂 ------
        case 'batch_not_top':
          foreach ($_POST['page_id_arr'] as $pages_id) {
            $pdo->update('appFBpage', ['is_top'=>0], ['pageId'=>$pages_id]);
          }
          echo json_encode(['success'=>true, 'msg'=>'完成批次取消置頂粉專']);
        break;
        
        
        default:
           echo json_encode(['success'=>false, 'msg'=>'type error....']);
        break;
    }
}
else{
    echo json_encode(['success'=>false, 'msg'=>'METHOD error....']);
}
?>