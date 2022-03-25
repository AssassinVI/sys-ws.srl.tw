<?php
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require '../../core/inc/security.php';
 
 if($_SERVER['REQUEST_METHOD']==='POST'){
    $pdo=new PDO_fun;
    $json=[
       'data'=>[],
       'msg'=>[]
    ];

    if($_POST['type']=='an_get'){
        $s_date=empty($_POST['s_date']) ? date('Y-m-d', strtotime('-31 day')):$_POST['s_date'];
        $e_date=empty($_POST['e_date']) ? date('Y-m-d', strtotime('-1 day')):$_POST['e_date'];

        //-- 時間陣列 --
        $StartDate=$s_date;
        $EndDate=$e_date;
        $date_arr=[];
        while (strtotime($StartDate) <= strtotime($EndDate)) {
		  array_push($date_arr, $StartDate);
		  $StartDate=date('Y-m-d', strtotime('+1 day', strtotime($StartDate)));
	    }

        //-- 區間每日人數 --
        $user_arr=[];
        $user_date_arr=[];
        $user=$pdo->select("SELECT date ,one_user FROM an_user WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id", 
                            ['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id']]);

        foreach ($user as $one_user) {
          $user_arr[$one_user['date']]=$one_user['one_user'];
        }
        foreach ($date_arr as $one_date) {
          $user_date=empty($user_arr[$one_date]) ? 0:(int)$user_arr[$one_date];
          array_push($user_date_arr, $user_date);
        }

        //-- 區間每日來信 --
        $user_mail_arr=[];
        $mail_date_arr=[];
        $user_mail=$pdo->select("SELECT date ,one_event FROM an_event WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id AND event_type='預約賞屋'", 
                            ['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id']]);

        foreach ($user_mail as $one_user_mail) {
          $user_mail_arr[$one_user_mail['date']]=$one_user_mail['one_event'];
        }
        foreach ($date_arr as $one_date) {
          $user_date=empty($user_mail_arr[$one_date]) ? 0:(int)$user_mail_arr[$one_date];
          array_push($mail_date_arr, $user_date);
        }


        //-- 區間每日來電 --
        $user_phone_arr=[];
        $phone_date_arr=[];
        $user_phone=$pdo->select("SELECT date ,one_event FROM an_event WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id AND event_type='撥打手機'", 
                            ['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id']]);

        foreach ($user_phone as $one_user_phone) {
          $user_phone_arr[$one_user_phone['date']]=$one_user_phone['one_event'];
        }
        foreach ($date_arr as $one_date) {
          $user_date=empty($user_phone_arr[$one_date]) ? 0:(int)$user_phone_arr[$one_date];
          array_push($phone_date_arr, $user_date);
        }


        //-- 區間每日跳出率 --
        $BounceRate_arr=[];
        $BounceRate_date_arr=[];
        $BounceRate=$pdo->select("SELECT date ,one_bounceRate FROM an_BounceRate WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id", 
                            ['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id']]);

        foreach ($BounceRate as $one_BounceRate) {
          $BounceRate_arr[$one_BounceRate['date']]=$one_BounceRate['one_bounceRate'];
        }
        foreach ($date_arr as $one_date) {
          $user_date=empty($BounceRate_arr[$one_date]) ? 0:round($BounceRate_arr[$one_date],1);
          array_push($BounceRate_date_arr, $user_date);
        }
        
        
        //-- 一週區間人數 --
        $week_user=$pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id GROUP BY case_id", 
                                 ['s_date'=>date('Y-m-d', strtotime('-1 week -1 day')), 'e_date'=>date('Y-m-d', strtotime('-1 day')), 'case_id'=>$_POST['case_id']], 'one');
        //-- 一月區間人數 --
        $month_user=$pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE date BETWEEN :s_date AND :e_date AND case_id=:case_id GROUP BY case_id", 
                                 ['s_date'=>date('Y-m-d', strtotime('-31 day')), 'e_date'=>date('Y-m-d', strtotime('-1 day')), 'case_id'=>$_POST['case_id']], 'one');

        //-- 總人數 --
        $total_user=$pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:case_id GROUP BY case_id", 
                                 ['case_id'=>$_POST['case_id']], 'one');


        $date_sql=empty($_POST['s_date']) ? "":"date BETWEEN :s_date AND :e_date AND";
        $sql_param=['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id']];

        //-- 使用者性別 --
        $sex=$pdo->select("SELECT sex_type, SUM(one_sex) as total 
                           FROM an_sex 
                           WHERE $date_sql case_id=:case_id AND sex_type!=''
                           GROUP BY sex_type", 
                            $sql_param);
        
        //-- 使用者年齡 --
        $years=$pdo->select("SELECT years_type, SUM(one_years) as total 
                           FROM an_years 
                           WHERE $date_sql case_id=:case_id AND years_type!=''
                           GROUP BY years_type", 
                            $sql_param);
        
        //-- 使用者地區 --
        $city=$pdo->select("SELECT SUM(one_city) as total, tw_name
                            FROM an_city as ct
                            inner JOIN taiwan_area as tw ON tw.en_name=REPLACE(ct.city_type, ' City', '')
                            WHERE $date_sql case_id=:case_id
                            GROUP BY city_type
                            ORDER BY SUM(one_city) DESC", 
                            $sql_param);


        //-- 使用者興趣 --
        $interest=$pdo->select("SELECT ai.interest_type, i_tw.tw_name, SUM(ai.one_interest) as total 
                           FROM an_interest as ai
                           LEFT JOIN interest_tw as i_tw ON ai.interest_type=i_tw.en_name
                           WHERE $date_sql ai.case_id=:case_id AND ai.interest_type!=''
                           GROUP BY ai.interest_type
                           ORDER BY SUM(ai.one_interest) DESC, ai.row_id
                           LIMIT 0,7", 
                            $sql_param);


        //-- 使用者興趣互動比率 --
        $interest_br=$pdo->select("SELECT ai.interest_type, i_tw.tw_name, ROUND(AVG(ai.one_bounceRate),1)  as total 
                                FROM an_interest as ai
                                LEFT JOIN interest_tw as i_tw ON ai.interest_type=i_tw.en_name
                                WHERE $date_sql ai.case_id=:case_id AND ai.interest_type!=''
                                GROUP BY ai.interest_type
                                ORDER BY SUM(ai.one_interest) DESC, ai.row_id
                                LIMIT 0,7", 
                                $sql_param);



        //-- 使用者媒體 --
        $media=$pdo->select("SELECT media_type, SUM(one_media) as total 
                           FROM an_media 
                           WHERE $date_sql case_id=:case_id AND media_type!=''
                           GROUP BY media_type", 
                            $sql_param);

        //-- 使用者瀏覽器 --
        $broswer=$pdo->select("SELECT broswer_type, SUM(one_broswer) as total 
                           FROM an_broswer 
                           WHERE $date_sql case_id=:case_id AND broswer_type!=''
                           GROUP BY broswer_type
                           ORDER BY SUM(one_broswer) DESC
                           LIMIT 0,6", 
                           $sql_param);

        //-- 使用的功能 --
        $event=$pdo->select("SELECT event_type, SUM(one_event) as total 
                           FROM an_event 
                           WHERE $date_sql case_id=:case_id AND event_type!=''
                           GROUP BY event_type
                           ORDER BY SUM(one_event) DESC
                           LIMIT 0,5", 
                            $sql_param);


        $src_time_date_sql=empty($_POST['s_date']) ? "":"st.date BETWEEN :s_date AND :e_date AND";
        //-- 一月流量來源 (配合使用時間) --
        $month_src=$pdo->select("SELECT src_type, IF(SUM(one_src)>=5, SUM(one_src), 0) as total
                                  FROM an_src
                                  WHERE case_id=:case_id AND src_type!='' AND date LIKE CONCAT(YEAR(NOW()),'-',DATE_FORMAT(NOW(),'%m'),'%')
                                  GROUP BY src_type
                                  ORDER BY SUM(one_src) DESC
                                  LIMIT 0,5", 
                                ['case_id'=>$_POST['case_id']]);


        //-- 總流量來源 (配合使用時間) --
        $src=$pdo->select("SELECT src_type, IF(SUM(one_src)>=5, SUM(one_src), 0) as total
                           FROM an_src
                           WHERE $date_sql case_id=:case_id AND src_type!=''
                           GROUP BY src_type
                           ORDER BY SUM(one_src) DESC
                           LIMIT 0,5", 
                            $sql_param);


        //-- 總來源使用時間 --
        $src_time=$pdo->select("SELECT src_type, AVG( one_time) as avg_time
                                FROM an_src_time 
                                WHERE $date_sql case_id=:case_id AND src_type!=''
                                GROUP BY src_type", 
                                $sql_param);




        //-- 網頁瀏覽程度 --
        
        //  --  總瀏覽人數 --
        $total_arr=[];
        $row_total=$pdo->select("SELECT one_user, date FROM an_completion WHERE $date_sql case_id=:case_id AND anchor_id='' ", $sql_param);
        $date_total=[];
        foreach ($row_total as $one_total) {
            $date_total[$one_total['date']]=$one_total['one_user'];
        }
        foreach ($date_arr as $data_one) {
            if(!empty($date_total[$data_one])){
                array_push($total_arr, (int)$date_total[$data_one]);
            }
            else{
                array_push($total_arr, 0);
            }
        }
        //-- 第一張圖 --
        $row_one_img=$pdo->select("SELECT com_img FROM an_completion_img WHERE case_id=:case_id AND anchor_id=''", ['case_id'=>$_POST['case_id']],'one');

        //  --  總瀏覽人數 END --


        //-- 分析資料 --
	    $arr_total=[
                        [
                            'anchor_name'=>'總瀏覽人數', 
                            'users'=>$total_arr, 
                            'case_id'=>$_POST['case_id'], 
                            'anchor_id'=>'', 
                            'com_img'=>$row_one_img['com_img']
                        ]
                    ];
        

       //-- 各區塊人數 --
	   $row=$pdo->select("SELECT a.anchor_name, ac.case_id, ac.anchor_id, aci.com_img
                            FROM an_completion as ac
                            INNER JOIN anchor_tb as a ON a.Tb_index=ac.anchor_id
                            INNER JOIN Related_tb as rt ON rt.fun_id=ac.anchor_id
                            LEFT JOIN an_completion_img as aci ON aci.anchor_id=ac.anchor_id
                            WHERE $date_sql ac.case_id=:case_id
                            GROUP BY ac.anchor_id
                            ORDER BY rt.OrderBy", $sql_param);
        $x=0;
        $sql_param_anchor=$sql_param;
        foreach ($row as $one) {
                $sql_param_anchor['anchor_id']=$one['anchor_id'];
                $one_total=$pdo->select("SELECT one_user, date FROM an_completion WHERE $date_sql case_id=:case_id AND anchor_id=:anchor_id  ", $sql_param_anchor);
                $com_arr=[];
                $date_com=[];
            foreach ($one_total as $one) {
                $date_com[$one['date']]=$one['one_user'];
            }
            foreach ($date_arr as $data_one) {
                if(!empty($date_com[$data_one])){
                    array_push($com_arr, (int)$date_com[$data_one]);
                }
                else{
                    array_push($com_arr, 0);
                }
            }
            $row[$x]['users']=$com_arr;
            $x++;
        }
        //-- 各區塊人數 END --

        array_splice($arr_total,1,0,$row);

        //-- 網頁瀏覽程度 END --


        //-- 預約賞屋來信 --
        $date_mail_sql=empty($_POST['s_date']) ? "":"set_time BETWEEN :s_date AND :e_date AND";
        $row_mail=$pdo->select("SELECT Tb_index, use_name, use_mail, call_content, phone, set_time, is_process, utm_source, remark
                                FROM call_record_tb 
                                WHERE $date_mail_sql case_id=:case_id 
                                ORDER BY set_time DESC", $sql_param);
        



        //-- 新舊訪客/回訪率 --
        $return_visit=$pdo->select("SELECT userType, SUM(one_user) as total 
                            FROM an_userType 
                            WHERE $date_sql case_id=:case_id AND userType!=''
                            GROUP BY userType", 
                            $sql_param);
        

        //--  --

       
        $json['data']=[
           'date'=>$date_arr,
           'user'=>$user_date_arr,
           'user_mail'=>$mail_date_arr,
           'user_phone'=>$phone_date_arr,
           'BounceRate'=>$BounceRate_date_arr,
           'sex'=>$sex,
           'years'=>$years,
           'city'=>$city,
           'interest'=>$interest,
           'interest_br'=>$interest_br,
           'media'=>$media,
           'broswer'=>$broswer,
           'event'=>$event,
           'src'=>$src,
           'src_time'=>$src_time,
           'month_src'=>$month_src,
           'completion'=>$arr_total,
           'mail'=>$row_mail,
           'return_visit'=>$return_visit,
           'week_user'=>$week_user['total'],
           'month_user'=>$month_user['total'],
           'total_user'=>$total_user['total'],
        ];
        

        echo json_encode($json);
    }

    //-- 預約賞屋來信(處理狀態查詢) --
    elseif($_POST['type']=='an_mail'){
      $s_date=empty($_POST['s_date']) ? date('Y-m-d', strtotime('-31 day')):$_POST['s_date'];
      $e_date=empty($_POST['e_date']) ? date('Y-m-d', strtotime('-1 day')):$_POST['e_date'];
      $sql_param=['s_date'=>$s_date, 'e_date'=>$e_date, 'case_id'=>$_POST['case_id'], 'is_process'=> '%'.$_POST['is_process'].'%'];
      $date_mail_sql=empty($_POST['s_date']) ? "":"set_time BETWEEN :s_date AND :e_date AND";
      $row_mail=$pdo->select("SELECT Tb_index, use_name, use_mail, phone, call_content, set_time, is_process, utm_source, remark
                              FROM call_record_tb 
                              WHERE $date_mail_sql case_id=:case_id AND is_process LIKE :is_process
                              ORDER BY set_time DESC", $sql_param);

      $json['data']=$row_mail;
      echo json_encode($json);
    }

    //-- 預約賞屋來信(備註) --
    elseif($_POST['type']=='ed_mail_remark'){
      $pdo->update('call_record_tb', ['remark'=>$_POST['remark']], ['Tb_index'=>$_POST['Tb_index']]);
    }


    $pdo->close();
 }
 else{
     $json['msg']='POST 失敗';
     echo json_encode($json);
 }
?>