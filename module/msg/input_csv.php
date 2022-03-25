<?php
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';

$pdo=new PDO_fun;

// $f = fopen("GTOWER名單.csv", "r");
// $x=1;
// while ($data = fgetcsv($f, 1000, ',')) {
  
//   $param=[
//       'Tb_index'=>'car'.date('YmdHis').$x,
//       'case_id'=>'case2021102110412875',
//       'utm_source'=>'google表單',
//       'set_time'=>date('Y-m-d H:i:s', strtotime($data[0])),
//       'use_name'=>iconv('big-5', 'utf-8', $data[1]),
//       'phone'=>'0'.$data[2]
//   ];

//   $pdo->insert('call_record_tb', $param);


//  $x++;
// }

$pdo->close();
?>