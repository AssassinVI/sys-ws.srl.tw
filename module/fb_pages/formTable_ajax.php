<?php
include "../../core/inc/config.php"; //載入基本設定
include "../../core/inc/function.php"; //載入基本function
include "../../core/inc/pdo_fun_calss.php";
include "../../core/inc/ssp.class.php";


 // DB table to use
    $table = 'appFBpage_form';
 
    // Table's primary key
    $primaryKey = 'rowid';

    $columns = [
        ['db' => 'StartDate'], 
        ['db' => 'form_name'], 
        [
            'db' => 'pageId',
            'formatter'=>function ($d, $row){
                $pdo=new PDO_fun;
                $page=$pdo->select("SELECT pageName FROM appCase WHERE pageId=:pageId", ['pageId'=>$d], 'one');
                return $page['pageName'];
                $pdo->close();
             }
        ],
        ['db' => 'full_name'],
        ['db' => 'phone_number'],
        
        [ 
          'db' => 'form_json',  
          'formatter'=>function ($d, $row){

             $form_arr=json_decode($d, true);
             $form_html="<br>";
             foreach ($form_arr as $key => $value) {
                $form_html.="{$key}：{$value}<br>";
             }

             return $form_html;
             
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

    if(!empty($_POST['form_id'])){
       $where= empty($where) ? " form_id = {$_POST['form_id']} " : $where." AND form_id = {$_POST['form_id']} ";
    }

    // if(isset($_POST['OnLineOrNot'])){
    //    $where= empty($where) ? " OnLineOrNot = ".$_POST['OnLineOrNot']." " : $where." AND OnLineOrNot = ".$_POST['OnLineOrNot']." ";
    // }
    
    

	echo json_encode(
    	SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $where )
	);
