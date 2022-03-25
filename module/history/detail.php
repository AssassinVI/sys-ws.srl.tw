<?php 
$page_name='系統紀錄';
$path_Arr=array('系統','系統紀錄');
?>
<?php include('../../core/page/header01.php');?>
<!-- FooTable -->
    <link href="css/plugins/footable/footable.core.css" rel="stylesheet">
    <style>
        .ch_color{background-color: #e9ffd9;}
        #history_tb thead tr{background-color: #696969;  color: #fff;}
        #history_tb thead tr th{border: 1px solid #ccc; font-size: 15px;}
        #history_tb tbody tr td{border: 1px solid #ccc; font-size: 15px; word-break: break-all;}
    </style>
<?php include('../../core/page/header02.php');?>

<?php 
//-- 歷史紀錄資料庫物件 --
$pdo_history=$new_pdo->_pdo_conn(DB_HIS_NAME);

$history=$new_pdo->select("SELECT * FROM sysHistory WHERE rowid=:rowid", ['rowid'=>$_GET['id']], 'one', $pdo_history);

switch ($history['h_action_type']) {
    case 'insert':
      $h_action_type='<span class="label label-primary">新增</span>';
    break;
    case 'update':
      $h_action_type='<span class="label label-warning">修改</span>';
    break;
    case 'delete':
      $h_action_type='<span class="label label-danger">刪除</span>';
    break;
}

$StartDate=date('Y年m月d日 H:i:s', strtotime($history['StartDate']));

//-- 項目 --
$h_snapshot_arr=json_decode($history['h_snapshot'], true);

$h_snapshot_key=array_keys($h_snapshot_arr['old']);

$new_pdo->close($pdo_history);
?>

<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
	
	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h3 class="font-bold">系統紀錄快照</h3>
                        </div>
      
                        <div class="ibox-content">
                            
                            <h3 style="float: left;"><?php echo $history['h_title'];?></h3>
                            <h3 style="float: right;"><?php echo '編輯者：'.$history['admin_name'].' ｜ 編輯時間：'.$StartDate;?></h3>

                            <table id="history_tb" class=" table table-stripped" >
                                <thead>
                                <tr>
                                    <th style="width: 150px;">項目</th>
                                    <th style="width: 45%;">舊</th>
                                    <th style="width: 45%;">新</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php 
                                    foreach ($h_snapshot_key as $key_one) {

                                       $ch_class=$h_snapshot_arr['old'][$key_one]!=$h_snapshot_arr['new'][$key_one] ? 'ch_color':'';
                                       
                                       echo '<tr class="'.$ch_class.'">
                                              <td>'.$key_one.'</td>
                                              <td>'.nl2br($h_snapshot_arr['old'][$key_one]).'</td>
                                              <td>'.nl2br($h_snapshot_arr['new'][$key_one]).'</td>
                                             </tr>';
                                    }
                                  ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
        </div>
</div>







          

<?php include('../../core/page/footer01.php');?>

<?php include('../../core/page/footer02.php');?>