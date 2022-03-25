<?php
  require '../../../core/inc/config.php';
  require '../../../core/inc/function.php';

  if($_POST){
    switch ($_POST['type']) {

        case 'insert':
          $param=[
            'Tb_index'=>'t'.date('YmdHis').rand(0,99),
            'case_id'=>$_POST['case_id'],
            'tab_name'=>$_POST['tab_name'],
            'an_StartDate'=>$_POST['an_StartDate'],
            'an_EndDate'=>$_POST['an_EndDate'],
            'com_StartDate'=>$_POST['com_StartDate'],
            'com_EndDate'=>$_POST['com_EndDate']
          ];

          pdo_insert('an_tab', $param);
        break;

        case 'delete':
          
          pdo_delete('an_tab', ['Tb_index'=>$_POST['Tb_index']]);
        break;
        
        default:
            # code...
        break;
    }
  }
?>