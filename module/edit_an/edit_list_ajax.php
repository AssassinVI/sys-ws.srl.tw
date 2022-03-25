<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require '../../core/inc/ssp.class.php';

 
// DB table to use
$table = 'an_user';
 
// Table's primary key
$primaryKey = 'row_id';

$pdo=new PDO_fun;

$columns =[
   ['db'=>'date', 'dt' => 0],
   ['db'=>'one_user', 'dt' => 1],
   ['db'=>'date', 'dt' => 2,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=['male'=>0, 'female'=>0];
      $an=$pdo->select("SELECT one_sex, sex_type FROM an_sex WHERE date=:date AND case_id=:case_id ", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['sex_type']]= $an_one['one_sex'];
      }
      $show_male_color=empty($an_all['male']) ? '':'label label-success';
      $show_female_color=empty($an_all['female']) ? '':'label label-success';
      $an_txt='<span class="'.$show_male_color.' data_m">男：'.$an_all['male'].'</span>
               <span class="'.$show_female_color.' data_m">女：'.$an_all['female'].'</span>';
      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 3,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=['25-34'=>0, '35-44'=>0, '45-54'=>0, '55-64'=>0, '65+'=>0];
      $an=$pdo->select("SELECT one_years, years_type FROM an_years WHERE date=:date AND case_id=:case_id ", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['years_type']]=$an_one['one_years'];
      }
      
      $show_25_color=empty($an_all['25-34']) ? '':'label label-success';
      $show_35_color=empty($an_all['35-44']) ? '':'label label-success';
      $show_45_color=empty($an_all['45-54']) ? '':'label label-success';
      $show_55_color=empty($an_all['55-64']) ? '':'label label-success';
      $show_65_color=empty($an_all['65+']) ? '':'label label-success';
      
      $an_txt='<span class="'.$show_25_color.' data_m">25-34歲：'.$an_all['25-34'].'</span>
               <span class="'.$show_35_color.' data_m">35-44歲：'.$an_all['35-44'].'</span>
               <span class="'.$show_45_color.' data_m">45-54歲：'.$an_all['45-54'].'</span>
               <span class="'.$show_55_color.' data_m">55-64歲：'.$an_all['55-64'].'</span>
               <span class="'.$show_65_color.' data_m">65+歲：'.$an_all['65+'].'</span>';

      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 4,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=[];
      $an_txt='';
      $an=$pdo->select("SELECT one_city, city_type, tw_name
                        FROM an_city as ct
                        inner JOIN taiwan_area as tw ON tw.en_name=REPLACE(ct.city_type, ' City', '')
                        WHERE date=:date AND case_id=:case_id
                        ORDER BY one_city DESC", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['city_type']]= $an_one['one_city'];
        $an_txt.=' <span class="label label-success data_m">'.$an_one['tw_name'].'：'.$an_one['one_city'].'</span> ';
      }
      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 5,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=['mobile'=>0, 'desktop'=>0, 'tablet'=>0];
      $an=$pdo->select("SELECT one_media, media_type FROM an_media WHERE date=:date AND case_id=:case_id", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['media_type']]= $an_one['one_media'];
      }
      $show_mobile_color=empty($an_all['mobile']) ? '':'label label-success';
      $show_desktop_color=empty($an_all['desktop']) ? '':'label label-success';
      $show_tablet_color=empty($an_all['tablet']) ? '':'label label-success';
      $an_txt='<span class="'.$show_desktop_color.' data_m">桌機：'.$an_all['desktop'].'</span>
               <span class="'.$show_mobile_color.' data_m">手機：'.$an_all['mobile'].'</span>
               <span class="'.$show_tablet_color.' data_m">平板：'.$an_all['tablet'].'</span>';
      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 6,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=[];
      $an_txt='';
      $an=$pdo->select("SELECT one_event, event_type
                        FROM an_event
                        WHERE date=:date AND case_id=:case_id
                        ORDER BY one_event DESC", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['event_type']]= $an_one['one_event'];
        $an_txt.=empty($an_one['one_event']) ? '': ' <span class="label label-success data_m">'.$an_one['event_type'].'：'.$an_one['one_event'].'</span> ';
      }
      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 7,
    'formatter' => function( $d, $row ){
      global $pdo;
      $an_all=[];
      $an_txt='';
      $an=$pdo->select("SELECT one_src, src_type
                        FROM an_src
                        WHERE date=:date AND case_id=:case_id
                        ORDER BY one_src DESC
                        LIMIT 0,10", ['date'=>$d, 'case_id'=>$_POST["case_id"]]);
      foreach ($an as $an_one) { 
        $an_all[$an_one['src_type']]= $an_one['one_src'];
        $an_txt.=empty($an_one['one_src']) ? '': ' <span class="label label-success data_m">'.$an_one['src_type'].'：'.$an_one['one_src'].'</span> ';
      }
      return $an_txt;
    }
   ],
   ['db'=>'date', 'dt' => 8,
    'formatter' => function( $d, $row ){
      return '<a id="search_date_btn" class="btn btn-info btn-sm iframe_box" href="manager.php?date='.$d.'&case_id='.$_POST["case_id"].'"><i class="fa fa-edit"></i> 編輯</a>';
    }
   ],
   
];


// SQL server connection information--> to config.php
$sql_details = $DataTable_sql_conn;

$case_id=empty($_POST["case_id"]) ? " case_id LIKE '%%' " : " case_id = '".$_POST["case_id"]."' ";
$where=$case_id;


echo json_encode(
    SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);

$pdo->close();
?>