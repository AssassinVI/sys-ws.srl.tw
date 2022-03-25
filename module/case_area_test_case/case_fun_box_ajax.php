<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';

 if ($_POST) {
  $test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --
 	
 	//------- 新增功能區塊關聯 ------
 	if($_POST['type']=='insert'){

 	  $Tb_index='re'.date('YmdHis').rand(0,99);

 	  $OrderBy_num=pdo_select("SELECT OrderBy FROM Related_tb WHERE case_id=:case_id ORDER BY OrderBy DESC LIMIT 0,1", ['case_id'=>$_POST['case_id']], $test_case_db);

      $param=[
           'Tb_index'=>$Tb_index,
           'case_id'=>$_POST['case_id'],
           'funbox_id'=>$_POST['funbox_id'],
           'OrderBy'=>(int)$OrderBy_num['OrderBy']+1
      ];
      pdo_insert('Related_tb', $param, $test_case_db);

      echo $Tb_index;
 	}
    //------- 撈取功能區塊關聯 ------
 	elseif($_POST['type']=='select'){
      
      $pdo=pdo_conn($test_case_db);
      $sql=$pdo->prepare("SELECT * FROM Related_tb WHERE case_id=:case_id ORDER BY OrderBy ASC");
      $sql->execute(['case_id'=>$_POST['case_id']]);
      $row=$sql->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($row);
 	}
    //------- 撈取功能區塊 ------
 	elseif($_POST['type']=='sel_funbox'){
      
      $FunBox=pdo_select("SELECT * FROM FunBox WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['funbox_id']], $test_case_db);
      echo json_encode($FunBox);
 	}
  //------- 更新功能區塊排序 ------
 	elseif($_POST['type']=='update'){

       $related_id_array=explode(',', $_POST['related_id_array']);
       
       for ($i=0; $i <count($related_id_array) ; $i++) { 
       	 pdo_update('Related_tb', ['OrderBy'=>($i+1)], ['Tb_index'=>$related_id_array[$i]], $test_case_db);
       	//echo $_POST['related_id_array'][$i].'\n';
       }
 	}
  //------- 刪除功能區塊 ------
  elseif($_POST['type']=='delete'){
    
    $row=pdo_select("SELECT fun_id, funbox_id FROM Related_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['related_id']], $test_case_db);

    if(empty($row['fun_id'])){
       //-- 刪除關聯 --
       pdo_delete('Related_tb', ['Tb_index'=>$_POST['related_id']], $test_case_db);
    }
    else{
       
       //-- 功能資料表 --
       $funBox_row=pdo_select("SELECT fun_tb FROM FunBox WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['funbox_id']], $test_case_db);


       //----------- 刪除功能區塊所有檔案 -------------

       if($funBox_row['fun_tb'=='slideshow_tb']){

          $show_row=pdo_select("SELECT show_img, case_id FROM slideshow_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['fun_id']], $test_case_db);
          $show_img=explode(',', $show_row['show_img']);
          for ($i=0; $i <count($show_img)-1 ; $i++) { 
            unlink('../../../product_html/'.$show_row['case_id'].'/img/'.$show_img[$i]);
          }
       }
       elseif($funBox_row['fun_tb'=='youtube_tb']){

         $you_row=pdo_select("SELECT video_file, case_id FROM youtube_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['fun_id']], $test_case_db);
         unlink('../../../product_html/'.$you_row['case_id'].'/video/'.$you_row['video_file']);

       }

       //-- 刪除關聯 --
       pdo_delete('Related_tb', ['Tb_index'=>$_POST['related_id']], $test_case_db);
       //-- 刪除功能區塊 --
       pdo_delete($funBox_row['fun_tb'], ['Tb_index'=>$row['fun_id']], $test_case_db);
       //-- (未完) --
    }
  }
  // ------------ 錨點名稱 -------------
  elseif($_POST['type']=='anchor_name'){
    $an_name=pdo_select("SELECT anchor_name FROM anchor_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['fun_id']], $test_case_db);
    echo $an_name['anchor_name'];
  }
 }
?>