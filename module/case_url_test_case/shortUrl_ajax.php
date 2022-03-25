<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 if ($_POST) {
   
   //-------- 縮短網址 ---------
   if ($_POST['type']=='Url') {
   	  
   	  //-- 判斷有無短網址 --
   	  $pdo=pdo_conn('srltw_short');
   	  $sql=$pdo->prepare("SELECT aUrl, url_id FROM appShort WHERE aUrl=:aUrl AND google_ac=',,'");
   	  $sql->execute(['aUrl'=>$_POST['longUrl']]);
   	  
   	  if ($sql->rowCount()<1) {
   	     $rand=rand_txt(randTXT(5));

   	     $param=[
   	       'Tb_index'=>'short'.date('YmdHis').rand(0,99),
   	       'mt_id'=>'site2017092011454657',
   	       'url_group'=>'inSS2018070409244214',
   	       'aTitle'=>$_POST['aTitle'],
   	       'aUrl'=>$_POST['longUrl'],
   	       'url_id'=>$rand,
   	       'qrCode_url'=>'http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=http://srl.tw/sh'.$rand.'&chld=H|0',
   	       'google_ac'=>',,',
   	       'StartDate'=>date('Y-m-d'),
   	       'OrderBy'=>'0',
   	       'OnLineOrNot'=>'1'
   	     ];

   	     pdo_insert('appShort', $param, 'srltw_short');

   	     echo 'http://srl.tw/sh'.$rand;
   	  }
   	  else{
         $is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
   	     echo 'http://srl.tw/sh'.$is_url[0]['url_id'];
   	  }
   }

   //----------- QR Code ------------
   else if($_POST['type']=='QR_code'){

   	  //-- google 分析標籤 --
   	  $google_mark=explode('?', $_POST['longUrl']);
   	  $all_mark=explode('&', $google_mark[1]);
   	  $source=explode('=', $all_mark[0]);
   	  $medium=explode('=', $all_mark[1]);
   	  $campaign=explode('=', $all_mark[2]);
      
      //-- 判斷有無短網址 --
      $pdo=pdo_conn('srltw_short');
   	  $sql=$pdo->prepare("SELECT aUrl, url_id FROM appShort WHERE aUrl=:aUrl AND google_ac LIKE :source AND google_ac LIKE :medium AND google_ac LIKE :campaign");
   	  $sql->execute([
        'aUrl'=>$google_mark[0],
        'source'=>'%'.$source[1].'%',
        'medium'=>'%'.$medium[1].'%',
        'campaign'=>'%'.$campaign[1].'%'
      ]);

   	  if ($sql->rowCount()<1) {
   	     $rand=rand_txt(randTXT(5));

   	     $param=[
   	       'Tb_index'=>'short'.date('YmdHis').rand(0,99),
   	       'mt_id'=>'site2017092011454657',
   	       'url_group'=>'inSS2018070409244214',
   	       'aTitle'=>$_POST['aTitle'],
   	       'aUrl'=>$google_mark[0],
   	       'url_id'=>$rand,
   	       'qrCode_url'=>'http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=http://srl.tw/sh'.$rand.'&chld=H|0',
   	       'google_ac'=>$source[1].','.$medium[1].','.$campaign[1],
   	       'StartDate'=>date('Y-m-d'),
   	       'OrderBy'=>'0',
   	       'OnLineOrNot'=>'1'
   	     ];

   	     pdo_insert('appShort', $param, 'srltw_short');

   	     echo 'http://srl.tw/sh'.$rand;
   	  }
   	  else{
   	  	 $is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
   	     echo 'http://srl.tw/sh'.$is_url[0]['url_id'];
   	  }
   }
   


 }//-- POST END --

 function rand_txt($url_id)
 {
 	$is_rand=pdo_select("SELECT url_id FROM appShort WHERE url_id=:url_id", ['url_id'=>$url_id], 'srltw_short');
 	if (!empty($is_rand['url_id'])) {
 		rand_txt(randTXT(5));
 	}else{
 		return $url_id;
 	}
 }
?>