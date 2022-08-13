<?php
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 
 if($_SERVER['REQUEST_METHOD']==='POST'){

    $pdo=new PDO_fun;
    if($_POST['type']=='del_line_notify'){
       $pdo->update('call_us_tb', ['line_Client_ID'=>'', 'line_Client_Secret'=>'', 'line_notify_token'=>''], ['case_id'=>$_POST['case_id']]);
       echo json_encode(['success'=>true]);
    }
    else{
        echo json_encode(['success'=>false, 'msg'=>'type錯誤!!']);
    }
    $pdo->close();
 }

?>