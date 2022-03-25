<?php 
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';
 if ($_POST) {
   $pdo=new PDO_fun;

   if ($_POST['type']=='del_life') {
      $pdo->delete('life_location', ['Tb_index'=>$_POST['Tb_index']]);
   }
   elseif($_POST['type']=='google_get'){

     $case=$pdo->select("SELECT * FROM life_tb WHERE case_id=:case_id", ['case_id'=>$_POST['Tb_index']], 'one');
     $location= str_replace(' ', '', $case['location']) ;
     $life_range=explode('|', $case['life_range']);
     $life_keyword=explode('|', $case['life_keyword']);

     $type=$_POST['life_type'];

        
        switch ($type) {
          case 'food':
            $life_range=$life_range[0];
            $life_keyword=$life_keyword[0];
          break;
          case 'doctor':
            $life_range=$life_range[1];
            $life_keyword=$life_keyword[1];
          break;
          case 'lodging':
            $life_range=$life_range[2];
            $life_keyword=$life_keyword[2];
          break;
          case 'school':
            $life_range=$life_range[3];
            $life_keyword=$life_keyword[3];
          break;
          case 'park':
            $life_range=$life_range[4];
            $life_keyword=$life_keyword[4];
          break;
          case 'bus_station':
            $life_range=$life_range[5];
            $life_keyword=$life_keyword[5];
          break;
          case 'convenience_store':
            $life_range=$life_range[6];
            $life_keyword=$life_keyword[6];
          break;
        }

        $map_key='AIzaSyA7YTKkfqHMWYicRb2Ig0kgJDLSam7uUzo';

        //-- 建立連線 --
        $ch = curl_init();
        
        // 設定擷取的URL網址
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$location."&radius=".$life_range."&type=".$type."&keyword=".$life_keyword."&key=".$map_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        //設定要傳的 變數A=值A & 變數B=值B (中間要用&符號串接)
        //$PostData = "grant_type=authorization_code&code=".$_GET['code']."&redirect_uri=https%3a%2f%2ftest.wanchun.tw%2fshare_area%2fline_login.php&client_id=1656067806&client_secret=ea2a37580a27722883eb57659e8e161f";

        //設定CURLOPT_POST 為 1或true，表示要用POST方式傳遞
        //curl_setopt($ch, CURLOPT_POST, 1); 
        //CURLOPT_POSTFIELDS 後面則是要傳接的POST資料。
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $PostData);

        //-- 執行 --
        $temp=curl_exec($ch);
        $json_data=json_decode($temp, true);
        $results=$json_data['results'];
        foreach ($results as $one) {
          $ch_num=$pdo->select("SELECT COUNT(*) as total FROM life_location WHERE loc_id=:loc_id OR life_name=:life_name", 
                               ['loc_id'=>$one['place_id'], 'life_name'=>$one['name']], 'one');
          if($ch_num['total']<1){
            $Tb_index='ll'.date('YmdHis').rand(10,99);
            $life_location='('.$one['geometry']['location']['lat'].', '.$one['geometry']['location']['lng'].')';
            
            //-- 下載圖片 (未完成) --
            if (!empty($one['photos'][0]['photo_reference'])) {
              // $photo_reference=$one['photos'][0]['photo_reference'];
              // $life_photo_url='https://maps.googleapis.com/maps/api/place/photo?maxwidth=700&maxheight=700&photoreference='.$photo_reference.'&key='.$map_key;
              // //-- 建立連線 --
              // $dt_ch = curl_init();
              // // 設定擷取的URL網址
              // curl_setopt($img_ch, CURLOPT_URL, $life_photo_url);
              // curl_setopt($img_ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
              // curl_setopt($img_ch, CURLOPT_RETURNTRANSFER,1);
              // $img_temp=curl_exec($img_ch);
              // $img_temp=explode('HREF="', $img_temp);
              // $img_temp=explode('">here', $img_temp[1]);
              // $img_temp=$img_temp[0];
              // gmap_download($img_temp, dirname(__FILE__).'/../../../product_html/'.$_POST['Tb_index'].'/img/', $Tb_index.'.jpg');
              // $life_photo=$Tb_index.'.jpg';
              // echo $img_temp;
              $life_photo='';
            }
            else{
              $life_photo='';
            }

            //-- 詳細資料 --
            $dt_url='https://maps.googleapis.com/maps/api/place/details/json?place_id='.$one['place_id'].'&fields=rating,formatted_address,formatted_phone_number&key='.$map_key;
            $dt_ch = curl_init();
            // 設定擷取的URL網址
            curl_setopt($dt_ch, CURLOPT_URL, $dt_url);
            curl_setopt($dt_ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'accept-language: zh-TW'));
            curl_setopt($dt_ch, CURLOPT_RETURNTRANSFER,1);
            $dt_temp=curl_exec($dt_ch);
            $dt_json_data=json_decode($dt_temp, true);
            $dt=$dt_json_data['result'];
            //echo $dt_temp;

            $param=[
              'Tb_index'=>$Tb_index,
              'loc_id'=>$one['place_id'],
              'case_id'=>$_POST['Tb_index'],
              'life_type'=>$type,
              'case_location'=>$case['location'],
              'life_location'=>$life_location,
              'life_name'=>$one['name'],
              'life_photo'=>$life_photo,
              'life_phone'=>$dt['formatted_phone_number'],
              'life_addr'=>$dt['formatted_address'],
              'life_eva'=>$dt['rating']
            ];
            $pdo->insert('life_location', $param);
            
          }
           
        }
        
        //echo "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$location."&radius=".$life_range."&type=".$type."&keyword=".$life_keyword."&key=AIzaSyA7YTKkfqHMWYicRb2Ig0kgJDLSam7uUzo";

        // 關閉CURL連線
        curl_close($ch);
     
     
   }
 }
?>