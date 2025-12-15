<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 require '../../core/inc/security.php';
 if ($_POST) {


   $pdo_web=new PDO_fun();
   $pdo_short=new PDO_fun('short');

   //-- 新版短網址時間 --
   $newDate='2023-01-01';

   switch ($_POST['type']) {
	
	case 'Url':
		$longUrl=$_POST['longUrl'];
		$shortUrl_data=['insert'=>false, 'url'=>'', 'msg'=>''];
		$aTitle=replace_input($_POST['aTitle']);

		//-- 判斷有無短網址 --
		$pdo=pdo_conn('srltw_short');
		$sql=$pdo->prepare("SELECT aUrl, url_id, StartDate FROM appShort WHERE aUrl=:aUrl AND google_ac=',,'");
		$sql->execute(['aUrl'=>$longUrl]);
		$is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
		
		if ($sql->rowCount()<1) {

			//-- 歷史紀錄 --
			$pdo_web->hs_tb_name='appShort';
			$pdo_web->hs_h_location='網址列表';
			$pdo_web->hs_h_action_type='insert';
			$pdo_web->hs_h_title='新增短網址-'.$_POST['source'];
			//-- 舊資料 --
			$pdo_web->old_data();

			$rand=rand_txt(randTXT(5));

			$param=[
			'Tb_index'=>'short'.date('YmdHis').rand(0,99),
			'mt_id'=>'site2017092011454657',
			'url_group'=>'inSS2018070409244214',
			'aTitle'=>$aTitle,
			'aUrl'=>$longUrl,
			'url_id'=>$rand,
			'qrCode_url'=>'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://srl.tw/sh'.$rand,
			'google_ac'=>',,',
			'StartDate'=>date('Y-m-d'),
			'OrderBy'=>'0',
			'OnLineOrNot'=>'1'
			];

			pdo_insert('appShort', $param, 'srltw_short');

			//-- 新增歷史紀錄 --
			$pdo_web->hs_new_param=$param;
			$pdo_web->add_history();

			$shortUrl_data['url']=strtotime($is_url[0]['StartDate']) <= strtotime($newDate) ? 'https://srl.tw/sh'.$rand : 'https://ucy.tw/'.$is_url[0]['url_id'];
		}
		else{
			
			$shortUrl_data['msg']='短網址重複';
			$shortUrl_data['url']=strtotime($is_url[0]['StartDate']) <= strtotime($newDate) ? 'https://srl.tw/sh'.$is_url[0]['url_id'] : 'https://ucy.tw/'.$is_url[0]['url_id'];
		}

		//-- 新增QR code --
		if(!empty($_POST['case_id'])){
			$case_id=replace_input($_POST['case_id']);
			$source=replace_input($_POST['source']);
			$media=replace_input($_POST['media']);
			$event_name=replace_input($_POST['event_name']);
			//---------------- 查詢重複QRcode --------------
			
			$QR_row=$pdo_web->select("SELECT * FROM QRcode_tb WHERE case_id=:case_id", ['case_id'=>$case_id]);
			foreach ($QR_row as $QR_one) {
				if($shortUrl_data['url']==$QR_one['QRcode_url']){
					$shortUrl_data['msg']='短網址重複';
					echo json_encode($shortUrl_data);
					exit();
				}
			}
				
				$Tb_index='qr'.date('YmdHis').rand(0,99);
				//$img_url='../../../product_html/'.$case_id.'/img/'.$Tb_index.'.png';
				$param=[
					'Tb_index'=>$Tb_index,
					'case_id'=>$case_id,
					'QRcode_url'=>$shortUrl_data['url'],
					'source'=>$source,
					'media'=>$media,
					'event_name'=>$event_name
					];
				$pdo_web->insert('QRcode_tb', $param);

		}
		
		
		$shortUrl_data['insert']=true;
		echo json_encode($shortUrl_data);
		break;


	//-- 查詢短網址列表 --
	case 'select':
		$case_id=replace_input($_POST['case_id']);

		$url_list=$pdo_web->select("SELECT * FROM QRcode_tb WHERE case_id=:case_id AND OnLineOrNot=1 ORDER BY Tb_index DESC", ['case_id'=>$case_id]);
		$x=0;
		foreach ($url_list as $list_one) {
		$url_id=explode('srl.tw/sh', $list_one['QRcode_url']);
		$read_url=$pdo_short->select("SELECT aUrl, StartDate FROM appShort WHERE url_id=:url_id", ['url_id'=>$url_id[1]], 'one');
		$url_list[$x]['qr_id']=urlencode(aes_encrypt_7($aes_key, $list_one['Tb_index']));
		$url_list[$x]['read_url']=$read_url['aUrl'];
		$url_list[$x]['QRcode_url']=strtotime($read_url['StartDate']) <= strtotime($newDate) ? $list_one['QRcode_url'] : 'https://ucy.tw/'.$url_id[1];
		$x++;
		}

		echo json_encode($url_list);
		break;


	//-- 修改到達網址 --
	case 'edit_read_url':
		$QRcode_url=$_POST['QRcode_url'];
		$aUrl=$_POST['aUrl'];
		$url_id=explode('srl.tw/sh', $QRcode_url);

		//-- 歷史紀錄 --
		$pdo_web->hs_tb_name='appShort';
		$pdo_web->hs_old_id=$url_id[1];
		$pdo_web->hs_old_index_name='url_id';
		$pdo_web->hs_h_location='網址列表';
		$pdo_web->hs_h_action_type='update';
		$pdo_web->hs_h_title='修改到達網址-'.$_POST['aUrl'];
		//-- 舊資料 --
		$pdo_web->old_data('short');


		
		$pdo_short->update('appShort', ['aUrl'=>$aUrl], ['url_id'=>$url_id[1]]);


		//-- 新增歷史紀錄 --
		$pdo_web->add_history('short');
		break;
	

	//-- 刪除短網址 --
	case 'del_url':
		$Tb_index=replace_input($_POST['Tb_index']);
		$qr_url=$pdo_web->select("SELECT QRcode_url, source FROM QRcode_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$Tb_index], 'one');
		$url_id=explode('srl.tw/sh', $qr_url['QRcode_url']);

		//-- 歷史紀錄 --
		$pdo_web->hs_tb_name='appShort';
		$pdo_web->hs_old_id=$url_id[1];
		$pdo_web->hs_old_index_name='url_id';
		$pdo_web->hs_h_location='網址列表';
		$pdo_web->hs_h_action_type='update';
		$pdo_web->hs_h_title='修改到達網址(刪除)-'.$qr_url['source'];
		//-- 舊資料 --
		$pdo_web->old_data('short');


		$pdo_web->update('QRcode_tb', ['OnLineOrNot'=>0], ['Tb_index'=>$Tb_index]);
		$pdo_short->update('appShort', ['OnLineOrNot'=>0], ['url_id'=>$url_id[1]]);


		//-- 新增歷史紀錄 --
		$pdo_web->add_history('short');
		break;

	
	//----------- QR Code ------------
	case 'QR_code':
		//  $longUrl=$_POST['longUrl'];
		//  $aTitle=replace_input($_POST['aTitle']);

		//   //-- google 分析標籤 --
		//   $google_mark=explode('?', $longUrl );
		//   $all_mark=explode('&', $google_mark[1]);
		//   $source=explode('=', $all_mark[0]);
		//   $medium=explode('=', $all_mark[1]);
		//   $campaign=explode('=', $all_mark[2]);
		
		//   //-- 判斷有無短網址 --
		//   $pdo=pdo_conn('srltw_short');
		//   $sql=$pdo->prepare("SELECT aUrl, url_id FROM appShort WHERE aUrl=:aUrl AND google_ac LIKE :source AND google_ac LIKE :medium AND google_ac LIKE :campaign");
		//   $sql->execute([
		//     'aUrl'=>$google_mark[0],
		//     'source'=>'%'.$source[1].'%',
		//     'medium'=>'%'.$medium[1].'%',
		//     'campaign'=>'%'.$campaign[1].'%'
		//   ]);

		//   if ($sql->rowCount()<1) {
		//      $rand=rand_txt(randTXT(5));

		//      $param=[
		//        'Tb_index'=>'short'.date('YmdHis').rand(0,99),
		//        'mt_id'=>'site2017092011454657',
		//        'url_group'=>'inSS2018070409244214',
		//        'aTitle'=>$aTitle,
		//        'aUrl'=>$google_mark[0],
		//        'url_id'=>$rand,
		//        'qrCode_url'=>'https://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=https://srl.tw/sh'.$rand.'&chld=H|0',
		//        'google_ac'=>$source[1].','.$medium[1].','.$campaign[1],
		//        'StartDate'=>date('Y-m-d'),
		//        'OrderBy'=>'0',
		//        'OnLineOrNot'=>'1'
		//      ];

		//      pdo_insert('appShort', $param, 'srltw_short');

		//      echo 'https://srl.tw/sh'.$rand;
		//   }
		//   else{
		//   	 $is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
		//      echo 'https://srl.tw/sh'.$is_url[0]['url_id'];
		//   }
		break;
	
	default:
		# code...
		break;
   }

   //-------- 縮短網址 ---------
//    if ($_POST['type']=='Url') {
   	
// 	 $longUrl=$_POST['longUrl'];
// 	 $shortUrl_data=['insert'=>false, 'url'=>'', 'msg'=>''];
// 	 $aTitle=replace_input($_POST['aTitle']);

//    	  //-- 判斷有無短網址 --
//    	  $pdo=pdo_conn('srltw_short');
//    	  $sql=$pdo->prepare("SELECT aUrl, url_id FROM appShort WHERE aUrl=:aUrl AND google_ac=',,'");
//    	  $sql->execute(['aUrl'=>$longUrl]);
   	  
//    	  if ($sql->rowCount()<1) {

// 		//-- 歷史紀錄 --
// 		$pdo_web->hs_tb_name='appShort';
// 		$pdo_web->hs_h_location='網址列表';
// 		$pdo_web->hs_h_action_type='insert';
// 		$pdo_web->hs_h_title='新增短網址-'.$_POST['source'];
// 		//-- 舊資料 --
// 		$pdo_web->old_data();

//    	     $rand=rand_txt(randTXT(5));

//    	     $param=[
//    	       'Tb_index'=>'short'.date('YmdHis').rand(0,99),
//    	       'mt_id'=>'site2017092011454657',
//    	       'url_group'=>'inSS2018070409244214',
//    	       'aTitle'=>$aTitle,
//    	       'aUrl'=>$longUrl,
//    	       'url_id'=>$rand,
//    	       'qrCode_url'=>'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://srl.tw/sh'.$rand,
//    	       'google_ac'=>',,',
//    	       'StartDate'=>date('Y-m-d'),
//    	       'OrderBy'=>'0',
//    	       'OnLineOrNot'=>'1'
//    	     ];

//    	     pdo_insert('appShort', $param, 'srltw_short');

// 		//-- 新增歷史紀錄 --
// 		$pdo_web->hs_new_param=$param;
// 		$pdo_web->add_history();

//    	     $shortUrl_data['url']='https://srl.tw/sh'.$rand;
//    	  }
//    	  else{
//          $is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
// 		 $shortUrl_data['msg']='短網址重複';
//    	     $shortUrl_data['url']='https://srl.tw/sh'.$is_url[0]['url_id'];
//    	  }


// 	  //-- 新增QR code --
// 	  if(!empty($_POST['case_id'])){
// 		$case_id=replace_input($_POST['case_id']);
// 		$source=replace_input($_POST['source']);
// 		$media=replace_input($_POST['media']);
// 		$event_name=replace_input($_POST['event_name']);
// 		//---------------- 查詢重複QRcode --------------
		
// 		$QR_row=$pdo_web->select("SELECT * FROM QRcode_tb WHERE case_id=:case_id", ['case_id'=>$case_id]);
// 		foreach ($QR_row as $QR_one) {
// 			if($shortUrl_data['url']==$QR_one['QRcode_url']){
// 				$shortUrl_data['msg']='短網址重複';
// 				echo json_encode($shortUrl_data);
// 				exit();
// 			}
// 		}
			
// 			$Tb_index='qr'.date('YmdHis').rand(0,99);
// 			//$img_url='../../../product_html/'.$case_id.'/img/'.$Tb_index.'.png';
// 			$param=[
// 				'Tb_index'=>$Tb_index,
// 				'case_id'=>$case_id,
// 				'QRcode_url'=>$shortUrl_data['url'],
// 				'source'=>$source,
// 				'media'=>$media,
// 				'event_name'=>$event_name
// 				];
// 			$pdo_web->insert('QRcode_tb', $param);

// 	  }
	  
	   
// 	   $shortUrl_data['insert']=true;
// 	   echo json_encode($shortUrl_data);
//    }

//     //-- 查詢短網址列表 --
//    elseif($_POST['type']=='select'){

// 	$case_id=replace_input($_POST['case_id']);

// 	$url_list=$pdo_web->select("SELECT * FROM QRcode_tb WHERE case_id=:case_id AND OnLineOrNot=1 ORDER BY Tb_index DESC", ['case_id'=>$case_id]);
// 	$x=0;
// 	foreach ($url_list as $list_one) {
// 	  $url_id=explode('srl.tw/sh', $list_one['QRcode_url']);
// 	  $read_url=$pdo_short->select("SELECT aUrl FROM appShort WHERE url_id=:url_id", ['url_id'=>$url_id[1]], 'one');
// 	  $url_list[$x]['qr_id']=urlencode(aes_encrypt_7($aes_key, $list_one['Tb_index']));
// 	  $url_list[$x]['read_url']=$read_url['aUrl'];
// 	  $x++;
// 	}

// 	echo json_encode($url_list);
//    }

//    //-- 修改到達網址 --
//    elseif($_POST['type']=='edit_read_url'){

// 	$QRcode_url=$_POST['QRcode_url'];
// 	$aUrl=$_POST['aUrl'];
// 	$url_id=explode('srl.tw/sh', $QRcode_url);

// 	//-- 歷史紀錄 --
// 	$pdo_web->hs_tb_name='appShort';
// 	$pdo_web->hs_old_id=$url_id[1];
// 	$pdo_web->hs_old_index_name='url_id';
// 	$pdo_web->hs_h_location='網址列表';
// 	$pdo_web->hs_h_action_type='update';
// 	$pdo_web->hs_h_title='修改到達網址-'.$_POST['aUrl'];
// 	//-- 舊資料 --
// 	$pdo_web->old_data('short');


	 
// 	$pdo_short->update('appShort', ['aUrl'=>$aUrl], ['url_id'=>$url_id[1]]);


// 	 //-- 新增歷史紀錄 --
// 	 $pdo_web->add_history('short');
//    }

//    //-- 刪除短網址 --
//    elseif($_POST['type']=='del_url'){
	
// 	$Tb_index=replace_input($_POST['Tb_index']);
// 	$qr_url=$pdo_web->select("SELECT QRcode_url, source FROM QRcode_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$Tb_index], 'one');
// 	$url_id=explode('srl.tw/sh', $qr_url['QRcode_url']);

// 	//-- 歷史紀錄 --
// 	$pdo_web->hs_tb_name='appShort';
// 	$pdo_web->hs_old_id=$url_id[1];
// 	$pdo_web->hs_old_index_name='url_id';
// 	$pdo_web->hs_h_location='網址列表';
// 	$pdo_web->hs_h_action_type='update';
// 	$pdo_web->hs_h_title='修改到達網址(刪除)-'.$qr_url['source'];
// 	//-- 舊資料 --
// 	$pdo_web->old_data('short');


// 	 $pdo_web->update('QRcode_tb', ['OnLineOrNot'=>0], ['Tb_index'=>$Tb_index]);
// 	 $pdo_short->update('appShort', ['OnLineOrNot'=>0], ['url_id'=>$url_id[1]]);


// 	 //-- 新增歷史紀錄 --
// 	 $pdo_web->add_history('short');
//    }

//    //----------- QR Code ------------
//    else if($_POST['type']=='QR_code'){

// 	//  $longUrl=$_POST['longUrl'];
// 	//  $aTitle=replace_input($_POST['aTitle']);

//    	//   //-- google 分析標籤 --
//    	//   $google_mark=explode('?', $longUrl );
//    	//   $all_mark=explode('&', $google_mark[1]);
//    	//   $source=explode('=', $all_mark[0]);
//    	//   $medium=explode('=', $all_mark[1]);
//    	//   $campaign=explode('=', $all_mark[2]);
      
//     //   //-- 判斷有無短網址 --
//     //   $pdo=pdo_conn('srltw_short');
//    	//   $sql=$pdo->prepare("SELECT aUrl, url_id FROM appShort WHERE aUrl=:aUrl AND google_ac LIKE :source AND google_ac LIKE :medium AND google_ac LIKE :campaign");
//    	//   $sql->execute([
//     //     'aUrl'=>$google_mark[0],
//     //     'source'=>'%'.$source[1].'%',
//     //     'medium'=>'%'.$medium[1].'%',
//     //     'campaign'=>'%'.$campaign[1].'%'
//     //   ]);

//    	//   if ($sql->rowCount()<1) {
//    	//      $rand=rand_txt(randTXT(5));

//    	//      $param=[
//    	//        'Tb_index'=>'short'.date('YmdHis').rand(0,99),
//    	//        'mt_id'=>'site2017092011454657',
//    	//        'url_group'=>'inSS2018070409244214',
//    	//        'aTitle'=>$aTitle,
//    	//        'aUrl'=>$google_mark[0],
//    	//        'url_id'=>$rand,
//    	//        'qrCode_url'=>'https://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=https://srl.tw/sh'.$rand.'&chld=H|0',
//    	//        'google_ac'=>$source[1].','.$medium[1].','.$campaign[1],
//    	//        'StartDate'=>date('Y-m-d'),
//    	//        'OrderBy'=>'0',
//    	//        'OnLineOrNot'=>'1'
//    	//      ];

//    	//      pdo_insert('appShort', $param, 'srltw_short');

//    	//      echo 'https://srl.tw/sh'.$rand;
//    	//   }
//    	//   else{
//    	//   	 $is_url=$sql->fetchAll(PDO::FETCH_ASSOC);
//    	//      echo 'https://srl.tw/sh'.$is_url[0]['url_id'];
//    	//   }
//    }
   

   $pdo_web->close();
   $pdo_short->close();
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