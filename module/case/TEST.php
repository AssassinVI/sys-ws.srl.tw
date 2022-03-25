<?php
require '../../core/inc/function.php';

if(empty($_FILES['c_img']['name'])){
    echo json_encode(['success'=>false, 'msg'=>'請選擇要匯入的檔案']);
    exit();
 }
 else{
    
    if(test_img($_FILES['c_img']['name'])){
        if($_FILES['c_img']['name']=="blob"){
            $type='webp';
        }
        else{
            $type=pathinfo($_FILES['c_img']['name'], PATHINFO_EXTENSION);
        }
        move_uploaded_file($_FILES['c_img']['tmp_name'], '../../img/tmp/tmp_img_'.date('YmdHis').'.'.$type);
        echo json_encode(['success'=>true]);
    }
    else{
        echo json_encode(['success'=>false, 'msg'=>'上傳檔案錯誤']);
    }
    
 }
?>