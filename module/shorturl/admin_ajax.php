<?php
/*短網址管理列表(Ajax)*/
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';
require '../../core/inc/ssp.class.php';

if ($_POST)
{
	$pdo = new PDO_fun('short');
	$type = $_POST['type'];
	$table_name = 'appShort';
	switch ($type)
	{
		//-- 列出資料 --
		case 'list':
			$dt_tbname = $table_name;
            $dt_pkname = 'Tb_index';
            $dt_conn = $Short_DataTable_sql_conn;
			$dt_columns = array(
				array('db' => $dt_pkname, 'dt' => 0),
				array('db' => 'url_id',
					  'dt' => 1,
					  'formatter' => function($d, $row){
						  
						  return 'https://ucy.tw/'.$d.' '.$copyurl_btn;
					  }
                ),
				array('db' => 'url_id',
					  'dt' => 2,
					  'formatter' => function($d, $row){
						  $copyurl_btn = 
							  '<a class="copyurl_btn" href="javascript:void(0);" title="複製" short_url="https://ucy.tw/'.$d.'"><img src="images/copy-content.png" width="20"  /></a>';
						  return $copyurl_btn;
					  }
                ),
				array('db' => 'aUrl',
					  'dt' => 3,
					  'formatter' => function($d, $row){
						  return $d;
					  }
                ),
				array('db' => 'aTitle',
					  'dt' => 4,
					  'formatter' => function($d, $row){
						  return $d;
					  }
                ),
				array('db' => 'OnlineOrNot',
					  'dt' => 5,
					  'formatter' => function($d, $row){
						  return ($d == '1') ? '上線' : '下線';
					  }
                ),
				array('db' => 'OrderBy',
					  'dt' => 6,
					  'formatter' => function($d, $row){
						  return $d;
					  }
                ),
				array('db' => 'OnLineOrNot',
					  'dt' => 7,
					  'formatter' => function($d, $row){
						  $OnLineOrNot=empty($d) ? '<span class="label label-danger">停用</span>':'<span class="label label-primary">使用中</span>';
						  return $OnLineOrNot;
					  }
                ),
				array('db' => $dt_pkname,
					  'dt' => 8,
					  'formatter' => function($d, $row){
						  
						  $sort_txt = '<input type="hidden" class="sort_in" name="Tb_index[]" value="'.$d.'">';
                            
						  //-- 修改 --
			              $mod_txt = 
                              '<a class="mod_btn" href="javascript:void(0);" Tb_index="'.$d.'">
				                   <button type="button" class="btn btn-rounded btn-info btn-sm" >
					                   <i class="fa fa-pencil-square" aria-hidden="true"></i>
					                   修改
				                   </button>
                               </a>';
						  
						  //-- 刪除 --
                          $del_txt = 
                              '<a class="del_btn" href="javascript:void(0);" Tb_index="'.$d.'">
				                   <button type="button" class="btn btn-rounded btn-warning btn-sm" >
				                       <i class="fa fa-trash" aria-hidden="true"></i>
					                   刪除
				                   </button>
				              </a>';
						  
						  return $sort_txt.$mod_txt.$del_txt;
					  }
                )
			);
			//查詢條件
            $dt_where = " ifnull(url_id, '') != '' ";
			$response = SSP::complex($_POST, $dt_conn, $dt_tbname, $dt_pkname, $dt_columns, $dt_where);
            echo json_encode($response);
			break;
		case 'del':
			$Tb_index = $_POST['Tb_index'];
			$where = array('Tb_index'=>$Tb_index);
			$pdo->delete($table_name, $where);
			$response = array('data'=>'');
			echo json_encode($response);
			break;
		case 'sort':
            $Tb_index = $_POST['Tb_index'];
            $Tb_index_ary = explode('_', $Tb_index);
            for ($i=0; $i<count($Tb_index_ary); $i++)
            {
                $param = array('OrderBy'=>($i+1));
                $where = array('Tb_index'=>$Tb_index_ary[$i]);
                $pdo->update($table_name, $param, $where);
            }
            $response = array('data'=>null);
            echo json_encode($response);
            break;	
		default:
			$response = array('data'=>'');
			echo json_encode($response);
			break;
	}
}
?>