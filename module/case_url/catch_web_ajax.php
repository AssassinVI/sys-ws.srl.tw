<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 if ($_POST) {

   $token = filter_input(INPUT_POST, 'tk', FILTER_SANITIZE_STRING);
	if (!$token || $token !== $_SESSION['token']) {
		// show an error message
		echo '<p class="error">Error: invalid form submission---</p>';
		// return 405 http status code
		header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
		exit;
	}
   
   //-- 新增 --
   if ($_POST['type']=='insert') {

	$case_id=replace_input($_POST['case_id']);
	$QRcode_url=replace_input($_POST['QRcode_url']);
	$source=replace_input($_POST['source']);
	$media=replace_input($_POST['media']);
	$event_name=replace_input($_POST['event_name']);
	$QRcode_pic=replace_input($_POST['QRcode_pic']);

     //---------------- 查詢重複QRcode --------------
     $pdo=pdo_conn();
   	 $sql=$pdo->prepare("SELECT * FROM QRcode_tb WHERE case_id=:case_id");
   	 $sql->execute(['case_id'=>$case_id]);
   	 while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
   	 	if (array_search($QRcode_url, $row)) {
   	 		echo "0";
   	 		exit();
   	 	}
	}
		
		$Tb_index='qr'.date('YmdHis').rand(0,99);
		$img_url='../../../product_html/'.$case_id.'/img/'.$Tb_index.'.png';

   	 	$param=[
   	 	      'Tb_index'=>$Tb_index,
   	 	      'case_id'=>$case_id,
   	 	      'QRcode_pic'=>$img_url,
   	 	      'QRcode_url'=>$QRcode_url,
   	 	      'source'=>$source,
   	 	      'media'=>$media,
   	 	      'event_name'=>$event_name
   	 	 	];
			pdo_insert('QRcode_tb', $param);
			
			//-- QR code 存圖檔 --
			// $QRcode_pic_decode=htmlspecialchars_decode($QRcode_pic);
			// $image = imagecreatefrompng($QRcode_pic_decode);
			// imagejpeg($image, $img_url, "80");
			// imagedestroy($image);

				 echo "1";
	
	$pdo=NULL;

   }

   //-- 查詢 --
   elseif($_POST['type']=='select'){

	$case_id=replace_input($_POST['case_id']);

   	 $pdo=pdo_conn();
   	 $sql=$pdo->prepare("SELECT * FROM QRcode_tb WHERE case_id=:case_id AND OnLineOrNot=1 ORDER BY Tb_index DESC");
   	 $sql->execute(['case_id'=>$case_id]);
	 $row=$sql->fetchAll(PDO::FETCH_ASSOC);
	 
	 $row_num=count($row);
	    for ($i=0; $i <$row_num ; $i++) { 
		  $row[$i]['qr_id']=urlencode(aes_encrypt_7($aes_key, $row[$i]['Tb_index']));
		}
		echo json_encode($row);
	
	 $pdo=NULL;
   }

   //-- 刪除 --
   elseif($_POST['type']=='del_url'){

	//  $Tb_index=replace_input($_POST['Tb_index']);

	//  $pdo=pdo_conn();
	//   $sql=$pdo->prepare("UPDATE QRcode_tb SET OnLineOrNot=0 WHERE Tb_index=:Tb_index");
   	//   $sql->execute(['Tb_index'=>$Tb_index]);
    //  $pdo=NULL;
   }
 }
?>