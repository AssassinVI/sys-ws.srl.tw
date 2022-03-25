<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';

if ($_POST) {

	$sel_tb=pdo_select("SELECT Tb_index FROM google_analytics WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']]);
    
    //------------- 無資料 --------------
	if(empty($sel_tb['Tb_index'])){
      	$param=[
             'Tb_index'=>$_POST['Tb_index'],
             'set_time'=>date('Y-m-d H:i:s'),
             'week_user'=>$_POST['week_user'],
             'month_user'=>$_POST['month_user'],
             'total_user'=>$_POST['total_user'],
             'sex'=>$_POST['sex'],
             'years'=>$_POST['years'],
             'media'=>$_POST['media'],
             'event'=>$_POST['event'],
             'src'=>$_POST['src'],
             'month_src'=>$_POST['month_src'],
             'city'=>$_POST['city'],
             'timeOnSite_years'=>$_POST['timeOnSite_years'],
             'user_date'=>$_POST['user_date']
      	];
      	pdo_insert('google_analytics', $param);
	}

	 //------------- 有資料 --------------
	else{

		$param=[
             'set_time'=>date('Y-m-d H:i:s'),
             'week_user'=>$_POST['week_user'],
             'month_user'=>$_POST['month_user'],
             'total_user'=>$_POST['total_user'],
             'sex'=>$_POST['sex'],
             'years'=>$_POST['years'],
             'media'=>$_POST['media'],
             'event'=>$_POST['event'],
             'src'=>$_POST['src'],
             'month_src'=>$_POST['month_src'],
             'city'=>$_POST['city'],
             'timeOnSite_years'=>$_POST['timeOnSite_years'],
             'user_date'=>$_POST['user_date']
      	];
      	pdo_update('google_analytics', $param, ['Tb_index'=>$_POST['Tb_index']]);
	}
	

}
?>