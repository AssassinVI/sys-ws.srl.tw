<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.fb_fans{ display: none; }
	#ph_tool_type_exp img{ width: 100%; height: 750px; }
    
    .other_ad{display: none; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
 
if ($_POST) {
  // ======================== 刪除 ===========================
  	//----------------------- 代表圖刪除 -------------------------------
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    	if ($_POST['col']=='aPic') {
    		$param=['aPic'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('build_case', $param, $where);
            unlink($_POST['data']);

    	}elseif($_POST['col']=='logo'){
            $param=['logo'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('build_case', $param, $where);
            unlink($_POST['data']);
    	}
    	elseif($_POST['col']=='activity_song'){
            $param=['activity_song'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('build_case', $param, $where);
            unlink($_POST['data']);

    	}elseif($_POST['col']=='activity_img'){
            $param=['activity_img'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('build_case', $param, $where);
            unlink($_POST['data']);
		}
		elseif($_POST['col']=='loading_logo'){
            $param=['loading_logo'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('build_case', $param, $where);
            unlink($_POST['data']);
    	}
       exit();
  	}

  	//-------------------------- 新增 -------------------------------
  	//-------------------------- 新增 -------------------------------
  	//-------------------------- 新增 -------------------------------

	if (empty($_POST['Tb_index'])) {//新增
		$Tb_index='case'.date('YmdHis').rand(0,99);


	 //--------------------------------------- 建立專案檔案 ----------------------------------
      create_dir(WS_PATH.'product_html/'.$Tb_index);

      if ($_POST['version']=='1') { //-- 正常版 --
    	  copy(WS_PATH.'product/product_empty.php', WS_PATH.'product_html/' . $Tb_index . '/Default.php');
      }
      elseif($_POST['version']=='2'){ //-- 全屏滑動版 --
        copy(WS_PATH.'product/product_empty_slide.php', WS_PATH.'product_html/' . $Tb_index . '/Default.php');
      }
    	elseif($_POST['version']=='0'){//-- 簡易版 --
    		copy(WS_PATH.'product/product_easy.php', WS_PATH.'product_html/' . $Tb_index . '/Default.php');
    	}

     
     //===================== 專案LOGO ========================
      if (!empty($_FILES['logo']['name'])){

      	 $type=explode('.', $_FILES['logo']['name']);
      	 $logo=$Tb_index.date('His').'.'.$type[count($type)-1];
		 fire_upload('logo', $logo, $Tb_index);
        // img_webp('logo', $logo, $Tb_index);
      }else{
      	$logo='';
      }

      //===================== 讀取LOGO ========================
      if (!empty($_FILES['loading_logo']['name'])){

      	 $type=explode('.', $_FILES['loading_logo']['name']);
		 $loading_logo=$Tb_index.date('His').'.'.$type[count($type)-1];
		 fire_upload('loading_logo', $loading_logo, $Tb_index);
		 //img_webp('loading_logo', $loading_logo, $Tb_index);
		 

      }else{
      	$loading_logo='';
      }

     //===================== 分享圖片 ========================
      if (!empty($_FILES['aPic']['name'])){

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.date('His').'.'.$type[count($type)-1];
		   fire_upload('aPic', $aPic, $Tb_index);
         //img_webp('aPic', $aPic, $Tb_index);
      }else{
      	$aPic='';
      }

     //===================== 活動圖片 ========================
      if (!empty($_FILES['activity_img']['name'])){

      	 $type=explode('.', $_FILES['activity_img']['name']);
      	 $activity_img=$Tb_index.date('His').'-ac.'.$type[count($type)-1];
		   fire_upload('activity_img', $activity_img, $Tb_index);
         //img_webp('activity_img', $activity_img, $Tb_index);
      }else{
      	 $activity_img='';
      }

      //===================== 背景音樂 ========================
      if (!empty($_FILES['activity_song']['name'])){

      	 $type=explode('/', $_FILES['activity_song']['type']);
      	 $activity_song=$Tb_index.date('His').'.'.$type[1];
         audio_upload('activity_song', $activity_song, $Tb_index);
      }else{
      	$activity_song='';
      }

      //===================== 廣告製作判斷 ==================
      if ($_POST['ad_making']=='o') {
      	$ad_making=$_POST['ad_making'].','.$_POST['ad_making_name'].','.$_POST['ad_making_url'];
      }
      else{
      	$ad_making=$_POST['ad_making'];
	  }
	  

	  //========================= QR code 存圖檔 =============================
	//   $img_url='../../../product_html/'.$Tb_index.'/img/'.$Tb_index.'_qr.png';
	//   $Tb_index_qr=substr($Tb_index,4);
	//   $QRcode_pic=htmlspecialchars_decode('https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=https://ws.srl.tw/cs/'.$Tb_index_qr.'/&chld=L|1&choe=UTF-8');
	//   $image = imagecreatefrompng($QRcode_pic);
	//   imagejpeg($image, $img_url, "80");
	//   imagedestroy($image);

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='build_case';
	$new_pdo->hs_h_location='專案編輯';
	$new_pdo->hs_h_action_type='insert';
	$new_pdo->hs_h_title='建立新專案-'.$_POST['aTitle'];
	//-- 舊資料 --
	$new_pdo->old_data();


    $is_auto_an=empty($_POST['is_auto_an']) ? 0:1;

	$param=  [          'Tb_index'=>$Tb_index,
			              'com_id'=>$_POST['com_id'],
			              'aTitle'=>$_POST['aTitle'],
						  'web_title'=>$_POST['web_title'],
			        'ph_tool_type'=>$_POST['ph_tool_type'],
			             'version'=>$_POST['version'],
			              'format'=>$_POST['format'],
			            'line_txt'=>$_POST['line_txt'],
	  					  'fb_sel'=>$_POST['fb_sel'],
			              'fb_txt'=>$_POST['fb_txt'],
			               'phone'=>$_POST['phone'],
			          'build_adds'=>$_POST['build_adds'],
			             'marquee'=>$_POST['marquee'],
			         'google_code'=>$_POST['google_code'],
				'google_view_code'=>$_POST['google_view_code'],
					   'head_code'=>$_POST['head_code'],
					   'body_code'=>$_POST['body_code'],
                'is_auto_an'=>$is_auto_an,
			         'description'=>$_POST['description'],
			             'KeyWord'=>$_POST['KeyWord'],
			           'StartDate'=>date('Y-m-d'),
					     'EndDate'=>$_POST['EndDate'],
					 'OnLineOrNot'=>$_POST['OnLineOrNot'],
					 	  'on_gtm'=>$_POST['on_gtm'],
					   'send_mail'=>$_POST['send_mail'],
                       'send_week'=>$_POST['send_week'],
			                'aPic'=>$aPic,
			                'logo'=>$logo,
			        'loading_logo'=>$loading_logo,
			        'activity_img'=>$activity_img,
			       'activity_song'=>$activity_song,
					   'ad_making'=>$ad_making,
					      'qrcode'=>$Tb_index.'_qr.png'
			         ];
	pdo_insert('build_case', $param);

	//-- 新增歷史紀錄 --
	$new_pdo->hs_new_param=$param;
	$new_pdo->add_history();

	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功新增');
   }

    //-------------------------- 修改 -------------------------------
  	//-------------------------- 修改 -------------------------------
  	//-------------------------- 修改 -------------------------------
   else{  //修改
   	$Tb_index =$_POST['Tb_index'];

   	 //===================== 專案LOGO ========================
   	 if (!empty($_FILES['logo']['name'])) {

		  unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['logo_file']);

		  if(is_file('/home/srltw/ws.srl.tw/product_html/'.$Tb_index.'/img/'.$_POST['logo_file'].'.webp')){
            unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['logo_file'].'.webp');
		  }

      	 $type=explode('.', $_FILES['logo']['name']);
      	 $logo=$Tb_index.date('His').'.'.$type[count($type)-1];
		   fire_upload('logo', $logo, $Tb_index);
         //img_webp('logo', $logo, $Tb_index);
        $logo_param=['logo'=>$logo];
        $logo_where=['Tb_index'=>$Tb_index];
        pdo_update('build_case', $logo_param, $logo_where);
        
      }

      //===================== 讀取LOGO ========================
   	 if (!empty($_FILES['loading_logo']['name'])) {

		  unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['loading_logo_file']);
		  
		  if(is_file('/home/srltw/ws.srl.tw/product_html/'.$Tb_index.'/img/'.$_POST['loading_logo_file'].'.webp')){
            unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['loading_logo_file'].'.webp');
		  }

      	 $type=explode('.', $_FILES['loading_logo']['name']);
		 $loading_logo=$Tb_index.date('His').'.'.$type[count($type)-1];
		 fire_upload('loading_logo', $loading_logo, $Tb_index);
         //img_webp('loading_logo', $loading_logo, $Tb_index);
        $loading_logo_param=['loading_logo'=>$loading_logo];
        $loading_logo_where=['Tb_index'=>$Tb_index];
        pdo_update('build_case', $loading_logo_param, $loading_logo_where);
        
      }

   	 //===================== 分享圖片 ========================
      if (!empty($_FILES['aPic']['name'])) {

		  unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['aPic_file']);
		  
		  if(is_file('/home/srltw/ws.srl.tw/product_html/'.$Tb_index.'/img/'.$_POST['aPic_file'].'.webp')){
            unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['aPic_file'].'.webp');
		  }

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.date('His').'.'.$type[count($type)-1];
		   fire_upload('aPic', $aPic, $Tb_index);
        // img_webp('aPic', $aPic, $Tb_index);
        $aPic_param=['aPic'=>$aPic];
        $aPic_where=['Tb_index'=>$Tb_index];
        pdo_update('build_case', $aPic_param, $aPic_where);
        
      }

     //===================== 背景音樂 ========================
      if (!empty($_FILES['activity_song']['name'])) {

      	unlink('../../../product_html/'.$Tb_index.'/audio/'.$_POST['activity_song_file']);

      	 $type=explode('/', $_FILES['activity_song']['type']);
      	 $activity_song=$Tb_index.date('His').'.'.$type[1];
         audio_upload('activity_song', $activity_song, $Tb_index);
        $activity_song_param=['activity_song'=>$activity_song];
        $activity_song_where=['Tb_index'=>$Tb_index];
        pdo_update('build_case', $activity_song_param, $activity_song_where);
        
      }

     //===================== 活動圖 ========================
      if (!empty($_FILES['activity_img']['name'])) {

		  unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['activity_img_file']);
		  
		  if(is_file('/home/srltw/ws.srl.tw/product_html/'.$Tb_index.'/img/'.$_POST['activity_img_file'].'.webp')){
            unlink('../../../product_html/'.$Tb_index.'/img/'.$_POST['activity_img_file'].'.webp');
		  }

      	 $type=explode('.', $_FILES['activity_img']['name']);
      	 $activity_img=$Tb_index.date('His').'-ac'.'.'.$type[count($type)-1];
		   fire_upload('activity_img', $activity_img, $Tb_index);
         //img_webp('activity_img', $activity_img, $Tb_index);
        $activity_img_param=['activity_img'=>$activity_img];
        $activity_img_where=['Tb_index'=>$Tb_index];
        pdo_update('build_case', $activity_img_param, $activity_img_where);
        
      }
     
      	//--------------------------- END -----------------------------------

      if ($_POST['version']=='1') { //-- 正常版 --
       	copy('../../../product/product_empty.php', '../../../product_html/' . $Tb_index . '/Default.php');
      }
      elseif($_POST['version']=='2'){ //-- 全屏滑動版 --
        copy('../../../product/product_empty_slide.php', '../../../product_html/' . $Tb_index . '/Default.php');
      }
     	elseif($_POST['version']=='0'){//-- 簡易版 --
     		copy('../../../product/product_easy.php', '../../../product_html/' . $Tb_index . '/Default.php');
     	}

     //===================== 廣告製作判斷 ==================
      if ($_POST['ad_making']=='o') {
      	$ad_making=$_POST['ad_making'].','.$_POST['ad_making_name'].','.$_POST['ad_making_url'];
      }
      else{
      	$ad_making=$_POST['ad_making'];
	  }
	  


	  //-- 歷史紀錄 --
	  $new_pdo->hs_tb_name='build_case';
	  $new_pdo->hs_old_id=$Tb_index;
	  $new_pdo->hs_h_location='專案編輯';
	  $new_pdo->hs_h_action_type='update';
	  $new_pdo->hs_h_title='修改專案-'.$_POST['aTitle'];
		//-- 舊資料 --
	  $new_pdo->old_data();

    
    $is_auto_an=empty($_POST['is_auto_an']) ? 0:1;
    
    $param=[  
			              'com_id'=>$_POST['com_id'],
			              'aTitle'=>$_POST['aTitle'],
					   'web_title'=>$_POST['web_title'],
			        'ph_tool_type'=>$_POST['ph_tool_type'],
			             'version'=>$_POST['version'],
			              'format'=>$_POST['format'],
			            'line_txt'=>$_POST['line_txt'],
						  'fb_sel'=>$_POST['fb_sel'],
			              'fb_txt'=>$_POST['fb_txt'],
			               'phone'=>$_POST['phone'],
			          'build_adds'=>$_POST['build_adds'],
			             'marquee'=>$_POST['marquee'],
			         'google_code'=>$_POST['google_code'],
				'google_view_code'=>$_POST['google_view_code'],
				       'head_code'=>$_POST['head_code'],
					   'body_code'=>$_POST['body_code'],
                      'is_auto_an'=>$is_auto_an,
			         'description'=>$_POST['description'],
			             'KeyWord'=>$_POST['KeyWord'],
					  	 'EndDate'=>$_POST['EndDate'],
					 'OnLineOrNot'=>$_POST['OnLineOrNot'],
						  'on_gtm'=>$_POST['on_gtm'],
					   'send_mail'=>$_POST['send_mail'],
                       'send_week'=>$_POST['send_week'],
			           'ad_making'=>$ad_making
		          ];
    $where= ['Tb_index'=>$Tb_index] ;
	pdo_update('build_case', $param, $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
	
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
   }
}
if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM build_case WHERE Tb_index=:Tb_index', $where);
 	$com_id=empty($row['com_id'])? '' : $row['com_id'];
    $is_auto_an=$row['is_auto_an']==0 ? '':'checked';
	$EndDate=empty($row['EndDate']) ? date('Y-m-d', strtotime('+2 year')) : $row['EndDate'];
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>專案資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php?MT_id=<?php echo $_GET['MT_id'];?>" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" >所屬公司</label>
							<div class="col-md-4">
								<select name="com_id" class="form-control">

								  <?php             

                                    $pdo=pdo_conn();
                                    $sql=$pdo->prepare("SELECT Tb_index, com_name FROM company ORDER BY OrderBy DESC, Tb_index ASC");
                                    $sql->execute();
                                    while ($row_com=$sql->fetch(PDO::FETCH_ASSOC)) {
                                       
                                       //if (in_array( $row_com['Tb_index'] , $_SESSION['group_com'])) {
                                         
                                         if ($com_id==$row_com['Tb_index']) {
                                          echo '<option selected value="'.$row_com['Tb_index'].'">'.$row_com['com_name'].'</option>';
                                         }
                                         else{
                                          echo '<option value="'.$row_com['Tb_index'].'">'.$row_com['com_name'].'</option>';
                                         }
                                      // }
                                        
                                    	
                                    }
                                  ?>
									
								</select>
							</div>
							<label class="col-md-2 control-label">版本</label>
							<div class="col-md-4">
								<select name="version" class="form-control">
									<option <?php echo $selected=$row['version']=='1' ? 'selected' : '';?> value="1">正常版</option>
                  <option <?php echo $selected=$row['version']=='2' ? 'selected' : '';?> value="2">全屏滑動版</option>
									<option <?php echo $selected=$row['version']=='0' ? 'selected' : '';?> value="0">簡易版</option>
									<?php 
                                     if(!empty($row['Tb_index'])){
                                     	$selected=$row['version']=='3' ? 'selected' : '';
                                     	echo '<option '.$selected.' value="3">特殊版</option>';
                                     }
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="aTitle">專案名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="aTitle" name="aTitle" value="<?php echo $row['aTitle'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="web_title">網站抬頭</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="web_title" name="web_title" value="<?php echo $row['web_title'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="ad_making">廣告製作</label>
							<div class="col-md-4">
								<select name="ad_making" class="form-control">
									<option <?php echo $selected=$row['ad_making']=='j' ? 'selected' : '';?> value="j">聯創數位</option>
									<option <?php echo $selected=$row['ad_making']=='c' ? 'selected' : '';?> value="c">元際數位</option>
									<option <?php echo $selected=$row['ad_making']!='j' && $row['ad_making']!='c' ? 'selected' : '';?> value="o">其他</option>
								</select>
							</div>
                            
                            <?php 
                              if ($row['ad_making']!='j' && $row['ad_making']!='c') {
                              	$other_dis='display: block;';
                              	$ad_making=explode(',', $row['ad_making']);
                              }
                              else{
                              	$other_dis='display: none;';
                              }
                              
                            ?>

                             <div class="other_ad" style="<?php echo $other_dis;?>">
							  <label class="col-md-1 control-label" >名稱</label>
							  <div class="col-md-2">
							    <input type="text" class="form-control"  name="ad_making_name" value="<?php echo $ad_making[1];?>">
                              </div>

                              <label class="col-md-1 control-label" >網址</label>
							  <div class="col-md-2">
							    <input type="text" class="form-control"  name="ad_making_url" value="<?php echo $ad_making[2];?>">
                              </div>
                             </div>
							
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="ph_tool_type">手機功能欄樣式</label>
							<div class="col-md-4">
								<select name="ph_tool_type" id="ph_tool_type" class="form-control">
									<option value="hor1">橫式造型1</option>
									<option value="hor2">橫式造型2</option>
									<option value="hor3">橫式造型3</option>
									<option value="hor4">橫式造型4</option>
									<option value="hor5">橫式造型5</option>
									<option value="hor_line">橫式造型-低階</option>
									<option value="ver1">直式造型1</option>
									<option value="ver2">直式造型2</option>
									<option value="ver3">直式造型3</option>
									<option value="ver4">直式造型4</option>
									<option value="ver5">直式造型5</option>
									<option value="ver6">直式造型6</option>
								</select>
							</div>
							<label class="col-md-2 control-label" for="ad_making">造型參考</label>
							<div class="col-md-4">
								 <a href="#ph_tool_type_exp" class="btn btn-info fancybox">參考圖示</a>

								 <div style="display: none; " id="ph_tool_type_exp" class="row no-gutters">
								 	<div class="col-md-3">
								 		<h3>橫式造型1</h3>
								 		<img src="../../img/phToolType/hor1.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>橫式造型2</h3>
								 		<img src="../../img/phToolType/hor2.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>橫式造型3</h3>
								 		<img src="../../img/phToolType/hor3.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>橫式造型4</h3>
								 		<img src="../../img/phToolType/hor4.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型1</h3>
								 		<img src="../../img/phToolType/ver1.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型2</h3>
								 		<img src="../../img/phToolType/ver2.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型3</h3>
								 		<img src="../../img/phToolType/ver3.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型4</h3>
								 		<img src="../../img/phToolType/ver4.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型5</h3>
								 		<img src="../../img/phToolType/ver5.JPG" alt="">
								 	</div>
								 	<div class="col-md-3">
								 		<h3>直式造型6</h3>
								 		<img src="../../img/phToolType/ver6.JPG" alt="">
								 	</div>
								 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="logo">專案LOGO</label>
							<div class="col-md-10">
								<input type="file" name="logo" class="form-control" accept="image/*" id="logo" onchange="file_viewer_load_new(this, '#logo_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="logo_box" class="col-md-4">
								
						   </div>
						<?php if(!empty($row['logo'])){?>
							<div  class="col-md-4">
							   <div id="logo" class="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" class="one_del"> X </button>
								  <img id="one_img" src="<?php echo case_URL($row['Tb_index'], 'img/').$row['logo'];?>" alt="請上傳代表圖檔">
								</div>
								<input type="hidden" name="logo_file" value="<?php echo $row['logo'];?>">
							</div>
						<?php }?>		
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="loading_logo">讀取LOGO</label>
							<div class="col-md-10">
								<input type="file" name="loading_logo" class="form-control" accept="image/*" id="loading_logo" onchange="file_viewer_load_new(this, '#loading_logo_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="loading_logo_box" class="col-md-4">
								
						   </div>
						<?php if(!empty($row['loading_logo'])){?>
							<div  class="col-md-4">
							   <div id="loading_logo" class="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" class="one_del"> X </button>
								  <img id="one_img" src="<?php echo case_URL($row['Tb_index'], 'img/').$row['loading_logo'];?>" alt="請上傳代表圖檔">
								</div>
								<input type="hidden" name="loading_logo_file" value="<?php echo $row['loading_logo'];?>">
							</div>
						<?php }?>		
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="aPic">分享圖片</label>
							<div class="col-md-10">
								<input type="file" name="aPic" class="form-control" accept="image/*" id="aPic" onchange="file_viewer_load_new(this, '#img_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="img_box" class="col-md-4">
								
							</div>
						<?php if(!empty($row['aPic'])){?>
							<div  class="col-md-4">
							   <div id="aPic" class="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" class="one_del"> X </button>
								  <img id="one_img" src="<?php echo case_URL($row['Tb_index'], 'img/').$row['aPic'];?>" alt="請上傳代表圖檔">
								</div>
								<input type="hidden" name="aPic_file" value="<?php echo $row['aPic'];?>">
							</div>
						<?php }?>		
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="format">格局說明</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="format" name="format" value="<?php echo $row['format'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label text-info" >LINE 功能分享或加群組</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="line_txt" name="line_txt" value="<?php echo $row['line_txt'];?>">
								<span class="text-danger">請依需求填寫，系統會自動判斷是分享或加群組。 #勿同時輸入兩種</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label text-success" >Facebook 功能選擇</label>
							<div class="col-md-4">
								<select id="fb_sel" name="fb_sel" class="form-control">
									<option value="share">分享臉書</option>
									<option value="fans">臉書紛絲團</option>
								</select>
								<input type="hidden" name="fb_sel_val" value="<?php echo $row['fb_sel'];?>">
							</div>
							<label  class="col-md-2 control-label fb_fans" >臉書紛絲團</label>
							<div class="col-md-4 fb_fans">
								<input type="text" class="form-control" id="fb_txt" name="fb_txt" value="<?php echo $row['fb_txt'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="phone">電話</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="build_adds">基地位置</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="build_adds" name="build_adds" value="<?php echo $row['build_adds'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="marquee">跑馬燈</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="marquee" name="marquee" value="<?php echo $row['marquee'];?>">
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="activity_song">背景音樂</label>
							<div class="col-md-10">
								<input type="file" name="activity_song" class="form-control" accept="audio/*" id="activity_song" onchange="audio_load(this, '#audio_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="audio_box" class="col-md-4">
								
							</div>
						<?php if(!empty($row['activity_song'])){?>
							<div  class="col-md-4">
							   <div id="activity_song" class="img_div">
							    <p>目前音樂</p>
								 <button type="button" id="one_del_audio" class="one_del"> X </button>
								  <audio controls src="<?php echo case_URL($row['Tb_index'], 'audio/').$row['activity_song'];?>">音樂</audio>
								</div>
								 <input type="hidden" name="activity_song_file" value="<?php echo $row['activity_song'];?>">
							</div>
						<?php }?>		
						</div>



						<div class="form-group">
							<label class="col-md-2 control-label" for="activity_img">活動圖片</label>
							<div class="col-md-10">
								<input type="file" name="activity_img" class="form-control" accept="image/*" id="activity_img" onchange="file_viewer_load_new(this, '#img_box2')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="img_box2" class="col-md-4">
								
							</div>
						<?php if(!empty($row['activity_img'])){?>
							<div  class="col-md-4">
							   <div id="activity_img" class="img_div">
							    <p>目前圖檔</p>
								 <button type="button" id="one_del_img" class="one_del"> X </button>
								  <img id="one_img" src="<?php echo case_URL($row['Tb_index'], 'img/').$row['activity_img'];?>" alt="請上傳代表圖檔">
								</div>
								<input type="hidden" name="activity_img_file" value="<?php echo $row['activity_img'];?>">
							</div>
						<?php }?>		
						</div>
 
                        <div class="form-group">
              
							<label class="col-md-2 control-label text-warning" for="google_code">Google分析追蹤碼</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="google_code" name="google_code" value="<?php echo $row['google_code'];?>">
								<span>例如:UA-12345678-1</span>
							</div>
							<label class="col-md-2 control-label text-warning" for="google_view_code">Google分析檢視碼</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="google_view_code" name="google_view_code" value="<?php echo $row['google_view_code'];?>">
								<span>例如:123456789，9碼</span>
							</div>

						</div>

            <div class="form-group">
              <label class="col-md-2 control-label "></label>
              <div class="col-md-4">
                <label class="text-warning">自動更新google 分析 <input type="checkbox" id="is_auto_an" name="is_auto_an" checked value="1" <?php echo $is_auto_an;?>></label>
              </div>
			</div>




			<div class="form-group">
              <label class="col-md-2 control-label ">每週分析報告</label>
              <div class="col-md-4">
                <input type="text" class="form-control" id="send_mail" name="send_mail" value="<?php echo $row['send_mail'];?>" placeholder="電子信箱">
				<span class="text-danger">可以逗號分割多個信箱</span>
			  </div>
			  <label class="col-md-2 control-label ">發報日期</label>
              <div class="col-md-4">
                <select name="send_week" id="send_week" class="form-control" >
					<option value="1">星期一</option>
					<option value="2">星期二</option>
					<option value="3">星期三</option>
					<option value="4">星期四</option>
					<option value="5">星期五</option>
					<option value="6">星期六</option>
					<option value="0">星期日</option>
				</select>
              </div>
			</div>
			
			

						<div class="form-group">
							<label class="col-md-2 control-label" for="description">網站描述</label>
							<div class="col-md-10">
								<textarea style="height: 250px;" class="form-control" id="description" name="description" placeholder="網站描述"><?php echo $row['description'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="KeyWord">關鍵字</label>
							<div class="col-md-10">
								<textarea class="form-control" id="KeyWord" name="KeyWord" placeholder="關鍵字"><?php echo $row['KeyWord'];?></textarea>
								<span>請使用逗點隔開，例如:XXX建案,A,B,XX坪,XX建設</span>
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="head_code">其他代碼(head)</label>
							<div class="col-md-10">
								<textarea style="height: 250px;" class="form-control" id="head_code" name="head_code" placeholder="google分析或 FB 代碼，放在head裡"><?php echo $row['head_code'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="body_code">其他代碼(body)</label>
							<div class="col-md-10">
								<textarea style="height: 250px;" class="form-control" id="body_code" name="body_code" placeholder="google分析或 FB 代碼，放在body裡"><?php echo $row['body_code'];?></textarea>
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="EndDate">停用日期</label>
							<div class="col-md-3">
								<input type="text" class="form-control datepicker_today" id="EndDate" name="EndDate" value="<?php echo $EndDate;?>">
								<span class="text-danger">預設兩年期限</span>
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="on_gtm">啟用預設GTM</label>
							<div class="col-md-10">
								
								<label><input type="radio" name="on_gtm" value="1"> 使用</label>｜
								<label><input type="radio" name="on_gtm" value="0"> 取消</label>

								<input type="hidden" id="on_gtm_input" value="<?php echo $num=!isset($row['on_gtm']) ? 1 : $row['on_gtm']; ?>">
								
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								
								<label><input type="radio" name="OnLineOrNot" value="1"> 啟用</label>｜
								<label><input type="radio" name="OnLineOrNot" value="0"> 停用</label>｜
								<label><input type="radio" name="OnLineOrNot" value="-1"> 下架</label>

								<input type="hidden" id="OnLineOrNot_input" value="<?php echo $num=!isset($row['OnLineOrNot']) ? 1 : $row['OnLineOrNot']; ?>">
								
							</div>
						</div>

						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
						<input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
					</form>
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->




		</div>

		<div class="col-lg-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>儲存您的資料</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<button type="button" class="btn btn-danger btn-block btn-flat" data-toggle="modal" data-target="#settingsModal1" onclick="clean_all()">重設表單</button>
						</div>
						<div class="col-lg-6">
						<?php if(empty($_GET['Tb_index'])){?>
							<button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">儲存</button>
						<?php }else{?>
						    <button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">更新</button>
						<?php }?>
						</div>
					</div>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

    <?php 
     if (!empty($row['ph_tool_type'])) {
       echo "$('#ph_tool_type [value=\"".$row['ph_tool_type']."\"]').prop('selected', true);";
	 }
	 
	 if(!empty($row['send_week'])){
       echo "$('#send_week [value=\"".$row['send_week']."\"]').prop('selected', true);";
	 }
    ?>

	//-- FB --
	if($('[name="fb_sel_val"]').val()=='fans'){
		$('[name="fb_sel"]').val($('[name="fb_sel_val"]').val());
		$('.fb_fans').css('display', 'block');
	}


    //-- 上下線 --
	let OnLineOrNot_input=$('#OnLineOrNot_input').val();
	$(`[name="OnLineOrNot"][value="${OnLineOrNot_input}"]`).prop('checked', true);

	//-- 啟用GTM --
	let on_gtm_input=$('#on_gtm_input').val();
	$(`[name="on_gtm"][value="${on_gtm_input}"]`).prop('checked', true);


          $("#submit_btn").click(function(event) {

          	 if ($('[name="aPic"]').val()!='' && $('[name="aPic"]').val().search(/(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i)==-1) {
          	 	alert('您的專案LOGO圖檔格式錯誤!!');
          	 	return;
          	 }

            if($('[name="activity_song"]').val()!='' && $('[name="activity_song"]').val().search(/(\.mp3)$/i)==-1){
               	alert('您的背景音樂檔格式錯誤!!');
               	return;
            }

             if($('[name="activity_img"]').val()!='' && $('[name="activity_img"]').val().search(/(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i)==-1){
                alert('您的活動圖檔格式錯誤!!');
                return;
            }

             $('#put_form').submit();
          	 
          });
    //------------------------------ 刪除 ---------------------------------
          $(".one_del").click(function(event) { 
			if (confirm('是否要刪除檔案?')) {
			 var data={
			 	        Tb_index: $("#Tb_index").val(),
                            data: $(this).next().attr('src'),
                            col:  $(this).parent().attr('id'),
                            type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $(this).parent().html('');
			}
		});
   

    //--------------------------- FB功能選擇 -------------------------
       $('#fb_sel').change(function(event) {
       	 if ($(this).val()=='fans') {
       	 	$('.fb_fans').css('display', 'block');
       	 }else{
       	 	$('.fb_fans').css('display', 'none');
       	 }
       });


       //------------------ 廣告選擇 -------------------
       $('[name="ad_making"]').change(function(event) {
       	if ($(this).val()=='o') {
       		$('.other_ad').css('display', 'block');
       	}else{
       		$('.other_ad').css('display', 'none');
       	}
       });

      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

