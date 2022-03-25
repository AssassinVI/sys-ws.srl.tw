<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 if ($_POST) {
   
   //-- 新增 --
   if ($_POST['type']=='insert') {

     //---------------- 查詢重複QRcode --------------
     $pdo=pdo_conn();
   	 $sql=$pdo->prepare("SELECT * FROM QRcode_tb WHERE case_id=:case_id");
   	 $sql->execute(['case_id'=>$_POST['case_id']]);
   	 while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
   	 	if (array_search($_POST['QRcode_url'], $row)) {
   	 		echo "0";
   	 		exit();
   	 	}
		}
		
		$Tb_index='qr'.date('YmdHis').rand(0,99);
		$img_url='../../../product_html/'.$_POST['case_id'].'/img/'.$Tb_index.'.png';

   	 	$param=[
   	 	      'Tb_index'=>$Tb_index,
   	 	      'case_id'=>$_POST['case_id'],
   	 	      'QRcode_pic'=>$img_url,
   	 	      'QRcode_url'=>$_POST['QRcode_url'],
   	 	      'source'=>$_POST['source'],
   	 	      'media'=>$_POST['media'],
   	 	      'event_name'=>$_POST['event_name']
   	 	 	];
			pdo_insert('QRcode_tb', $param);
			
			//-- QR code 存圖檔 --
			$QRcode_pic=htmlspecialchars_decode($_POST['QRcode_pic']);
			$image = imagecreatefrompng($QRcode_pic);
			imagejpeg($image, $img_url, "80");
			imagedestroy($image);

				 echo "1";
	
	$pdo=NULL;

   }

   //-- 查詢 --
   elseif($_POST['type']=='select'){
   	 $pdo=pdo_conn();
   	 $sql=$pdo->prepare("SELECT * FROM QRcode_tb WHERE case_id=:case_id AND OnLineOrNot=1 ORDER BY Tb_index DESC");
   	 $sql->execute(['case_id'=>$_POST['case_id']]);
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
	 $pdo=pdo_conn();
	  $sql=$pdo->prepare("UPDATE QRcode_tb SET OnLineOrNot=0 WHERE Tb_index=:Tb_index");
   	  $sql->execute(['Tb_index'=>$_POST['Tb_index']]);
     $pdo=NULL;
   }
 }
?>