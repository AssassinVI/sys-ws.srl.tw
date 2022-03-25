<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';
require '../../core/inc/ssp.class.php';


// DB table to use
$table = 'sysHistory';
 
// Table's primary key
$primaryKey = 'rowid';



$columns = array(
    array(
        'db'        => 'h_action_type',
        'dt'        => 0,
        'formatter' => function( $d, $row ) {
            switch ($d) {
                case 'insert':
                  $html='<span class="label label-primary">新增</span>';
                break;
                case 'update':
                  $html='<span class="label label-warning">修改</span>';
                break;
                case 'delete':
                  $html='<span class="label label-danger">刪除</span>';
                break;
                case 'login':
                  $html='<span class="label label-success">登入</span>';
                break;
            }
            return $html;
        }
    ),
    array( 'db' => 'h_location', 'dt' => 1 ),
    array( 'db' => 'h_title', 'dt' => 2 ),
    array( 
        'db' => 'admin_id', 
        'dt' => 3,
        'formatter' => function( $d, $row ){

            //-- 系統管理員 --
            if($d=='admin2016093012400651'){
                $name='系統管理員';
                $Group_name='系統管理員';
            }
            else{
                $pdo=new PDO_fun;
                $mem=$pdo->select("SELECT adm.name, g.Group_name 
                                    FROM sysAdmin as adm
                                    INNER JOIN sysAdminGroup as g ON g.Tb_index=adm.admin_per
                                    WHERE adm.tb_index=:tb_index", ['tb_index'=>$d], 'one');
                $pdo=NULL;  
                $name=$mem['name'];
                $Group_name=$mem['Group_name'];
            }
            
            return $name.' ('.$Group_name.') ';
        }
    ),
    array( 
        'db' => 'StartDate', 
        'dt' => 4,
        'formatter' => function( $d, $row ){
            return date('Y年m月d日 H:i:s', strtotime($d));
        }
    ),
    array(
        'db'        => 'rowid',
        'dt'        => 5,
        'formatter' => function( $d, $row ) {
            return '<a href="detail.php?id='.$d.'"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-database"></i> 詳細</button></a>';
        }
    ),
);


// SQL server connection information--> to config.php
$sql_details = $DataTable_his_sql_conn;

$where="";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);

?>