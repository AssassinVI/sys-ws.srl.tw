<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';

 if ($_POST) {

  $test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --
   
   //----------- 自訂CSS ------------

 	if($_POST['type']=='css'){

      	$row=pdo_select("SELECT Tb_index FROM change_css WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']], $test_case_db);
      	if(empty($row['Tb_index'])){
           pdo_insert('change_css', ['Tb_index'=>$_POST['Tb_index'], 'css'=>$_POST['css']], $test_case_db);
      	}
      	else{
      		pdo_update('change_css', ['css'=>$_POST['css']], ['Tb_index'=>$_POST['Tb_index']], $test_case_db);
      	}
 	}
    
    //----------- 更改顏色 ------------

 	elseif($_POST['type']=='color'){
       
        $row=pdo_select("SELECT Tb_index FROM color WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']], $test_case_db);
      	if(empty($row['Tb_index'])){

      	   $param=[
      	   	 'Tb_index'=>$_POST['Tb_index'], 
      	   	 'h1_color'=>$_POST['h1_color'],
      	   	 'h2_color'=>$_POST['h2_color'],
      	   	 'p_color'=>$_POST['p_color'],
      	   	 'marquee'=>$_POST['marquee'],
      	   	 'top_txt'=>$_POST['top_txt'],
      	   	 'top_bar'=>$_POST['top_bar'],
      	   	 'back_color'=>$_POST['back_color']
      	   	];
           pdo_insert('color', $param, $test_case_db);
      	}
      	else{

      		 $param=[ 
      	   	 'h1_color'=>$_POST['h1_color'],
      	   	 'h2_color'=>$_POST['h2_color'],
      	   	 'p_color'=>$_POST['p_color'],
      	   	 'marquee'=>$_POST['marquee'],
      	   	 'top_txt'=>$_POST['top_txt'],
      	   	 'top_bar'=>$_POST['top_bar'],
      	   	 'back_color'=>$_POST['back_color']
      	   	];
      		pdo_update('color', $param, ['Tb_index'=>$_POST['Tb_index']], $test_case_db);
      	}
 	}
 }
?>