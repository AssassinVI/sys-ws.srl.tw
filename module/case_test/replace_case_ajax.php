<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';

if ($_POST) {


	$pdo=pdo_conn('srltw_test_case');

  $old_Tb_index=substr($_POST['Tb_index'], 0,-4);
	
	//===================== 替換建案 ====================
	$sql=$pdo->prepare("SELECT * FROM build_case WHERE Tb_index=:Tb_index");
	$sql->execute(['Tb_index'=>$_POST['Tb_index']]);
	while ($case_row=$sql->fetch(PDO::FETCH_ASSOC)) {
    
		$param=[
		      'com_id'=>$case_row['com_id'],
		      'aTitle'=>$case_row['aTitle'],
		      'ph_tool_type'=>$case_row['ph_tool_type'],
		      'aPic'=>$case_row['aPic'],
		      'logo'=>$case_row['logo'],
		      'format'=>$case_row['format'],
		      'floor'=>$case_row['floor'],
		      'build_com'=>$case_row['build_com'],
		      'Consignment'=>$case_row['Consignment'],
		      'description'=>$case_row['description'],
		      'build_adds'=>$case_row['build_adds'],
		      'google_code'=>$case_row['google_code'],
		      'google_view_code'=>$case_row['google_view_code'],
		      'phone'=>$case_row['phone'],
		      'line_txt'=>$case_row['line_txt'],
		      'fb_txt'=>$case_row['fb_txt'],
		      'version'=>$case_row['version'],
		      'KeyWord'=>$case_row['KeyWord'],
		      'StartDate'=>$case_row['StartDate'],
		      'OnLineOrNot'=>$case_row['OnLineOrNot'],
		      'OrderBy'=>$case_row['OrderBy'],
		      'ad_making'=>$case_row['ad_making'],
		    ];
			pdo_update('build_case', $param, ['Tb_index'=>$old_Tb_index]);


	}


    

    //===================== 替換功能區塊關聯 ====================
  //-- 正式版 --
  $old_Related_tb=pdo_select("SELECT * FROM Related_tb WHERE case_id=:case_id", ['case_id'=>$old_Tb_index]);
  
  //-- 測試版 --
  $sql=$pdo->prepare("SELECT * FROM Related_tb WHERE case_id=:case_id");
	$sql->execute(['case_id'=>$_POST['Tb_index']]);
  $x=0;
	while ($rel_row=$sql->fetch(PDO::FETCH_ASSOC)) {
  
      
      //===================== 替換基本圖文 ====================
      if (substr($rel_row['fun_id'], 0,2)=='bs') {

         base_word($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換圖片輪播 ====================
      elseif(substr($rel_row['fun_id'], 0,2)=='ss'){

        slideshow_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }
      
      //===================== 替換Youtube ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='yu') {

         youtube_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換Google Map ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='gm') {

         googlemap_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換聯絡我們 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ca') {

         call_us_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換圖片牆 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='iw') {

         img_wall_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換自定義 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ot') {

         other_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換房貸試算 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='ma') {

         mathHome_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換錨點 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='an') {

         anchor_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }

      //===================== 替換食醫住行 ====================
      elseif (substr($rel_row['fun_id'], 0,2)=='li') {

         life_tb($old_Related_tb[$x]['fun_id'], $rel_row['fun_id']);
      }




      if ($old_Related_tb[$x]['Tb_index']==$rel_row['Tb_index']) {
        $param=[
             'fun_id'=>$rel_row['fun_id'],
             'funbox_id'=>$rel_row['funbox_id'],
             'OrderBy'=>$rel_row['OrderBy'],
             'OnLineOrNot'=>$rel_row['OnLineOrNot']
           ];
        pdo_update('Related_tb', $param, ['Tb_index'=>$rel_row['Tb_index']]);
      }
      else{

        $param=[
           'Tb_index'=>$rel_row['Tb_index'],
           'case_id'=>$old_Tb_index,
           'fun_id'=>$rel_row['fun_id'],
           'funbox_id'=>$rel_row['funbox_id'],
           'OrderBy'=>$rel_row['OrderBy'],
           'OnLineOrNot'=>$rel_row['OnLineOrNot']
         ];
        pdo_insert('Related_tb', $param);
      }

      

    $x++;

	}


   //===================== 替換顏色====================
   $sql=$pdo->prepare("SELECT * FROM color WHERE Tb_index=:Tb_index");
   $sql->execute(['Tb_index'=>$_POST['Tb_index']]);

   while ($color_row=$sql->fetch(PDO::FETCH_ASSOC)) {

          $param=[
             'h1_color'=>$color_row['h1_color'],
             'h2_color'=>$color_row['h2_color'],
             'p_color'=>$color_row['p_color'],
             'marquee'=>$color_row['marquee'],
             'top_txt'=>$color_row['top_txt'],
             'top_bar'=>$color_row['top_bar'],
             'back_color'=>$color_row['back_color']
           
          ];
         pdo_update('color', $param, ['Tb_index'=>$old_Tb_index]);   

   }


   //===================== 替換CSS====================
   $sql=$pdo->prepare("SELECT * FROM change_css WHERE Tb_index=:Tb_index");
   $sql->execute(['Tb_index'=>$_POST['Tb_index']]);
   while ($css_row=$sql->fetch(PDO::FETCH_ASSOC)) {

          $param=[
             'css'=>$css_row['css']           
          ];
         pdo_update('change_css', $param, ['Tb_index'=>$old_Tb_index]);   

   }

   //===================== 替換新聞====================
    $sql=$pdo->prepare("SELECT * FROM case_news WHERE case_id=:case_id");
       $sql->execute(['case_id'=>$_POST['Tb_index']]);
       while ($news_row=$sql->fetch(PDO::FETCH_ASSOC)) {

              $param=[
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
             pdo_update('case_news', $param, ['Tb_index'=>$news_row['Tb_index']]);   
       }



   //---------------- 檔案替換 --------------------
   copy_dir(WS_PATH.'product_html/'.$_POST['Tb_index'], WS_PATH.'product_html/'.$old_Tb_index);
}



   
   function base_word($old_fun_id='', $Tb_index)
   {  
     global $old_Tb_index;

     $pdo=pdo_conn('srltw_test_case');
     $fun_sql=$pdo->prepare("SELECT * FROM base_word WHERE Tb_index=:Tb_index");
     $fun_sql->execute(['Tb_index'=>$Tb_index]);
           

     if (empty($old_fun_id) || $old_fun_id!=$Tb_index) {
       
      while ($base_row=$fun_sql->fetch(PDO::FETCH_ASSOC)) {
           
             $param=[
                'Tb_index'=>$base_row['Tb_index'],
                'case_id'=>$old_Tb_index,
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
            pdo_insert('base_word', $param); 
      }
     }
     else{

       while ($base_row=$fun_sql->fetch(PDO::FETCH_ASSOC)) {
            
              $param=[
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
             pdo_update('base_word', $param, ['Tb_index'=>$Tb_index]); 
       }
     }
      
      $pdo=null;
   }

   
   function slideshow_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

     $pdo=pdo_conn('srltw_test_case');
     $sql=$pdo->prepare("SELECT * FROM slideshow_tb WHERE Tb_index=:Tb_index");
       $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index) {
        while ($show_row=$sql->fetch(PDO::FETCH_ASSOC)) {

                     $param=[
                        'Tb_index'=>$show_row['Tb_index'],
                        'case_id'=>$old_Tb_index,
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
                    pdo_insert('slideshow_tb', $param);
                }
      }
      else{
        
        while ($show_row=$sql->fetch(PDO::FETCH_ASSOC)) {

             $param=[
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
            pdo_update('slideshow_tb', $param, ['Tb_index'=>$Tb_index]);
        }
      }
      
        
      $pdo=null;
   }


   function youtube_tb($old_fun_id='', $Tb_index)
   {  
     global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM youtube_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){

        while ($you_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                'Tb_index'=>$you_row['Tb_index'],
                'case_id'=>$old_Tb_index,
                'aTitle'=>$you_row['aTitle'],
                'video_type'=>$you_row['video_type'],
                'you_adds'=>$you_row['you_adds'],
                'video_file'=>$you_row['video_file'],
                'autoPlay'=>$you_row['autoPlay'],
                  'StartDate'=>date('Y-m-d H:i:s'),
                  'OnLineOrNot'=>$you_row['OnLineOrNot']
               ];
              pdo_insert('youtube_tb', $param);   
        }
      }
      else{

        while ($you_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'aTitle'=>$you_row['aTitle'],
                'video_type'=>$you_row['video_type'],
                'you_adds'=>$you_row['you_adds'],
                'video_file'=>$you_row['video_file'],
                'autoPlay'=>$you_row['autoPlay'],
                  'StartDate'=>date('Y-m-d H:i:s'),
                  'OnLineOrNot'=>$you_row['OnLineOrNot']
               ];
              pdo_update('youtube_tb', $param, ['Tb_index'=>$Tb_index]);   
        }
      }
         
      $pdo=null;
   }


   function googlemap_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM googlemap_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
         
         while ($map_row=$sql->fetch(PDO::FETCH_ASSOC)) {

                $param=[
                  'Tb_index'=>$map_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'aTitle'=>$map_row['aTitle'],
                  'location'=>$map_row['location'],
                  'loc_txt'=>$map_row['loc_txt'],
                   'OnLineOrNot'=>$map_row['OnLineOrNot']
                ];
               pdo_insert('googlemap_tb', $param); 
         }
      }
      else{

        while ($map_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'aTitle'=>$map_row['aTitle'],
                'location'=>$map_row['location'],
                'loc_txt'=>$map_row['loc_txt'],
                  'OnLineOrNot'=>$map_row['OnLineOrNot']
               ];
              pdo_update('googlemap_tb', $param, ['Tb_index'=>$Tb_index]); 
        }
      }
      
      $pdo=null;
   }


   function call_us_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM call_us_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        while ($call_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'Tb_index'=>$call_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'btn_name'=>$call_row['btn_name'],
                  're_name'=>$call_row['re_name'],
                  're_mail'=>$call_row['re_mail'],
                  'OnLineOrNot'=>$call_row['OnLineOrNot']
               ];
              pdo_insert('call_us_tb', $param);
        }
      }
      else{

        while ($call_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'btn_name'=>$call_row['btn_name'],
                  're_name'=>$call_row['re_name'],
                  're_mail'=>$call_row['re_mail'],
                  'OnLineOrNot'=>$call_row['OnLineOrNot']
               ];
              pdo_update('call_us_tb', $param, ['Tb_index'=>$Tb_index]);
        }
      }
      
      $pdo=null;
   }


   function img_wall_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM img_wall_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        
        while ($imgWall_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'Tb_index'=>$imgWall_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'img_file'=>$imgWall_row['img_file'],
                  'img_word'=>$imgWall_row['img_word'],
                  'OnLineOrNot'=>$imgWall_row['OnLineOrNot']
               ];
              pdo_insert('img_wall_tb', $param);  
        }
      }
      else{

        while ($imgWall_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'img_file'=>$imgWall_row['img_file'],
                  'img_word'=>$imgWall_row['img_word'],
                  'OnLineOrNot'=>$imgWall_row['OnLineOrNot']
               ];
              pdo_update('img_wall_tb', $param, ['Tb_index'=>$Tb_index]);  
        }
      }
      
      $pdo=null;
   }

   function other_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM other_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        
        while ($other_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'Tb_index'=>$other_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'content'=>$other_row['content'],
                  'OnLineOrNot'=>$other_row['OnLineOrNot']
               ];
              pdo_insert('other_tb', $param);  
        }
      }
      else{

         while ($other_row=$sql->fetch(PDO::FETCH_ASSOC)) {

                $param=[
                   'content'=>$other_row['content'],
                   'OnLineOrNot'=>$other_row['OnLineOrNot']
                ];
               pdo_update('other_tb', $param, ['Tb_index'=>$Tb_index]);  
         }
      }
      
      $pdo=null;
   }

   function mathHome_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM mathHome_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        
        while ($home_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'Tb_index'=>$home_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'OnLineOrNot'=>$home_row['OnLineOrNot']
               ];
              pdo_insert('mathHome_tb', $param);  
        }
      }
      else{
        while ($home_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'OnLineOrNot'=>$home_row['OnLineOrNot']
               ];
              pdo_update('mathHome_tb', $param, ['Tb_index'=>$Tb_index]);  
        }
      }
      
      $pdo=null;
   }

   function anchor_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM anchor_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        
        while ($an_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'Tb_index'=>$an_row['Tb_index'],
                  'case_id'=>$old_Tb_index,
                  'anchor_name'=>$an_row['anchor_name'],
                  'OnLineOrNot'=>$an_row['OnLineOrNot']
               ];
              pdo_insert('anchor_tb', $param); 
        }
      }
      else{

        while ($an_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                  'anchor_name'=>$an_row['anchor_name'],
                  'OnLineOrNot'=>$an_row['OnLineOrNot']
               ];
              pdo_update('anchor_tb', $param, ['Tb_index'=>$Tb_index]); 
        }
      }
      
      $pdo=null;
   }

   function life_tb($old_fun_id='', $Tb_index)
   {  
    global $old_Tb_index;

      $pdo=pdo_conn('srltw_test_case');
      $sql=$pdo->prepare("SELECT * FROM life_tb WHERE Tb_index=:Tb_index");
      $sql->execute(['Tb_index'=>$Tb_index]);

      if (empty($old_fun_id) || $old_fun_id!=$Tb_index){
        
        while ($life_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
                'Tb_index'=>$life_row['Tb_index'],
                'case_id'=>$old_Tb_index,
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
              pdo_insert('life_tb', $param);   
        }

        life_location();
      }
      else{

        while ($life_row=$sql->fetch(PDO::FETCH_ASSOC)) {

               $param=[
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
              pdo_update('life_tb', $param, ['Tb_index'=>$Tb_index]);   
        }

        life_location();
      }
         
      $pdo=null;
   }


   function life_location(){

     global $old_Tb_index;

     $row_life_location=pdo_select("SELECT * FROM life_location WHERE case_id=:case_id", ['case_id'=>$old_Tb_index.'test']);
     $row_life_location_old=pdo_select("SELECT * FROM life_location WHERE case_id=:case_id", ['case_id'=>$old_Tb_index]);
     if (count($row_life_location)>0 && count($row_life_location_old)<1) {

       foreach ($row_life_location as $row) {
         $Tb_index='ll'.date('YmdHis').rand(0,99);
                $param=[
                        'Tb_index'=>$Tb_index,
                        'case_id'=>$old_Tb_index,
                        'life_type'=>$row['life_type'],
                        'case_location'=>$row['case_location'],
                        'life_location'=>$row['life_location'],
                        'life_name'=>$row['life_name'],
                        'life_photo'=>$row['life_photo'],
                        'life_phone'=>$row['life_phone'],
                        'life_addr'=>$row['life_addr'],
                        'life_eva'=>$row['life_eva']
                       ];
                      pdo_insert('life_location', $param); 
       }
     }
   }

?>