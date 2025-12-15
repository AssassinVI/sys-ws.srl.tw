<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
// require '../../core/inc/ajax_fun.php';
// include "../../core/inc/file_class.php"; // file class
require '../../core/inc/pdo_fun_calss.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $pdo=new PDO_fun;


    switch ($_POST['type']) {

        //-- 新增 --
        case 'insert':
          $Tb_index='news'.date('YmdHis').rand(10,99);
          $OnLineOrNot=empty($_POST['OnLineOrNot']) ? 0:1;
          $param=[
             'Tb_index'=>$Tb_index,
		         'mt_id'=>$_POST['mt_id'],
              'aTitle_one'=>$_POST['aTitle_one'],
              // 'abstract'=>$_POST['abstract'],
              // 'aTXT'=>$_POST['aTXT'],
		          'StartDate'=>date('Y-m-d'),
		        'OnLineOrNot'=>$OnLineOrNot,
            'update_num'=>date('His')
          ];

         //-- 檔案上傳 --
         if (!empty( $_FILES['aImg']['name'])) { 
              $param['aImg']=file_ajax_upload($Tb_index.'_m', $_FILES['aImg'], 'aImg', 600);
         }

         if (!empty( $_FILES['index_aImg']['name'])) { 
              $param['index_aImg']=file_ajax_upload($Tb_index.'_im', $_FILES['index_aImg'], 'index_aImg', 1500);
          }

          if (!empty( $_FILES['index2_aImg']['name'])) { 
              $param['index2_aImg']=file_ajax_upload($Tb_index.'_im2', $_FILES['index2_aImg'], 'index2_aImg', 900);
          }

          

          //-- banner --
        //  if (!empty( $_FILES['aImg_banner']['name'])) { 
        //       $param['aImg_banner']=file_ajax_upload($Tb_index.'_banner', $_FILES['aImg_banner'], 'aImg_banner', 1800);
        //   }

          $pdo->insert('appNews', $param);
          echo json_encode(['success'=>true, 'msg'=>'新增成功!']);
        break;

        //-- 更新 --
        case 'update':
          $Tb_index=$_POST['Tb_index'];
          $OnLineOrNot=empty($_POST['OnLineOrNot']) ? 0:1;
          $param=[
                'aTitle_one'=>$_POST['aTitle_one'],
                // 'abstract'=>$_POST['abstract'],
                // 'aTXT'=>$_POST['aTXT'],
              'OnLineOrNot'=>$OnLineOrNot,
              'update_num'=>date('His')
         ];

         //-- 檔案上傳 --
         if (!empty( $_FILES['aImg']['name'])) { 
              $param['aImg']=file_ajax_upload($Tb_index.'_m', $_FILES['aImg'], 'aImg', 600);
          }
        
        if (!empty( $_FILES['index_aImg']['name'])) { 
            $param['index_aImg']=file_ajax_upload($Tb_index.'_im', $_FILES['index_aImg'], 'index_aImg', 1500);
        }

        if (!empty( $_FILES['index2_aImg']['name'])) { 
              $param['index2_aImg']=file_ajax_upload($Tb_index.'_im2', $_FILES['index2_aImg'], 'index2_aImg', 900);
        }

          //-- banner --
        // if (!empty( $_FILES['aImg_banner']['name'])) { 
        //       $param['aImg_banner']=file_ajax_upload($Tb_index.'_banner', $_FILES['aImg_banner'], 'aImg_banner', 1800);
        //   }

          $where=['Tb_index'=>$Tb_index];
          $pdo->update('appNews', $param, $where);


          echo json_encode(['success'=>true, 'msg'=>'更新成功!']);
        break;
        
        //-- 查詢 --
        case 'select_one':
          $row=$pdo->select("SELECT * FROM appNews WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']], 'one');

          echo json_encode(['success'=>true, 'data'=>$row, 'msg'=>'查詢']);
        break;

        //-- 刪除圖片 --
        case 'delete_img':
          $pdo->update('appNews', [$_POST['data_img']=>''], ['Tb_index'=>$_POST['Tb_index']]);
          unlink('../../img/'.$_POST['old_img']);
          echo json_encode(['success'=>true, 'msg'=>'成功刪除圖片'.$_POST['old_img']]);
        break;


        //-- 刪除多圖片(區塊) --
        case 'delete_mb_img':
          $img_arr=$_POST['old_all_img'];
          $img_index=array_search($_POST['old_b_img'], $img_arr);
          array_splice($img_arr, $img_index, 1);
          $img_arr=implode(',', $img_arr);
          $pdo->update('appNews_txt', [$_POST['data_img']=>$img_arr], ['rowid'=>$_POST['rowid']]);
          unlink('../../img/'.$_POST['old_b_img']);
          echo json_encode(['success'=>true, 'msg'=>'成功刪除圖片']);
        break;

        //--- 置頂 ---
        case 'is_top':

          if($_POST['is_top']==1){
            $pdo->select("UPDATE appNews SET is_top=0 WHERE is_top=1 AND mt_id=:mt_id", ['mt_id'=>$_POST['mt_id']]);
            $pdo->update('appNews', ['is_top'=>1], ['Tb_index'=>$_POST['Tb_index']]);

          }
          else{
            $pdo->update('appNews', ['is_top'=>0], ['Tb_index'=>$_POST['Tb_index']]);

          }
          echo json_encode(['success'=>true, 'msg'=>'更新置頂!']);

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