<?php
 require '../../../core/inc/config.php';
 require '../../../core/inc/function.php';
 require '../../../core/inc/pdo_fun_calss.php';

 $pdo=new PDO_fun;
 
 $row=$pdo->select("SELECT * FROM build_case WHERE send_mail!='' AND send_week=DAYOFWEEK(NOW())-1", 'no');


 foreach ($row as $one) {

  //-- 判斷是否還有流量 (一週至少超過50) --
  $flow=$pdo->select("SELECT SUM(one_user) as total
                     FROM `an_user` 
                     WHERE case_id=:case_id AND date BETWEEN DATE_SUB(CURDATE(),INTERVAL 8 DAY) AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) 
                     GROUP BY case_id",
                     ['case_id'=>$one['Tb_index']], 'one');


  if($flow['total']>50){

  $StartDate=date('Y-m-d', strtotime('last Sunday -6 day'));
  $EndDate=date('Y-m-d', strtotime('last Sunday '));
  $an_code=aes_encrypt_7($aes_key, $StartDate.','.$EndDate);
  
  //-- 一周瀏覽 --
  $ad_w_users=$pdo->select("SELECT SUM(one_user) as num 
                          FROM an_user 
                          WHERE case_id=:case_id AND date BETWEEN :StartDate AND :EndDate 
                          GROUP BY case_id", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate], 'one');
  

  //-- 一個月瀏覽 --
  $StartDate_m=date('Y-m-d', strtotime('-30 day'));
  $EndDate_m=date('Y-m-d');
  $ad_m_users=$pdo->select("SELECT SUM(one_user) as num 
                          FROM an_user 
                          WHERE case_id=:case_id AND date BETWEEN :StartDate AND :EndDate 
                          GROUP BY case_id", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate_m, 'EndDate'=>$EndDate_m], 'one');

  //-- 總瀏覽 --
  $ad_all_users=$pdo->select("SELECT SUM(one_user) as num 
                              FROM an_user 
                              WHERE case_id=:case_id 
                              GROUP BY case_id", ['case_id'=>$one['Tb_index']], 'one');
  

  //-- 性別 --
  $ad_sex=$pdo->select("SELECT SUM(one_sex) as num , sex_type
                        FROM an_sex 
                        WHERE case_id=:case_id AND sex_type!='' AND date BETWEEN :StartDate AND :EndDate
                        GROUP BY sex_type
                        ORDER BY sex_type", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate]);

  //-- 性別 top --
  $ad_sex_top=$pdo->select("SELECT SUM(one_sex) as num, sex_type
                            FROM an_sex 
                            WHERE case_id=:case_id AND sex_type!='' AND date BETWEEN :StartDate AND :EndDate
                            GROUP BY sex_type
                            ORDER BY num DESC
                            LIMIT 0,1", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate], 'one');
  
  //-- 年齡 --
  $ad_years=$pdo->select("SELECT SUM(one_years) as num , years_type
                          FROM an_years 
                          WHERE case_id=:case_id AND years_type!='' AND date BETWEEN :StartDate AND :EndDate
                          GROUP BY years_type
                          ORDER BY years_type", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate]);
  
   //-- 年齡 top --
  $ad_year_top=$pdo->select("SELECT SUM(one_years) as num, years_type
                            FROM an_years 
                            WHERE case_id=:case_id AND years_type!='' AND date BETWEEN :StartDate AND :EndDate
                            GROUP BY years_type
                            ORDER BY num DESC
                            LIMIT 0,1", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate], 'one');

 

  $body_data = file_get_contents("an_week_mail.html");
  $body_data = str_replace("{case_name}", $one['aTitle'], $body_data);
  $body_data = str_replace("{StartDate}", $StartDate, $body_data);
  $body_data = str_replace("{EndDate}", $EndDate, $body_data);
  $body_data = str_replace("{week_users}", $ad_w_users['num'], $body_data);
  $body_data = str_replace("{month_users}", $ad_m_users['num'], $body_data);
  $body_data = str_replace("{all_users}", $ad_all_users['num'], $body_data);

  $body_data = str_replace("{".$ad_sex_top['sex_type']."}", '<b style="color:red;">'.$ad_sex_top['num'].'人</b>', $body_data);

  $body_data = str_replace("{female}", $ad_sex[0]['num'].'人', $body_data);
  $body_data = str_replace("{male}", $ad_sex[1]['num'].'人', $body_data);

  $body_data = str_replace("{".$ad_year_top['years_type']."}", '<b style="color:red;">'.$ad_year_top['num'].'人</b>', $body_data);

  $body_data = str_replace("{18-24}", $ad_years[0]['num'].'人', $body_data);
  $body_data = str_replace("{25-34}", $ad_years[1]['num'].'人', $body_data);
  $body_data = str_replace("{35-44}", $ad_years[2]['num'].'人', $body_data);
  $body_data = str_replace("{45-54}", $ad_years[3]['num'].'人', $body_data);
  $body_data = str_replace("{55-64}", $ad_years[4]['num'].'人', $body_data);
  $body_data = str_replace("{65+}", $ad_years[5]['num'].'人', $body_data);

  
  $body_data = str_replace("{more_a}", 'http://ws.srl.tw/sys/module/case_analysis/analytics.php?MT_id=site2018040210520640&Tb_index='.$one['Tb_index'].'&an_code='.$an_code, $body_data);

  //-- 流量來源 --
  $ad_src=$pdo->select("SELECT SUM(one_src) as num , src_type
                          FROM an_src 
                          WHERE case_id=:case_id AND src_type!='' AND date BETWEEN :StartDate AND :EndDate
                          GROUP BY src_type
                          ORDER BY num DESC
                          LIMIT 0,5", ['case_id'=>$one['Tb_index'], 'StartDate'=>$StartDate, 'EndDate'=>$EndDate]);
  
   
   $ad_src_txt='';
   $x=1;
   foreach ($ad_src as $src) {
     
     $name=src_ch($src['src_type']);
     if($name!=false){

      $top_txt=$x==1 ? '<b style="color:red">'.$src['num'].'人</b>':$src['num'].'人';
        $ad_src_txt.='<tr>
                        <td style="font-family: Microsoft JhengHei; font-size: 1.2em; ">'.$name.'</td>
                        <td style="width: 100px; text-align: center; font-size: 1.2em; ">'.$top_txt.'</td>
                     </tr>';
     }

     $x++;
   }

   $body_data = str_replace("{src_an}", $ad_src_txt, $body_data);
   
   $send_mail=explode(',', $one['send_mail']);
   $name_data=$send_mail;
   $adds_data=$send_mail;
   send_Mail($one['aTitle'].'系統', 'server@srl.tw', $one['aTitle'].'-一週分析報告', $body_data, $name_data, $adds_data);


  }

 }





 function src_ch($data_name)
 {
    if(preg_match("/none/i", $data_name)){
       return '直接連結';
    }
    elseif(preg_match("/organic/i", $data_name)){
       $new_name=explode('/', $data_name);
       return $new_name[0].'搜尋';
    }
    elseif(preg_match("/referral/i", $data_name)){
       $new_name=explode('/', $data_name);

       if(preg_match("/m.facebook.com/i", $data_name)){
         return '手機版FB推薦連結';
       }
       elseif(preg_match("/facebook.com/i", $data_name)){
         return '電腦版FB推薦連結';
       }
       elseif(preg_match("/market.ltn.com.tw/i", $data_name)){
         return '自由時報推薦連結';
       }
       elseif(preg_match("/xy168.com.tw/i", $data_name)){
         return '薪曜官網推薦連結';
       }
       elseif(preg_match("/l.facebook.com/i", $data_name)){
         return false;
       }
       elseif(preg_match("/tw.search.yahoo.com/i", $data_name)){
         return false;
       }
       elseif(preg_match("/tpc.googlesyndication.com/i", $data_name)){
         return false;
       }
       elseif(preg_match("/googleads.g.doubleclick.net/i", $data_name)){
         return false;
       }
       else{
         return $new_name[0].'推薦連結';
       }
    }
    elseif(preg_match("/Campaigns/i", $data_name)){
      $new_name=explode('/', $data_name);
      return $new_name[0].'google廣告';
    }
    elseif(preg_match("/not set/i", $data_name)){
      return false;
    }
    else{
       return $data_name;
    }
 }
 
 $pdo=NULL;
?>