<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';

if ($_POST) {


	$pdo=pdo_conn();
	
	//===================== 複製建案 ====================
	$sql=$pdo->prepare("SELECT * FROM build_case WHERE Tb_index=:Tb_index");
	$sql->execute(['Tb_index'=>$_POST['Tb_index']]);
   
	while ($case_row=$sql->fetch(PDO::FETCH_ASSOC)) {

      $aTitle=empty($case_row['aTitle']) ? '':$case_row['aTitle'];
      $ph_tool_type=empty($case_row['ph_tool_type']) ? '':$case_row['ph_tool_type'];
      $aPic=empty($case_row['aPic']) ? '':$case_row['aPic'];
      $logo=empty($case_row['logo']) ? '':$case_row['logo'];
      $format=empty($case_row['format']) ? '':$case_row['format'];
      $floor=empty($case_row['floor']) ? '':$case_row['floor'];
      $build_com=empty($case_row['build_com']) ? '':$case_row['build_com'];
      $Consignment=empty($case_row['Consignment']) ? '':$case_row['Consignment'];
      $description=empty($case_row['description']) ? '':$case_row['description'];
      $build_adds=empty($case_row['build_adds']) ? '':$case_row['build_adds'];
      $google_code=empty($case_row['google_code']) ? '':$case_row['google_code'];
      $google_view_code=empty($case_row['google_view_code']) ? '':$case_row['google_view_code'];
      $phone=empty($case_row['phone']) ? '':$case_row['phone'];
      $line_txt=empty($case_row['line_txt']) ? '':$case_row['line_txt'];
      $fb_txt=empty($case_row['fb_txt']) ? '':$case_row['fb_txt'];
      $version=empty($case_row['version']) ? '':$case_row['version'];
      $KeyWord=empty($case_row['KeyWord']) ? '':$case_row['KeyWord'];
      $StartDate=empty($case_row['StartDate']) ? '':$case_row['StartDate'];
      $OnLineOrNot=empty($case_row['OnLineOrNot']) ? '':$case_row['OnLineOrNot'];
      $OrderBy=empty($case_row['OrderBy']) ? '':$case_row['OrderBy'];
      $ad_making=empty($case_row['ad_making']) ? '':$case_row['ad_making'];
      
		$param=[
		      'Tb_index'=>$case_row['Tb_index'].'test',
		      'com_id'=>$case_row['com_id'],
		      'aTitle'=>$aTitle,
		      'ph_tool_type'=>$ph_tool_type,
		      'aPic'=>$aPic,
		      'logo'=>$logo,
		      'format'=>$format,
		      'floor'=>$floor,
		      'build_com'=>$build_com,
		      'Consignment'=>$Consignment,
		      'description'=>$description,
		      'build_adds'=>$build_adds,
		      'google_code'=>$google_code,
		      'google_view_code'=>$google_view_code,
		      'phone'=>$phone,
		      'line_txt'=>$line_txt,
		      'fb_txt'=>$fb_txt,
		      'version'=>$version,
		      'KeyWord'=>$KeyWord,
		      'StartDate'=>$StartDate,
		      'OnLineOrNot'=>$OnLineOrNot,
		      'OrderBy'=>$OrderBy,
		      'ad_making'=>$ad_making,
		    ];
			pdo_insert('build_case', $param, 'srltw_test_case');

				//--- 輸出複製建案 ---
			   $test_arr=[];
			   $test_arr['Tb_index']=$_POST['Tb_index'].'test';
			   $test_arr['aTitle']=$case_row['aTitle'];
			   $test_arr['OnLineOrNot']=$case_row['OnLineOrNot'];
			   $test_arr['version']=$case_row['version'];
			   echo json_encode($test_arr);

	}


    

    //===================== 複製功能區塊關聯 ====================
   $sql=$pdo->prepare("SELECT * FROM Related_tb WHERE case_id=:case_id");
	$sql->execute(['case_id'=>$_POST['Tb_index']]);
   $i=1;
	while ($rel_row=$sql->fetch(PDO::FETCH_ASSOC)) {

      
      //===================== 複製基本圖文 ====================
      if (substr($rel_row['fun_id'], 0,2)=='bs') {

         base_word($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製圖片輪播 ====================
      elseif(substr($rel_row['fun_id'], 0,2)=='ss'){

        slideshow_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }
      
      //===================== 複製Youtube ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='yu') {

         youtube_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製Google Map ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='gm') {

         googlemap_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製聯絡我們 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ca') {

         call_us_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製圖片牆 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='iw') {

         img_wall_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製自定義 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ot') {

         other_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製房貸試算 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ma') {

         mathHome_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製錨點 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='an') {

         anchor_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      //===================== 複製食醫住行 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='li') {

         life_tb($_POST['Tb_index'].'test', $rel_row['fun_id']);
      }

      $param=[
           'Tb_index'=>$rel_row['Tb_index'],
           'case_id'=>$_POST['Tb_index'].'test',
           'fun_id'=>$rel_row['fun_id'],
           'funbox_id'=>$rel_row['funbox_id'],
           'OrderBy'=>$rel_row['OrderBy'],
           'OnLineOrNot'=>$rel_row['OnLineOrNot']
         ];
      pdo_insert('Related_tb', $param, 'srltw_test_case');
	  
     $i++;
	}


   //===================== 複製顏色====================
   $sql=$pdo->prepare("SELECT * FROM color WHERE Tb_index=:Tb_index");
   $sql->execute(['Tb_index'=>$_POST['Tb_index']]);
   $x=1;
   while ($color_row=$sql->fetch(PDO::FETCH_ASSOC)) {

          $param=[
             'Tb_index'=>$_POST['Tb_index'].'test',
             'h1_color'=>$color_row['h1_color'],
             'h2_color'=>$color_row['h2_color'],
             'p_color'=>$color_row['p_color'],
             'marquee'=>$color_row['marquee'],
             'top_txt'=>$color_row['top_txt'],
             'top_bar'=>$color_row['top_bar'],
             'back_color'=>$color_row['back_color']
           
          ];
         pdo_insert('color', $param, 'srltw_test_case');   
         $x++;
   }


   //===================== 複製CSS====================
   $sql=$pdo->prepare("SELECT * FROM change_css WHERE Tb_index=:Tb_index");
   $sql->execute(['Tb_index'=>$_POST['Tb_index']]);
   $x=1;
   while ($css_row=$sql->fetch(PDO::FETCH_ASSOC)) {

          $param=[
             'Tb_index'=>$_POST['Tb_index'].'test',
             'css'=>$css_row['css']           
          ];
         pdo_insert('change_css', $param, 'srltw_test_case');   
         $x++;
   }

   //===================== 複製新聞====================
    $sql=$pdo->prepare("SELECT * FROM case_news WHERE case_id=:case_id");
       $sql->execute(['case_id'=>$_POST['Tb_index']]);
       while ($news_row=$sql->fetch(PDO::FETCH_ASSOC)) {

              $param=[
                 'Tb_index'=>$news_row['Tb_index'],
                 'case_id'=>$_POST['Tb_index'].'test',
                 'aTitle'=>$news_row['aTitle'],
                 'aPic'=>$news_row['aPic'],
                 'source'=>$news_row['source'],
                 'aAbstract'=>$news_row['aAbstract'],
                 'aUrl'=>$news_row['aUrl'],
                 'youtubeUrl'=>$news_row['youtubeUrl'],
                 'StartDate'=>$news_row['StartDate'],
                 'OrderBy'=>$news_row['OrderBy'],
                 'OnLineOrNot'=>$news_row['OnLineOrNot']
              ];
             pdo_insert('case_news', $param, 'srltw_test_case');   
       }



   //---------------- 檔案複製 --------------------
   copy_dir(WS_PATH.'product_html/'.$_POST['Tb_index'], WS_PATH.'product_html/'.$_POST['Tb_index'].'test');
}



   
   function base_word($case_id ,$Tb_index)
   {  
      $pdo=pdo_conn();
      $fun_sql=$pdo->prepare("SELECT * FROM base_word WHERE Tb_index=:Tb_index");
            $fun_sql->execute(['Tb_index'=>$Tb_index]);
            while ($base_row=$fun_sql->fetch(PDO::FETCH_ASSOC)) {
                 
                   $param=[
                      'Tb_index'=>$Tb_index,
                      'case_id'=>$case_id,
                      'aTitle'=>$base_row['aTitle'],
                      'Title_two'=>$base_row['Title_two'],
                      'content'=>$base_row['content'],
                      'base_img'=>$base_row['base_img'],
                      'base_img_ph'=>$base_row['base_img_ph'],
                      'back_img'=>$base_row['back_img'],
                      'txt_fadein'=>$base_row['txt_fadein'],
                      'img_fadein'=>$base_row['img_fadein'],
                      'ImgWord_type'=>$base_row['ImgWord_type'],
                      'ImgWord_ph_type'=>$base_row['ImgWord_ph_type'],
                      'zoomin_img'=>$base_row['zoomin_img'],
                      'line_show'=>$base_row['line_show'],
                      'OnLineOrNot'=>$base_row['OnLineOrNot'],
                      'StartDate'=>date('Y-m-d H:i:s')
                   ];
                  pdo_insert('base_word', $param, 'srltw_test_case'); 
            }
      $pdo=null;
   }

   
   function slideshow_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM slideshow_tb WHERE Tb_index=:Tb_index");
        $sql->execute(['Tb_index'=>$Tb_index]);
        while ($show_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'play_speed'=>$show_row['play_speed'],
                'effect'=>$show_row['effect'],
                'show_img'=>$show_row['show_img'],
                'show_img_ph'=>$show_row['show_img_ph'],
                'ImgWord_type'=>$show_row['ImgWord_type'],
                'ImgWord_ph_type'=>$show_row['ImgWord_ph_type'],
                'aTXT'=>$show_row['aTXT'],
                'img_txt'=>$show_row['img_txt'],
                'StartDate'=>date('Y-m-d H:i:s'),
                'OnLineOrNot'=>$show_row['OnLineOrNot']
             ];
            pdo_insert('slideshow_tb', $param, 'srltw_test_case');
  
        }
      $pdo=null;
   }


   function youtube_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM youtube_tb WHERE Tb_index=:Tb_index");
         $sql->execute(['Tb_index'=>$Tb_index]);
         while ($you_row=$sql->fetch(PDO::FETCH_ASSOC)) {

                $param=[
                   'Tb_index'=>$Tb_index,
                   'case_id'=>$case_id,
                   'aTitle'=>$you_row['aTitle'],
                 'video_type'=>$you_row['video_type'],
                 'you_adds'=>$you_row['you_adds'],
                 'video_file'=>$you_row['video_file'],
                 'autoPlay'=>$you_row['autoPlay'],
                   'StartDate'=>date('Y-m-d H:i:s'),
                   'OnLineOrNot'=>$you_row['OnLineOrNot']
                ];
               pdo_insert('youtube_tb', $param, 'srltw_test_case');   
         }
      $pdo=null;
   }


   function googlemap_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM googlemap_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($map_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'aTitle'=>$map_row['aTitle'],
              'location'=>$map_row['location'],
              'loc_txt'=>$map_row['loc_txt'],
                'OnLineOrNot'=>$map_row['OnLineOrNot']
             ];
            pdo_insert('googlemap_tb', $param, 'srltw_test_case'); 
      }
      $pdo=null;
   }


   function call_us_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM call_us_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($call_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'btn_name'=>$call_row['btn_name'],
                're_name'=>$call_row['re_name'],
                're_mail'=>$call_row['re_mail'],
                'OnLineOrNot'=>$call_row['OnLineOrNot']
             ];
            pdo_insert('call_us_tb', $param, 'srltw_test_case');
      }
      $pdo=null;
   }


   function img_wall_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM img_wall_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($imgWall_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'img_file'=>$imgWall_row['img_file'],
                'img_word'=>$imgWall_row['img_word'],
                'OnLineOrNot'=>$imgWall_row['OnLineOrNot']
             ];
            pdo_insert('img_wall_tb', $param, 'srltw_test_case');  
      }
      $pdo=null;
   }

   function other_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM other_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($other_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'content'=>$other_row['content'],
                'OnLineOrNot'=>$other_row['OnLineOrNot']
             ];
            pdo_insert('other_tb', $param, 'srltw_test_case');  
      }
      $pdo=null;
   }

   function mathHome_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM mathHome_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($home_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'OnLineOrNot'=>$home_row['OnLineOrNot']
             ];
            pdo_insert('mathHome_tb', $param,'srltw_test_case');  
      }
      $pdo=null;
   }

   function anchor_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM anchor_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);
      while ($an_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
                'Tb_index'=>$Tb_index,
                'case_id'=>$case_id,
                'anchor_name'=>$an_row['anchor_name'],
                'OnLineOrNot'=>$an_row['OnLineOrNot']
             ];
            pdo_insert('anchor_tb', $param, 'srltw_test_case'); 
      }
      $pdo=null;
   }

   function life_tb($case_id, $Tb_index)
   {  
      $pdo=pdo_conn();
      $sql=$pdo->prepare("SELECT * FROM life_tb WHERE Tb_index=:Tb_index");
         $sql->execute(['Tb_index'=>$Tb_index]);
         while ($life_row=$sql->fetch(PDO::FETCH_ASSOC)) {

                $param=[
                   'Tb_index'=>$Tb_index,
                   'case_id'=>$case_id,
                   'location'=>$life_row['location'],
                 'life_range'=>$life_row['life_range'],
                 'life_keyword'=>$life_row['life_keyword'],
                 'life_zoom'=>$life_row['life_zoom'],
                 'traffic_loc'=>$life_row['traffic_loc'],
                 'traffic_name'=>$life_row['traffic_name'],
                 'traffic_zoom'=>$life_row['traffic_zoom'],
                 'fun_loc'=>$life_row['fun_loc'],
                 'fun_name'=>$life_row['fun_name'],
                 'color_type'=>$life_row['color_type'],
                 'type'=>$life_row['type'],
                   'OnLineOrNot'=>$life_row['OnLineOrNot']
                ];
               pdo_insert('life_tb', $param, 'srltw_test_case');   
         }
      $pdo=null;
   }

?>