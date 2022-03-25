<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require '../../core/inc/security.php';

 $token = filter_input(INPUT_POST, 'tk', FILTER_SANITIZE_STRING);

  if (!$token || $token !== $_SESSION['token']) {
    echo '<p class="error">Error: invalid form submission---</p>';
    // return 405 http status code
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
  }

 if ($_POST) {
   $pdo=new PDO_fun;

   //-- 流量來源項目 --
   if ($_POST['type']=='src_type') {
     $row=$pdo->select("SELECT media, source FROM QRcode_tb WHERE case_id=:case_id AND OnlineOrNot=1",
                        ['case_id'=>$_POST['case_id']]);

     $row_num=count($row);                
      for ($i=0; $i <$row_num ; $i++) { 
        $row[$i]['value']=$row[$i]['source'].' / '.$row[$i]['media'];
      }
     echo json_encode($row);
   }
   //-- post 分析數據 --
   elseif($_POST['type']=='update_an'){


   $case=$pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['case_id']], 'one');

    //-- 歷史紀錄 --
		$pdo->hs_h_location='分析數據管理';
		$pdo->hs_h_action_type='update';
		$pdo->hs_h_title='更新分析數據-'.$case['aTitle'].'｜'.date('Y年m月d日', strtotime($_POST['date']));
		//-- 舊資料 --
		$col_old_arr=[];
    //-- 新資料 --
		$col_new_arr=[];

    


      $where=[
        'date'=>$_POST['date'],
        'case_id'=>$_POST['case_id']
      ];
     //-- 每日人數 --

    //@@ 歷史(舊) @@
    $old=$pdo->select("SELECT * FROM an_user WHERE date=:date AND case_id=:case_id", $where, 'one');
    $od_arr=[ 'tb'=>'每日人數', 'user'=>$old['one_user'] ];
    $col_old_arr=array_merge($col_old_arr, $od_arr);

    $pdo->update('an_user', ['one_user'=>$_POST['one_user']], $where);

    //@@ 歷史(新) @@
    $new=$pdo->select("SELECT * FROM an_user WHERE date=:date AND case_id=:case_id", $where, 'one');
    $new_arr=[ 'tb'=>'每日人數', 'user'=>$new['one_user'] ];
    $col_new_arr=array_merge($col_new_arr, $new_arr);



    //-- 性別 --
    //@@ 歷史(舊) @@
    $od_arr=[ 'tb_sex'=>'性別', 'male'=>0, 'female'=>0 ];
    $old=$pdo->select("SELECT * FROM an_sex WHERE date=:date AND case_id=:case_id", $where);
    foreach ($old as $old_one) {
      if(!empty($old_one['sex_type'])){
        $od_arr[$old_one['sex_type']]=$old_one['one_sex'];
      }
    }
    $col_old_arr=array_merge($col_old_arr, $od_arr);

     $sex_where=$where;
     $sex_arr=['male', 'female'];
     $sex_post_arr=['one_sex_male', 'one_sex_female'];
     $sex_num=count($sex_arr);
     for ($i=0; $i <$sex_num ; $i++) { 
        $sex_where['sex_type']=$sex_arr[$i];
        $pdo->select("DELETE FROM an_sex WHERE date=:date AND case_id=:case_id AND sex_type=:sex_type", $sex_where);
        $sex_where['one_sex']=$_POST[$sex_post_arr[$i]];
        $pdo->insert('an_sex', $sex_where);
     }

    //@@ 歷史(新) @@
    $new_arr=[ 'tb_sex'=>'性別'];
    $new=$pdo->select("SELECT * FROM an_sex WHERE date=:date AND case_id=:case_id", $where);
    foreach ($new as $new_one) {
      if(!empty($new_one['sex_type'])){
        $new_arr[$new_one['sex_type']]=$new_one['one_sex'];
      }
    }
    $col_new_arr=array_merge($col_new_arr, $new_arr);


     //-- 年齡 --
      //@@ 歷史(舊) @@
      $od_arr=[ 'tb_year'=>'年齡', '25-34'=>0, '35-44'=>0, '45-54'=>0, '55-64'=>0, '65+'=>0 ];
      $old=$pdo->select("SELECT * FROM an_years WHERE date=:date AND case_id=:case_id", $where);
      foreach ($old as $old_one) {
        if(!empty($old_one['years_type'])){
          $od_arr[$old_one['years_type']]=$old_one['one_years'];
        }
      }
      $col_old_arr=array_merge($col_old_arr, $od_arr);


     $year_where=$where;
     $year_arr=['25-34', '35-44', '45-54', '55-64', '65+'];
     $year_post_arr=['one_years_25', 'one_years_35', 'one_years_45', 'one_years_55', 'one_years_65'];
     $year_num=count($year_arr);
     for ($i=0; $i <$year_num ; $i++) { 
        $year_where['years_type']=$year_arr[$i];
        $pdo->select("DELETE FROM an_years WHERE date=:date AND case_id=:case_id AND years_type=:years_type", $year_where);
        $year_where['one_years']=$_POST[$year_post_arr[$i]];
        $pdo->insert('an_years', $year_where);
     }

      //@@ 歷史(新) @@
      $new_arr=[ 'tb_year'=>'年齡' ];
      $new=$pdo->select("SELECT * FROM an_years WHERE date=:date AND case_id=:case_id", $where);
      foreach ($new as $new_one) {
        if(!empty($new_one['years_type'])){
          $new_arr[$new_one['years_type']]=$new_one['one_years'];
        }
      }
      $col_new_arr=array_merge($col_new_arr, $new_arr);


     //-- 地區 --
      //@@ 歷史(舊) @@
      $od_arr=[ 'tb_city'=>'地區' ];
      $old=$pdo->select("SELECT * FROM an_city WHERE date=:date AND case_id=:case_id", $where);
      foreach ($old as $old_one) {
        if(!empty($old_one['city_type'])){
          $od_arr[$old_one['city_type']]=$old_one['one_city'];
        }
      }
      $col_old_arr=array_merge($col_old_arr, $od_arr);


     $pdo->select("DELETE FROM an_city WHERE date=:date AND case_id=:case_id ", $where);
     $city_where=$where;
     $city_num=count($_POST['city_type']);
     for ($i=0; $i <$city_num ; $i++) { 
        $city_where['city_type']=$_POST['city_type'][$i];
        $ct_type=$pdo->select("SELECT en_name, tw_name FROM taiwan_area");
        foreach ($ct_type as $ct_type_one) {
          if($ct_type_one['tw_name'] == $city_where['city_type']){
            $city_where['city_type']=$ct_type_one['en_name'].' City';
          }
        }
        $city_where['one_city']=$_POST['one_city'][$i];
        $pdo->insert('an_city', $city_where);
     }

     //@@ 歷史(新) @@
      $new_arr=[ 'tb_city'=>'地區' ];
      $new=$pdo->select("SELECT * FROM an_city WHERE date=:date AND case_id=:case_id", $where);
      foreach ($new as $new_one) {
        if(!empty($new_one['city_type'])){
          $new_arr[$new_one['city_type']]=$new_one['one_city'];
        }
      }
      $col_new_arr=array_merge($col_new_arr, $new_arr);



     //-- 媒體 --
     //@@ 歷史(舊) @@
    $od_arr=[ 'tb_media'=>'媒體', 'desktop'=>0, 'mobile'=>0, 'tablet'=>0 ];
    $old=$pdo->select("SELECT * FROM an_media WHERE date=:date AND case_id=:case_id", $where);
    foreach ($old as $old_one) {
      if(!empty($old_one['media_type'])){
        $od_arr[$old_one['media_type']]=$old_one['one_media'];
      }
    }
    $col_old_arr=array_merge($col_old_arr, $od_arr);


     $media_where=$where;
     $media_arr=['desktop', 'mobile', 'tablet'];
     $media_post_arr=['one_media_desktop', 'one_media_mobile', 'one_media_tablet'];
     $media_num=count($media_arr);
     for ($i=0; $i <$media_num ; $i++) { 
        $media_where['media_type']=$media_arr[$i];
        $pdo->select("DELETE FROM an_media WHERE date=:date AND case_id=:case_id AND media_type=:media_type", $media_where);
        $media_where['one_media']=$_POST[$media_post_arr[$i]];
        $pdo->insert('an_media', $media_where);
     }

     //@@ 歷史(新) @@
    $new_arr=[ 'tb_media'=>'媒體'];
    $new=$pdo->select("SELECT * FROM an_media WHERE date=:date AND case_id=:case_id", $where);
    foreach ($new as $new_one) {
      if(!empty($new_one['media_type'])){
        $new_arr[$new_one['media_type']]=$new_one['one_media'];
      }
    }
    $col_new_arr=array_merge($col_new_arr, $new_arr);



     //-- 使用功能 --
     //@@ 歷史(舊) @@
      $od_arr=[ 'tb_event'=>'使用功能' ];
      $event_num=count($_POST['event_type']);
      for ($i=0; $i <$event_num ; $i++){
        $od_arr[$_POST['event_type'][$i]]=0;
      }
      $old=$pdo->select("SELECT * FROM an_event WHERE date=:date AND case_id=:case_id", $where);
      foreach ($old as $old_one) {
        if(!empty($old_one['event_type'])){
          $od_arr[$old_one['event_type']]=$old_one['one_event'];
        }
      }
      $col_old_arr=array_merge($col_old_arr, $od_arr);


     $pdo->select("DELETE FROM an_event WHERE date=:date AND case_id=:case_id ", $where);
     $event_where=$where;
     $event_num=count($_POST['event_type']);
     for ($i=0; $i <$event_num ; $i++) { 
        $event_where['event_type']=$_POST['event_type'][$i];
        $event_where['one_event']=$_POST['one_event'][$i];
        $pdo->insert('an_event', $event_where);
     }


     //@@ 歷史(新) @@
      $new_arr=[ 'tb_event'=>'使用功能' ];
      $new=$pdo->select("SELECT * FROM an_event WHERE date=:date AND case_id=:case_id", $where);
      foreach ($new as $new_one) {
        if(!empty($new_one['event_type'])){
          $new_arr[$new_one['event_type']]=$new_one['one_event'];
        }
      }
      $col_new_arr=array_merge($col_new_arr, $new_arr);


     //-- 流量來源 --
     //@@ 歷史(舊) @@
      $od_arr=[ 'tb_src'=>'流量來源' ];
      $src_num=count($_POST['src_type']);
      for ($i=0; $i <$src_num ; $i++){
        $od_arr[$_POST['src_type'][$i]]=0;
      }
      $old=$pdo->select("SELECT * FROM an_src WHERE date=:date AND case_id=:case_id", $where);
      foreach ($old as $old_one) {
        if(!empty($old_one['src_type'])){
          $od_arr[$old_one['src_type']]=$old_one['one_src'];
        }
      }
      $col_old_arr=array_merge($col_old_arr, $od_arr);


     $pdo->select("DELETE FROM an_src WHERE date=:date AND case_id=:case_id ", $where);
     $src_where=$where;
     $src_num=count($_POST['src_type']);
     for ($i=0; $i <$src_num ; $i++) { 
        $src_where['src_type']=$_POST['src_type'][$i];
        $src_where['one_src']=$_POST['one_src'][$i];
        $pdo->insert('an_src', $src_where);
     }

     //@@ 歷史(新) @@
      $new_arr=[ 'tb_src'=>'流量來源' ];
      $new=$pdo->select("SELECT * FROM an_src WHERE date=:date AND case_id=:case_id", $where);
      foreach ($new as $new_one) {
        if(!empty($new_one['src_type'])){
          $new_arr[$new_one['src_type']]=$new_one['one_src'];
        }
      }
      $col_new_arr=array_merge($col_new_arr, $new_arr);


     //-- 新增歷史紀錄(自訂) --
     $pdo->add_c_history($col_old_arr, $col_new_arr);


     location_up('manager.php?date='.$_POST['date'].'&case_id='.$_POST['case_id'], '成功更新');
   }
   $pdo->close();
 }
?>