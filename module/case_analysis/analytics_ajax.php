<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';

 if ($_POST) {

	 $new_pdo=new PDO_fun;
 	
 	//-- 姓別 --
 	if ($_POST['type']=='sex') {
	  
	   $where=['Tb_index'=>$_POST['Tb_index']];
	   
	   if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT sex_type, SUM(one_sex) as one_sex_num FROM an_sex WHERE case_id=:Tb_index AND sex_type!='' $date_sql GROUP BY sex_type", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['sex_type'].',';
		 $num.=$one['one_sex_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);


	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT sex_type, SUM(one_sex) as one_sex_num FROM an_sex WHERE case_id=:Tb_index AND sex_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY sex_type", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['sex_type'].',';
		    $num.=$com_one['one_sex_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

	   echo $return;
	   //echo json_encode($where);
	 }
	 
	//--- 新訪者/回訪者 ---
    elseif ($_POST['type']=='userType') {
	  
	   $where=['Tb_index'=>$_POST['Tb_index']];
	   
	   if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT userType, SUM(one_user) as one_user_num FROM an_userType WHERE case_id=:Tb_index AND userType!='' $date_sql GROUP BY userType", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['userType'].',';
		 $num.=$one['one_user_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);


	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT userType, SUM(one_user) as one_user_num FROM an_userType WHERE case_id=:Tb_index AND userType!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY userType", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['userType'].',';
		    $num.=$com_one['one_user_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

	   echo $return;
	   //echo json_encode($where);
	 }
	 


	 // -- 回訪次數(人數) --
 	elseif($_POST['type']=='userCount'){
      
	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT count_num, SUM(one_user) as one_user_num 
	                    FROM an_userCount 
						WHERE case_id=:Tb_index AND count_num!='' AND count_num BETWEEN 2 AND 10 $date_sql 
						GROUP BY count_num", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['count_num'].',';
		 $num.=$one['one_user_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT count_num, SUM(one_user) as one_user_num 
		                      FROM an_userCount 
							  WHERE case_id=:Tb_index AND count_num!='' AND count_num BETWEEN 2 AND 10 AND date BETWEEN :com_StartDate AND :com_EndDate 
							  GROUP BY count_num", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['count_num'].',';
		    $num.=$com_one['one_user_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

 	  echo $return;
	 }
	 



	 // -- 自用戶上次訪問網站以來經過的天數(人數) --
 	elseif($_POST['type']=='loyalty'){
      
	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT lastDay, SUM(one_user) as one_user_num 
	                    FROM an_dayLast 
						WHERE case_id=:Tb_index AND lastDay!='' AND lastDay BETWEEN 1 AND 7 $date_sql 
						GROUP BY lastDay", $where);
	   
	   //-- 回訪者人數 --
       $row_total=$new_pdo->select("SELECT SUM(one_user) as one_user_num 
	                          FROM an_userType 
							  WHERE case_id=:Tb_index AND userType='Returning Visitor' $date_sql 
							  GROUP BY userType", $where, 'one');

	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['lastDay'].',';
		 $num.=$one['one_user_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1).'|'.$row_total['one_user_num'];

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT lastDay, SUM(one_user) as one_user_num 
		                      FROM an_dayLast 
							  WHERE case_id=:Tb_index AND lastDay!='' AND lastDay BETWEEN 1 AND 7 AND date BETWEEN :com_StartDate AND :com_EndDate 
							  GROUP BY lastDay", $where);
		 
		 //-- 回訪者人數 --
		 $row_com_total=$new_pdo->select("SELECT  SUM(one_user) as one_user_num 
		                      FROM an_userType 
							  WHERE case_id=:Tb_index AND userType='Returning Visitor' AND date BETWEEN :com_StartDate AND :com_EndDate 
							  GROUP BY userType", $where, 'one');

	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['lastDay'].',';
		    $num.=$com_one['one_user_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1).'|'.$row_com_total['one_user_num'];
	   }

 	  echo $return;
 	}


    // -- 年齡 --
 	elseif($_POST['type']=='old'){
      
	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT years_type, SUM(one_years) as one_years_num FROM an_years WHERE case_id=:Tb_index AND years_type!='' $date_sql GROUP BY years_type", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['years_type'].',';
		 $num.=$one['one_years_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT years_type, SUM(one_years) as one_years_num FROM an_years WHERE case_id=:Tb_index AND years_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY years_type", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['years_type'].',';
		    $num.=$com_one['one_years_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

 	  echo $return;
 	}

 	// -- 使用的媒體 --
 	elseif($_POST['type']=='media'){
      
	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
      if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT media_type, SUM(one_media) as one_media_num FROM an_media WHERE case_id=:Tb_index AND media_type!='' $date_sql GROUP BY media_type ORDER BY one_media_num DESC", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['media_type'].',';
		 $num.=$one['one_media_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);
	

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT media_type, SUM(one_media) as one_media_num FROM an_media WHERE case_id=:Tb_index AND media_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY media_type", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['media_type'].',';
		    $num.=$com_one['one_media_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

	   echo $return;
 	}

 	// -- 使用的功能鈕 --
 	elseif($_POST['type']=='tool_btn'){

	  $where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }
      
	   $row=$new_pdo->select("SELECT event_type, SUM(one_event) as one_event_num FROM an_event WHERE case_id=:Tb_index AND event_type!='' $date_sql GROUP BY event_type ORDER BY SUM(one_event) DESC", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['event_type'].',';
		 $num.=$one['one_event_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);


	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT event_type, SUM(one_event) as one_event_num FROM an_event WHERE case_id=:Tb_index AND event_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY event_type ORDER BY SUM(one_event) DESC", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['event_type'].',';
		    $num.=$com_one['one_event_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

 	  echo $return;
 	}

 	// -- 來源流量 --
 	elseif($_POST['type']=='src_num'){

	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   
	   $row=$new_pdo->select("SELECT src_type, SUM(one_src) as one_src_num 
	                    FROM an_src 
						WHERE case_id=:Tb_index AND src_type!='' $date_sql 
						GROUP BY src_type 
						ORDER BY one_src_num DESC
						", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['src_type'].',';
		 $num.=$one['one_src_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
	 	 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT src_type, SUM(one_src) as one_src_num 
		                      FROM an_src 
							  WHERE case_id=:Tb_index AND src_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate 
							  GROUP BY src_type 
							  ORDER BY one_src_num DESC
							  ", $where);
	     $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=$com_one['src_type'].',';
		    $num.=$com_one['one_src_num'].',';
		 }

		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

 	  echo $return;
 	}

    // -- 月來源流量 --
    elseif($_POST['type']=='month_src_num'){
      
       $where=['Tb_index'=>$_POST['Tb_index']];
	   $row=$new_pdo->select("SELECT src_type, SUM(one_src) as one_src_num 
	   						  FROM an_src 
							  WHERE case_id=:Tb_index AND src_type!='' AND date LIKE CONCAT(YEAR(NOW()),'-',DATE_FORMAT(NOW(),'%m'),'%') 
							  GROUP BY src_type 
							  ORDER BY one_src_num DESC", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['src_type'].',';
		 $num.=$one['one_src_num'].',';
	   }
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);
 	  echo $return;
      }

 	// -- 地區使用人數 --
 	elseif($_POST['type']=='city'){

	  $where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT city_type, SUM(one_city) as one_city_num FROM an_city WHERE case_id=:Tb_index AND city_type!='' $date_sql GROUP BY city_type ORDER BY one_city_num DESC", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['city_type'].',';
		 $num.=$one['one_city_num'].',';
	   }
	   $type=substr($type,0,-1);
	   $num=substr($num,0,-1);
	   
	   $city_name=explode(',', $type);
	   $city_num=explode(',', $num);
	   
	   $total_num=count($city_name);
	   
 	  for ($i=0; $i <$total_num ; $i++) {

 	    $tw_name=explode(' ', $city_name[$i]); 
 	  	
 	  	$taiwan_name=$new_pdo->select("SELECT tw_name FROM taiwan_area WHERE en_name LIKE :en_name LIMIT 0,1", ['en_name'=>'%'.$tw_name[0].'%'], 'one');
 	  	if (!empty($taiwan_name['tw_name']) && $city_num[$i]>10) {
 	  		$city_name[$i]=$taiwan_name['tw_name'];
 	  	}
 	  	else{
 	  		unset($city_name[$i]);
 	  		unset($city_num[$i]);
 	  	}
 	  }

	   $city=implode(',', $city_name).'|'.implode(',', $city_num);
	   

	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		 
		 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

	   $row=$new_pdo->select("SELECT city_type, SUM(one_city) as one_city_num FROM an_city WHERE case_id=:Tb_index AND city_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY city_type ORDER BY one_city_num DESC", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['city_type'].',';
		 $num.=$one['one_city_num'].',';
	   }
	   $type=substr($type,0,-1);
	   $num=substr($num,0,-1);
	   
	   $city_name=explode(',', $type);
	   $city_num=explode(',', $num);
	   
	   $total_num=count($city_name);
	   
 	  for ($i=0; $i <$total_num ; $i++) {

 	    $tw_name=explode(' ', $city_name[$i]); 
 	  	
 	  	$taiwan_name=$new_pdo->select("SELECT tw_name FROM taiwan_area WHERE en_name LIKE :en_name LIMIT 0,1", ['en_name'=>'%'.$tw_name[0].'%'],'one');
 	  	if (!empty($taiwan_name['tw_name']) && $city_num[$i]>10) {
 	  		$city_name[$i]=$taiwan_name['tw_name'];
 	  	}
 	  	else{
 	  		unset($city_name[$i]);
 	  		unset($city_num[$i]);
 	  	}
 	  }

	   $city=$city.'|'.implode(',', $city_name).'|'.implode(',', $city_num);
 
	   }

 	  echo $city;
 	}

 	// -- 齡層平均停留網站時間 --
 	elseif($_POST['type']=='timeOnSite'){
      
	  $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   
	   $row=$new_pdo->select("SELECT years_type, round(AVG(one_timeOnSite),2) as one_timeOnSite_num FROM an_timeOnSite WHERE case_id=:Tb_index AND years_type!='' $date_sql GROUP BY years_type", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['years_type'].',';
		 $num.=$one['one_timeOnSite_num'].',';
	   }
	   $type=substr($type,0,-1);
	   $num=substr($num,0,-1);

 	   $timeOnSite_years_name=explode(',', $type);
	   $timeOnSite_years_num=explode(',', $num);
	   
	   $total_num=count($timeOnSite_years_name);

 	  for ($i=0; $i <$total_num ; $i++) { 
 	  	
 	  	$timeOnSite_years_name[$i]=$timeOnSite_years_name[$i].'歲';
 	  	$timeOnSite_years_num[$i]=round((int)$timeOnSite_years_num[$i]/60, 2);
 	  }

	   $timeOnSite_years=implode(',', $timeOnSite_years_name).'|'.implode(',', $timeOnSite_years_num);
	   

      //-- 比較資料 --
	  if(!empty($_POST['com_StartDate'])){
		
		 $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		$row=$new_pdo->select("SELECT years_type, round(AVG(one_timeOnSite),2) as one_timeOnSite_num 
		                 FROM an_timeOnSite 
						 WHERE case_id=:Tb_index AND years_type!='' AND date BETWEEN :com_StartDate AND :com_EndDate 
						 GROUP BY years_type", $where);
		
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=$one['years_type'].',';
		 $num.=$one['one_timeOnSite_num'].',';
	   }
	   $type=substr($type,0,-1);
	   $num=substr($num,0,-1);

 	   $timeOnSite_years_name=explode(',', $type);
	   $timeOnSite_years_num=explode(',', $num);
	   
	   $total_num=count($timeOnSite_years_name);

 	  for ($i=0; $i <$total_num ; $i++) { 
 	  	
 	  	$timeOnSite_years_name[$i]=$timeOnSite_years_name[$i].'歲';
 	  	$timeOnSite_years_num[$i]=round((int)$timeOnSite_years_num[$i]/60, 2);
 	  }

	   $timeOnSite_years=$timeOnSite_years.'|'.implode(',', $timeOnSite_years_name).'|'.implode(',', $timeOnSite_years_num);
	  }

 	  echo $timeOnSite_years;
 	}

 	// -- 每日使用人數 --
 	elseif($_POST['type']=='data_use'){
		
	  $where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

	   $row=$new_pdo->select("SELECT date ,one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0 $date_sql ORDER BY date", $where);
	   $type='';
	   $num='';
	   foreach ($row as $one) {
		 $type.=date('Ymd', strtotime($one['date'])).',';
		 $num.=$one['one_user'].',';
	   }
	   
	   $return=substr($type,0,-1).'|'.substr($num,0,-1);

	   
	   //-- 比較資料 --
	   if(!empty($_POST['com_StartDate'])){
		   
         $where=['Tb_index'=>$_POST['Tb_index']];
		 $where['com_StartDate']=$_POST['com_StartDate'];
		 $where['com_EndDate']=$_POST['com_EndDate'];

		 $row_com=$new_pdo->select("SELECT date ,one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0  AND date BETWEEN :com_StartDate AND :com_EndDate ORDER BY date", $where);
		 $type='';
		 $num='';
		 foreach ($row_com as $com_one) {
			$type.=date('Ymd', strtotime($com_one['date'])).',';
		    $num.=$com_one['one_user'].',';
		 }
		 
		 $return=$return.'|'.substr($type,0,-1).'|'.substr($num,0,-1);
	   }

 	  echo $return;
	}
	 
	// -- 一周||最大使用人數 --
 	elseif($_POST['type']=='max_user'){
	   
	   $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  //-- 最大使用人數 --
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];

		$row=$new_pdo->select("SELECT date, one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0 $date_sql ORDER BY one_user DESC LIMIT 0,1", $where, 'one');
		
		//-- 時間區間2 --
		if(!empty($_POST['com_StartDate'])){
		  $date_sql=" AND date BETWEEN :com_StartDate AND :com_EndDate";
		  $where2=['Tb_index'=>$_POST['Tb_index']];
		  $where2['com_StartDate']=$_POST['com_StartDate'];
		  $where2['com_EndDate']=$_POST['com_EndDate'];

		  $row_com=$new_pdo->select("SELECT date, one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0 $date_sql ORDER BY one_user DESC LIMIT 0,1", $where2, 'one');
		  $input_txt=','.$row_com['one_user'].','.$row_com['date'];
		}
		else{
		  $input_txt='';
		}
		
		 echo $row['one_user'].','.$row['date'].$input_txt;
	   }
	   //-- 一周使用人數 --
	   else{
		 $row=$new_pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index AND date>=DATE_ADD( CURDATE( ) , INTERVAL -7 DAY ) GROUP BY case_id", $where, 'one');
		 echo $row['total'];
	   }
	}


	// -- 一月||最小使用人數 --
 	elseif($_POST['type']=='min_user'){
	   
	   $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  //-- 最小使用人數 --
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];

		 $row=$new_pdo->select("SELECT date,one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0 $date_sql ORDER BY one_user ASC LIMIT 0,1", $where, 'one');

		//-- 時間區間2 --
		if(!empty($_POST['com_StartDate'])){
		  $date_sql=" AND date BETWEEN :com_StartDate AND :com_EndDate";
		  $where2=['Tb_index'=>$_POST['Tb_index']];
		  $where2['com_StartDate']=$_POST['com_StartDate'];
		  $where2['com_EndDate']=$_POST['com_EndDate'];

		  $row_com=$new_pdo->select("SELECT date,one_user FROM an_user WHERE case_id=:Tb_index AND one_user>0 $date_sql ORDER BY one_user ASC LIMIT 0,1", $where2, 'one');
		  $input_txt=','.$row_com['one_user'].','.$row_com['date'];
		}
		else{
		  $input_txt='';
		}
		 

		 echo $row['one_user'].','.$row['date'].$input_txt;
	   }
	   //-- 一月使用人數 --
	   else{
		 $row=$new_pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index AND date>=DATE_ADD( CURDATE( ) , INTERVAL -1 MONTH ) GROUP BY case_id", $where, 'one');
		 echo $row['total'];
	   }
	}
	

	// -- 總使用人數 --
 	elseif($_POST['type']=='all_user'){
	   
	   $where=['Tb_index'=>$_POST['Tb_index']];
	  
	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
		
		// 時間區間2
		if(!empty($_POST['com_StartDate'])){
		    $where2=['Tb_index'=>$_POST['Tb_index']];
	    	$where2['com_StartDate']=$_POST['com_StartDate'];
			$where2['com_EndDate']=$_POST['com_EndDate'];
		   $row_com=$new_pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index AND date BETWEEN :com_StartDate AND :com_EndDate GROUP BY case_id", $where2, 'one');
		   $row_com_total=','.$row_com['total'];
		}
		else{
		    $row_com_total='';
		}

		 $row=$new_pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index $date_sql GROUP BY case_id", $where, 'one');
		 echo $row['total'].$row_com_total;
	   }
	   else{
		 $row=$new_pdo->select("SELECT SUM(one_user) as total FROM an_user WHERE case_id=:Tb_index GROUP BY case_id", $where, 'one');
		 echo $row['total'];
	   }
	}
	


	// -- 網頁瀏覽程度 --
    elseif($_POST['type']=='an_completion'){
	  
		$where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }
	   
	   //-- 首頁 --
	   $row_total=$new_pdo->select("SELECT SUM(one_user) as total FROM an_completion WHERE case_id=:Tb_index AND anchor_id='' $date_sql GROUP BY case_id", $where, 'one');
	   $row_one_img=$new_pdo->select("SELECT com_img FROM an_completion_img WHERE case_id=:case_id AND anchor_id=''", ['case_id'=>$_POST['Tb_index']], 'one');
	   $arr_total=[['anchor_name'=>'總瀏覽人數', 'users'=>$row_total['total'], 'case_id'=>$_POST['Tb_index'], 'anchor_id'=>'', 'com_img'=>$row_one_img['com_img']]];
		
	   $row=$new_pdo->select("SELECT a.anchor_name ,SUM(ac.one_user) as users, ac.case_id, ac.anchor_id, aci.com_img
	                    FROM an_completion as ac
						INNER JOIN anchor_tb as a ON a.Tb_index=ac.anchor_id
						INNER JOIN Related_tb as rt ON rt.fun_id=ac.anchor_id
						LEFT JOIN an_completion_img as aci ON aci.anchor_id=ac.anchor_id
						WHERE ac.case_id=:Tb_index $date_sql
						GROUP BY ac.anchor_id
						ORDER BY rt.OrderBy", $where);

	//    $row=pdo_select("SELECT a.anchor_name ,SUM(ac.one_user) as users, ac.case_id, ac.anchor_id, aci.com_img
	//                     FROM an_completion as ac
	// 					INNER JOIN anchor_tb as a ON a.Tb_index=ac.anchor_id
	// 					INNER JOIN Related_tb as rt ON rt.fun_id=ac.anchor_id
	// 					LEFT JOIN an_completion_img as aci ON aci.anchor_id=ac.anchor_id
	// 					WHERE ac.case_id=:Tb_index $date_sql
	// 					GROUP BY ac.anchor_id
	// 					ORDER BY rt.OrderBy", $where);
	   
	  array_splice($row,0,0,$arr_total);

 	  echo json_encode($row);
	}



	// -- 網頁瀏覽程度 TEST --
    elseif($_POST['type']=='an_completion_test'){
	  
		$where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=" AND date BETWEEN :an_StartDate AND :an_EndDate";
		 $where['an_StartDate']=date('Y-m-d', strtotime('-1 month'));
		 $where['an_EndDate']=date('Y-m-d', strtotime('-1 day'));
	   }

	   //-- 時間陣列 --
	   $StartDate=$where['an_StartDate'];
	   $EndDate=$where['an_EndDate'];
	   $date_arr=[];
	   while (strtotime($StartDate) <= strtotime($EndDate)) {
		  array_push($date_arr, $StartDate);
		  $StartDate=date('Y-m-d', strtotime('+1 day', strtotime($StartDate)));
	   }
	   

	   //  --  總瀏覽人數 --
	   $total_arr=[];
	   $row_total=$new_pdo->select("SELECT one_user, date FROM an_completion WHERE case_id=:Tb_index AND anchor_id='' $date_sql ", $where);
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


	   $row_one_img=$new_pdo->select("SELECT com_img FROM an_completion_img WHERE case_id=:case_id AND anchor_id=''", ['case_id'=>$_POST['Tb_index']],'one');
	   $arr_total=['date'=>$date_arr,
	   			   'completion'=>[['anchor_name'=>'總瀏覽人數', 'users'=>$total_arr, 'case_id'=>$_POST['Tb_index'], 'anchor_id'=>'', 'com_img'=>$row_one_img['com_img']]]];

	   
	   //-- 各區塊人數 --
	   $row=$new_pdo->select("SELECT a.anchor_name, ac.case_id, ac.anchor_id, aci.com_img
	                    FROM an_completion as ac
						INNER JOIN anchor_tb as a ON a.Tb_index=ac.anchor_id
						INNER JOIN Related_tb as rt ON rt.fun_id=ac.anchor_id
						LEFT JOIN an_completion_img as aci ON aci.anchor_id=ac.anchor_id
						WHERE ac.case_id=:Tb_index $date_sql
						GROUP BY ac.anchor_id
						ORDER BY rt.OrderBy", $where);
	 $x=0;
     foreach ($row as $one) {
		$where['anchor_id']=$one['anchor_id'];
	    $one_total=$new_pdo->select("SELECT one_user, date FROM an_completion WHERE case_id=:Tb_index AND anchor_id=:anchor_id $date_sql ", $where);
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
	   
	  array_splice($arr_total['completion'],1,0,$row);

 	  echo json_encode($arr_total);
	}



	// -- 網頁瀏覽程度(比較) --
    elseif($_POST['type']=='an_c_completion'){
	  
		$where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['com_StartDate'])){
		$date_sql=" AND date BETWEEN :com_StartDate AND :com_EndDate";
		$where['com_StartDate']=$_POST['com_StartDate'];
		$where['com_EndDate']=$_POST['com_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }
	   
	   
	   $row_total=$new_pdo->select("SELECT SUM(one_user) as total FROM an_completion WHERE case_id=:Tb_index AND anchor_id='' $date_sql GROUP BY case_id", $where, 'one');
	   $row_one_img=$new_pdo->select("SELECT com_img FROM an_completion_img WHERE case_id=:case_id AND anchor_id=''", ['case_id'=>$_POST['Tb_index']], 'one');

	   $arr_total=[['anchor_name'=>'總瀏覽人數', 'users'=>$row_total['total'], 'case_id'=>$_POST['Tb_index'], 'anchor_id'=>'', 'com_img'=>$row_one_img['com_img']]];

	   $row=$new_pdo->select("SELECT a.anchor_name ,SUM(ac.one_user) as users, ac.case_id, ac.anchor_id, aci.com_img
	                    FROM an_completion as ac
						INNER JOIN anchor_tb as a ON a.Tb_index=ac.anchor_id
						INNER JOIN Related_tb as rt ON rt.fun_id=ac.anchor_id
						LEFT JOIN an_completion_img as aci ON aci.anchor_id=ac.anchor_id AND aci.case_id=ac.case_id
						WHERE ac.case_id=:Tb_index $date_sql
						GROUP BY ac.anchor_id
						ORDER BY rt.OrderBy", $where);
	   
	  array_splice($row,0,0,$arr_total);

 	  echo json_encode($row);
	}





	//-- 網頁瀏覽程度(比較) TEST --
	elseif($_POST['type']=='an_c_completion_test'){
		
	  $where=['Tb_index'=>$_POST['Tb_index'], 'anchor_id'=>$_POST['anchor_id']];

	  if(!empty($_POST['com_StartDate'])){
		$date_sql=" AND date BETWEEN :com_StartDate AND :com_EndDate";
		$where['com_StartDate']=$_POST['com_StartDate'];
		$where['com_EndDate']=$_POST['com_EndDate'];
	   }
	   else{
		 $date_sql=" AND date BETWEEN :com_StartDate AND :com_EndDate";
		 $where['com_StartDate']=date('Y-m-d', strtotime('-1 month'));
		 $where['com_EndDate']=date('Y-m-d', strtotime('today'));
	   }

	   //-- 時間陣列 --
	   $StartDate=$where['com_StartDate'];
	   $EndDate=$where['com_EndDate'];
	   $date_arr=[];
	   while (strtotime($StartDate) <= strtotime($EndDate)) {
		  array_push($date_arr, $StartDate);
		  $StartDate=date('Y-m-d', strtotime('+1 day', strtotime($StartDate)));
	   }

	   
	   //-- 各區塊人數 --
	    $one_total=$new_pdo->select("SELECT one_user, date FROM an_completion WHERE case_id=:Tb_index AND anchor_id=:anchor_id $date_sql ", $where);
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
	// 	$row[$x]['users']=$com_arr;
	// 	$x++;
	 
	   
	//   array_splice($arr_total['completion'],1,0,$row);

 	  echo json_encode($com_arr);
	}


	
	//-- 預約賞屋來信 --
	elseif($_POST['type']=='an_mail'){

	  $where=['Tb_index'=>$_POST['Tb_index']];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND set_time BETWEEN :an_StartDate AND :an_EndDate";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

        $row_mail=$new_pdo->select("SELECT use_name, Tb_index, use_mail, phone, set_time, is_process, utm_source
                                    FROM call_record_tb 
                                    WHERE case_id=:Tb_index $date_sql
									ORDER BY set_time DESC", $where);
		
		echo json_encode($row_mail);
	}


	//-- 預約賞屋來信 (選擇狀態) --
	elseif($_POST['type']=='an_status_mail'){

	  $where=['Tb_index'=>$_POST['Tb_index'], 'is_process'=>'%'.$_POST['is_process'].'%'];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND set_time BETWEEN :an_StartDate AND :an_EndDate ";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

        $row_mail=$new_pdo->select("SELECT use_name, Tb_index, use_mail, phone, set_time, is_process
                                    FROM call_record_tb 
                                    WHERE case_id=:Tb_index AND is_process LIKE :is_process $date_sql
									ORDER BY set_time DESC", $where);
		
		echo json_encode($row_mail);
	}



	//-- 預約賞屋來信 (狀態) 數量 --
	elseif($_POST['type']=='an_num_mail'){

	  $where=['Tb_index'=>$_POST['Tb_index'], 'is_process'=>'%'.$_POST['is_process'].'%'];

	  if(!empty($_POST['an_StartDate'])){
		$date_sql=" AND set_time BETWEEN :an_StartDate AND :an_EndDate ";
		$where['an_StartDate']=$_POST['an_StartDate'];
		$where['an_EndDate']=$_POST['an_EndDate'];
	   }
	   else{
		 $date_sql=""; 
	   }

        $row_mail=$new_pdo->select("SELECT count(*) as num
                                    FROM call_record_tb 
                                    WHERE case_id=:Tb_index AND is_process LIKE :is_process $date_sql
									", $where, 'one');
		
		echo $row_mail['num'];
	}



    //-- 預約賞屋來信 處理 --
	elseif($_POST['type']=='ch_is_process'){
	   
	   $new_pdo->update('call_record_tb', ['is_process'=>$_POST['is_process']], ['Tb_index'=>$_POST['Tb_index']]);
	    
	   echo 'OK';
	}



	//@@@@@@@@@@@@@@@@@@@@@@@@@@@ 一週報表更多  @@@@@@@@@@@@@@@@@@@@@@@@@@@
	elseif($_POST['type']=='week_report'){
	   
	   $data= aes_decrypt_7($aes_key, $_POST['an_code']);

	   echo $data;
	}


	//@@@@@@@@@@@@@@@@@@@@@@@@@ 會員COOKIE @@@@@@@@@@@@@@@@@@@@@@@@@
	elseif($_POST['type']=='mem_cookie'){
	  
		echo $_COOKIE['admin_index'];
	}

 }
?>