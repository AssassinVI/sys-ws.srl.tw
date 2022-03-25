<?php
header("Content-type: image/jpeg");
header("Content-Disposition: attachment; filename='QR_code.png'"); //filename檔案名稱

 // 建立CURL連線
      $ch = curl_init();
      // 設定擷取的URL網址
      curl_setopt($ch, CURLOPT_URL, $_POST['qr_url']);
      curl_setopt($ch, CURLOPT_HEADER, false);
      //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      // 執行
      $image_data=curl_exec($ch);
      // 關閉CURL連線
      curl_close($ch);
 
 //---------- 結合LOGO用 ------------
//move_uploaded_file($image_data,'QRcode_pic/'.$Tb_index.'.png');


$image = imagecreatefromstring($image_data);

//Logo
if (!empty($_FILES['qr_logo']['name'])) {
    
    $icon_info=getimagesize($_FILES['qr_logo']['tmp_name']); //取得圖片長寬、類型
    $icon_type=$icon_info[2]; //圖片類型

    switch ($icon_type) {
        case '1':
              $icon=imagecreatefromgif($_FILES['qr_logo']['tmp_name']);
            break;
        case '2':
              $icon=imagecreatefromjpeg($_FILES['qr_logo']['tmp_name']);
            break;
        case '3':
              $icon=imagecreatefrompng($_FILES['qr_logo']['tmp_name']);
            break;
    }

     $thumb=imagecreatetruecolor(50, 50);//建立空白縮圖
     imagecopyresampled($thumb, $icon, 0, 0, 0, 0, 50, 50, $icon_info[0], $icon_info[1]);
     imagecopymerge($image, $thumb, 50, 50, 0, 0, 50, 50, 100);

     imagepng($image); // 於此目錄下, 產生實體圖片
}
else{
    
    imagepng($image); // 於此目錄下, 產生實體圖片
}




?>