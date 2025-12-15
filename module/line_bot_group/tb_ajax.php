<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';
require '../../core/inc/ssp.class.php';


// DB table to use
$table = 'line_msg_bot_group';
 
// Table's primary key
$primaryKey = 'rowid';


$columns=[
  ['db' => 'groupName', 'dt' => 0],
  ['db' => 'groupId', 'dt' => 1],
  ['db' => 'case_id', 'dt' => 2],
  ['db' => 'StartDate', 'dt' => 3],
  ['db' => 'rowid', 'dt' => 4, 
   'formatter' => function($d, $row) {
     return '
     <a href="manager.php?rowid='.$d.'" class="btn btn-success btn-sm">編輯</a>
     <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="del_group('.$d.');">刪除</a>';
  }],
];


// SQL server connection information--> to config.php
$sql_details = $DataTable_sql_conn;

$where="";

echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);

?>