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
      $where=[
        'date'=>$_POST['date'],
        'case_id'=>$_POST['case_id']
      ];
     //-- 每日人數 --
     //-- 歷史紀錄 --
    $new_pdo->hs_tb_name='an_user';
    $new_pdo->hs_old_id='';
    $new_pdo->hs_h_location='每日人數';
    $new_pdo->hs_h_action_type='update';
    $new_pdo->hs_h_title='修改每日人數｜'.$_POST['date'].'｜'.$_POST['case_id'];
      //-- 舊資料 --
    $new_pdo->old_data();

    $pdo->update('an_user', ['one_user'=>$_POST['one_user']], $where);

     //-- 新增歷史紀錄 --
     $new_pdo->hs_new_param=['one_user'=>$_POST['one_user']];
	   $new_pdo->add_history();

     //-- 性別 --
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

     //-- 年齡 --
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

     //-- 地區 --
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

     //-- 媒體 --
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

     //-- 使用功能 --
     $pdo->select("DELETE FROM an_event WHERE date=:date AND case_id=:case_id ", $where);
     $event_where=$where;
     $event_num=count($_POST['event_type']);
     for ($i=0; $i <$event_num ; $i++) { 
        $event_where['event_type']=$_POST['event_type'][$i];
        $event_where['one_event']=$_POST['one_event'][$i];
        $pdo->insert('an_event', $event_where);
     }


     //-- 流量來源 --
     $pdo->select("DELETE FROM an_src WHERE date=:date AND case_id=:case_id ", $where);
     $src_where=$where;
     $src_num=count($_POST['src_type']);
     for ($i=0; $i <$src_num ; $i++) { 
        $src_where['src_type']=$_POST['src_type'][$i];
        $src_where['one_src']=$_POST['one_src'][$i];
        $pdo->insert('an_src', $src_where);
     }


     location_up('manager.php?date='.$_POST['date'].'&case_id='.$_POST['case_id'], '成功更新');
   }
   $pdo->close();
 }
?>