<?php
include "../../core/inc/config.php"; //載入基本設定
include "../../core/inc/function.php"; //載入基本function
include "../../core/inc/pdo_fun_calss.php";
include "../../core/inc/ssp.class.php";


 // DB table to use
    $table = 'appFBpage';
 
    // Table's primary key
    $primaryKey = 'rowid';

    $columns = [
        ['db' => 'pageId',
         'formatter'=> function ($d){
               return '<label class="check_label check_one_page"><input type="checkbox" name="sel_page" value="'.$d.'">勾選</label>';
         }
        ], 
        [
         'db' => 'is_top',
         'formatter'=> function ($d){
              if($d=='1'){
                return '<span class="badge badge-danger">TOP</span>';
              }
              else{
                return '';
              }
         }
        ],
        ['db' => 'pageId'], 
        ['db' => 'pageName'], 
        [
         'db'=>'line_bot_group',
         'formatter'=> function ($d){
            $pdo=new PDO_fun();
            $line_group=$pdo->select("SELECT groupName FROM line_msg_bot_group WHERE groupId=:groupId", ['groupId'=>$d], 'one');
            return $line_group['groupName'];
            $pdo->close();
         }
        ],
        [
            'db' => 'pageId',
            'formatter'=>function ($d, $row){
                return '<a class="btn btn-rounded btn-primary btn-sm" href="form_log_list.php?MT_id='.$_POST['MT_id'].'&pageId='.$d.'" >
                           <i class="fa fa-table" aria-hidden="true"></i> LOG
                        </a>
                        <a class="btn btn-rounded btn-primary btn-sm" href="form_list.php?MT_id='.$_POST['MT_id'].'&pageId='.$d.'" >
                           <i class="fa fa-table" aria-hidden="true"></i> 留名單
                        </a>
                        <a class="btn btn-rounded btn-success btn-sm" href="manager.php?MT_id='.$_POST['MT_id'].'&pageId='.$d.'" >
                           <i class="fa fa-pencil-square" aria-hidden="true"></i> 編輯
                        </a>';
             }
        ],
    ];

    //-- 給予dt 數值 --
    $columns_num=count($columns);
    for ($i=0; $i <$columns_num ; $i++) { 
        $columns[$i]['dt']=$i;
    }

    // SQL server connection information--> to config.php
    $sql_details = DATATABLE_CONN;

    $where="";

    if(!empty($_POST['pageId'])){
       $where= empty($where) ? " pageId = {$_POST['pageId']} " : $where." AND pageId = {$_POST['pageId']} ";
    }


    // if(isset($_POST['OnLineOrNot'])){
    //    $where= empty($where) ? " OnLineOrNot = ".$_POST['OnLineOrNot']." " : $where." AND OnLineOrNot = ".$_POST['OnLineOrNot']." ";
    // }
    
    

	echo json_encode(
    	SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $where )
	);
