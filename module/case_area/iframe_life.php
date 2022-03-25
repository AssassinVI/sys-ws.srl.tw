<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }

  #txt_fadein_type{ display: none; }
  #img_fadein_type{ display: none; }

  .one_traffic, .one_fun{ float: left; position: relative; width: 33%;}
  .one_traffic label, .one_fun label{ display: block; }
  .top_traffic, .top_fun{ margin-top: 10px; }

  .o_file{ height: 250px;}
  .o_file p{    background-color: #ccc; padding: 5px;}

  .o_l_file{ height: 100px;     width: 130px;}
  .o_l_file p{    background-color: #ccc; padding: 5px;}

  .show_detail{  margin-right: 5px; padding: 5px 9px; border: 1px solid;}
  .life_detail_div{height:500px; overflow: auto;     border: 1px solid; margin: 14px 0;}
  .one_del_life{position: absolute;}

  .add_life{    margin: 15px !important;  margin-left: 0 !important;}

  .form-horizontal .form-group.life_item{position: relative; margin: 20px 0; background-color: #f3f3f4; padding: 15px; border: 1px solid #ccc; border-radius: 10px;}
  .form-horizontal .form-group.life_item button{top:0; left:0;}
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if($_POST){

  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='li'.date('YmdHis').rand(0,99);

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);
    
    $range=implode('|', $_POST['range']);
    $keyword=implode('|', $_POST['keyword']);
    $active=implode('|', $_POST['active']);
    $life_zoom=implode('|', $_POST['life_zoom']);
    $traffic_loc=empty($_POST['traffic_loc']) ? '' : implode('|', $_POST['traffic_loc']);
    $traffic_name=empty($_POST['traffic_name']) ? '' : implode('|', $_POST['traffic_name']);
    $fun_loc=empty($_POST['fun_loc']) ? '' : implode('|', $_POST['fun_loc']);
    $fun_name=empty($_POST['fun_name']) ? '' : implode('|', $_POST['fun_name']);


    //-- 行座標圖片 --
    $traffic_img_txt='';

    if(!empty($_FILES['traffic_img']['name'])){
      $traffic_img_num=count($_FILES['traffic_img']['name']);
      
      for ($i=0; $i <$traffic_img_num ; $i++) { 
        if(!empty($_FILES['traffic_img']['name'][$i])){

          $type=explode('.', $_FILES['traffic_img']['name'][$i]);
          $traffic_img_name=$Tb_index.'_t_'.$i.'.'.$type[count($type)-1];
          $traffic_img_txt.=$traffic_img_name.'|';
          more_fire_upload('traffic_img', $i, $traffic_img_name, $_GET['Tb_index']);
        }
        else{
          $traffic_img_txt.='|';
        }
      }
    }
      
    


    //-- 樂座標圖片 --
    $fun_img_txt='';

    if(!empty($_FILES['fun_img']['name'])){
      $fun_img_num=count($_FILES['fun_img']['name']);
      
      for ($i=0; $i <$fun_img_num ; $i++) { 
        if(!empty( $_FILES['fun_img']['name'][$i])){

          $type=explode('.', $_FILES['fun_img']['name'][$i]);
          $fun_img_name=$Tb_index.'_f_'.$i.'.'.$type[count($type)-1];
          $fun_img_txt.=$fun_img_name.'|';
          more_fire_upload('fun_img', $i, $fun_img_name, $_GET['Tb_index']);
        }
        else{
          $fun_img_txt.='|';
        }
      }
    }
      

    

    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'location'=>$_POST['location'],
       'life_range'=>$range,
       'life_keyword'=>$keyword,
       'life_active'=>$active,
       'life_zoom'=>$life_zoom,
       'traffic_loc'=>$traffic_loc,
       'traffic_name'=>$traffic_name,
       'traffic_img'=>$traffic_img_txt,
       'traffic_zoom'=>$_POST['traffic_zoom'],
       'fun_loc'=>$fun_loc,
       'fun_name'=>$fun_name,
       'fun_img'=>$fun_img_txt,
       'color_type'=>$_POST['color_type'],
       'type'=>$_POST['type'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('life_tb', $param);
    location_up('iframe_life.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];

    $range=implode('|', $_POST['range']);
    $keyword=implode('|', $_POST['keyword']);
    $active=implode('|', $_POST['active']);
    $life_zoom=implode('|', $_POST['life_zoom']);
    $traffic_loc=empty($_POST['traffic_loc']) ? '' : implode('|', $_POST['traffic_loc']);
    $traffic_name=empty($_POST['traffic_name']) ? '' : implode('|', $_POST['traffic_name']);
    $fun_loc=empty($_POST['fun_loc']) ? '' : implode('|', $_POST['fun_loc']);
    $fun_name=empty($_POST['fun_name']) ? '' : implode('|', $_POST['fun_name']);



    //-- 行座標圖片 --
    $traffic_img_txt='';
    if(!empty($_POST['traffic_loc'])){
      $traffic_img_num=count($_POST['traffic_loc']);
      
      for ($i=0; $i <$traffic_img_num ; $i++) { 
        if(!empty($_FILES['traffic_img']['name'][$i])){

          $type=explode('.', $_FILES['traffic_img']['name'][$i]);
          $traffic_img_name=$Tb_index.'_t_'.date('His').'_'.$i.'.'.$type[count($type)-1];
          $traffic_img_txt.=$traffic_img_name.'|';
          more_fire_upload('traffic_img', $i, $traffic_img_name, $_GET['Tb_index']);
        }
        else{

          $traffic_img_txt.=$_POST['old_t_file'][$i].'|';
        }
      }
    }

      



    //-- 樂座標圖片 --
    $fun_img_txt='';
    if(!empty($_POST['fun_loc'])){
      $fun_img_num=count($_POST['fun_loc']);
    
      for ($i=0; $i <$fun_img_num ; $i++) { 
        if(!empty( $_FILES['fun_img']['name'][$i])){

          $type=explode('.', $_FILES['fun_img']['name'][$i]);
          $fun_img_name=$Tb_index.'_f_'.date('His').'_'.$i.'.'.$type[count($type)-1];
          $fun_img_txt.=$fun_img_name.'|';
          more_fire_upload('fun_img', $i, $fun_img_name, $_GET['Tb_index']);
        }
        else{
          $fun_img_txt.=$_POST['old_f_file'][$i].'|';
        }
        
      }
    }
    



    //-- 食衣住行修改 --
    $life_type=['food','doctor','lodging','school','park','bus_station','convenience_store','cafe','bank','gas_station','atm'];
    foreach ($life_type as $type_one) {
      
      if(!empty( $_POST['life_location-'.$type_one][0])){

        // pdo_select("DELETE FROM life_location WHERE case_id=:case_id AND life_type=:life_type", 
        //         ['case_id'=>$_GET['Tb_index'], 'life_type'=>$type_one]);

        $life_num=count($_POST['life_location-'.$type_one]);

        for ($i=0; $i <$life_num ; $i++) {

          $Tb_index_new='ll'.date('YmdHis').rand(0,99);
          $life_id=empty($_POST['life_id-'.$type_one][$i]) ? $Tb_index_new:$_POST['life_id-'.$type_one][$i];

          //-- 上傳圖檔判斷 --
          // if(!empty( $_FILES['life_photo-'.$type_one]['name'][$i])){

          //   $type=explode('.', $_FILES['life_photo-'.$type_one]['name'][$i]);
          //   $life_photo=$life_id.'_'.$type_one.'_'.date('His').'_'.$i.'.'.$type[count($type)-1];
          //   more_fire_upload('life_photo-'.$type_one, $i, $life_photo, $_GET['Tb_index']);
          // }
          // else{
          //   $life_photo=empty($_POST['old_file-'.$type_one][$i]) ? '':$_POST['old_file-'.$type_one][$i];
          // }

          $is_loc=$new_pdo->select("SELECT COUNT(*) as total FROM life_location WHERE Tb_index=:Tb_index AND case_id=:case_id", 
                                  ['Tb_index'=>$life_id, 'case_id'=>$_GET['Tb_index']], 'one');

           $param=[
                'case_location'=>$_POST['case_location-'.$type_one][$i],
                'life_location'=>$_POST['life_location-'.$type_one][$i],
                'life_name'=>$_POST['life_name-'.$type_one][$i],
                // 'life_photo'=>$life_photo,
                'life_phone'=>$_POST['life_phone-'.$type_one][$i],
                'life_addr'=>$_POST['life_addr-'.$type_one][$i],
                'life_eva'=>$_POST['life_eva-'.$type_one][$i],
                'OrderBy'=>($i+1)
            ];

          if(empty($is_loc['total'])){
            //-- 新增食衣住行 --
            //$Tb_index_new='ll'.date('YmdHis').rand(0,99);
            $param['Tb_index']=$Tb_index_new;
            $param['case_id']=$_GET['Tb_index'];
            $param['life_type']=$type_one;
             
            pdo_insert('life_location', $param);
          }
          else{
            $where=['Tb_index'=>$_POST['life_id-'.$type_one][$i]];
            pdo_update('life_location', $param, $where);
          }
          
        }
      }
      
    }

    

      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

      $param=[
       'location'=>$_POST['location'],
       'life_range'=>$range,
       'life_keyword'=>$keyword,
       'life_active'=>$active,
       'life_zoom'=>$life_zoom,
       'traffic_loc'=>$traffic_loc,
       'traffic_name'=>$traffic_name,
       'traffic_img'=>$traffic_img_txt,
       'traffic_zoom'=>$_POST['traffic_zoom'],
       'fun_loc'=>$fun_loc,
       'fun_name'=>$fun_name,
       'fun_img'=>$fun_img_txt,
       'color_type'=>$_POST['color_type'],
       'type'=>$_POST['type'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('life_tb', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);
    location_up('iframe_life.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM life_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);

  $range=explode('|', $row['life_range']);
  $keyword=explode('|', $row['life_keyword']);
  $life_zoom=explode('|', $row['life_zoom']);
  $life_active=explode('|', $row['life_active']);

?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-食醫住行</h2>
    
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
          <!-- <button id="google_get_btn" type="button" class="btn btn-success">google抓取新內容</button> -->
			 		<button id="save_btn" type="button" class="btn btn-primary">儲存</button>      
			 	</div>
			 </div>
			<div class="ibox-content">
				<form id="fun_form" action="#" method="POST" class="form-horizontal" enctype='multipart/form-data'>

           <input type="hidden" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
            
           <div class="form-group">
             <label class="col-sm-2 control-label" ></label>
             <div class="col-sm-10">
            <?php 
             if (empty($row['Tb_index'])) {
            ?>
              <h3>未產生功能，請按下儲存</h3>
            <?php
             }else{
            ?>
              <h3>已產生功能</h3>
            <?php
             }
            ?>
            </div>
           </div> 

            <div class="form-group">
              <label class="col-sm-2 control-label" >食醫住行按鈕類型</label>
              <div class="col-sm-10">
                <select class="form-control" name="type">
                  <option value="0" <?php echo $check=$row['type']=='0' ?  'selected':'';?> >Menu彈出</option>
                  <option value="1" <?php echo $check=$row['type']=='1' ?  'selected':'';?>>滑到指定位置</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" >按鈕配色</label>
              <div class="col-sm-10">
                <select class="form-control" name="color_type">
                  <option value="0" <?php echo $check=$row['color_type']=='0' ?  'selected':'';?> >預設</option>
                  <option value="1" <?php echo $check=$row['color_type']=='1' ?  'selected':'';?>>高檔1</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" >位置座標:</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="location" placeholder="請輸入地圖座標" value="<?php echo  $row['location'];?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" ></label>
              <div class="col-sm-10">
                <p class="text-danger">食醫住行範圍預設: 1000，地圖縮放比預設: 14</p>
              </div>
            </div>
            <?php
              //$life_name=['食','醫','住','育','樂','公園','公車站','咖啡店','銀行','商店','加油站','藥局'];
              $life_name=['食','醫','住','育','公園','公車站','商店','咖啡店','銀行','加油站','ATM'];
              $life_type=['food','doctor','lodging','school','park','bus_station','convenience_store','cafe','bank','gas_station','atm'];
              $life_num=count($life_name);

              for ($i=0; $i <$life_num ; $i++) { 

                $life_row=$new_pdo->select("SELECT * FROM life_location WHERE case_id=:case_id AND life_type=:life_type ORDER BY OrderBy, Tb_index",
                                          ['case_id'=>$_GET['Tb_index'], 'life_type'=>$life_type[$i]]);
                
                $life_d_txt='';
                foreach ($life_row as $life_one) {
                  //-- 圖 --
                  if(!empty($life_one['life_photo'])){
                    $img_html='<a target="_blank" href="https://'.WEB_HOST.'/product_html/'.$life_one['case_id'].'/img/'.$life_one['life_photo'].'"><div class="o_l_file" style="background: url(https://'.WEB_HOST.'/product_html/'.$life_one['case_id'].'/img/'.$life_one['life_photo'].') center; background-size: cover;"><p>目前圖檔</p> </div></a>
                    <input type="hidden" name="old_file-'.$life_type[$i].'[]" value="'.$life_one['life_photo'].'">';
                  }
                  else{
                    $img_html='<input type="hidden" name="old_file-'.$life_type[$i].'[]" value="">';
                  }

                  $life_d_txt.='
                  <div class="form-group life_item">
                      <button type="button" style="z-index: 10;" class="btn btn-danger one_del_life">x</button>
                      <label class="col-sm-1 control-label" >座標:</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="text" name="life_location-'.$life_type[$i].'[]" value="'.$life_one['life_location'].'">
                      </div>
                      <label class="col-sm-1 control-label" >名稱:</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="text" name="life_name-'.$life_type[$i].'[]" value="'.$life_one['life_name'].'">
                      </div>
                      <label class="col-sm-1 control-label" >電話:</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="text" name="life_phone-'.$life_type[$i].'[]" value="'.$life_one['life_phone'].'">
                      </div>
                      <label class="col-sm-1 control-label" >地址:</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="text" name="life_addr-'.$life_type[$i].'[]" value="'.$life_one['life_addr'].'">
                      </div>
                      <label class="col-sm-1 control-label" >評價:</label>
                      <div class="col-sm-2">
                        <input class="form-control" type="text" name="life_eva-'.$life_type[$i].'[]" value="'.$life_one['life_eva'].'">
                      </div>
                      <label class="col-sm-1 control-label" >照片:</label>
                      <div class="col-sm-4">
                        <input class="form-control" type="file" name="life_photo-'.$life_type[$i].'[]" onchange="file_viewer_load_new(this, \'\')">
                        '.$img_html.'
                      </div>
                      <input type="hidden" name="life_id-'.$life_type[$i].'[]" value="'.$life_one['Tb_index'].'">
                      <input type="hidden" name="case_location-'.$life_type[$i].'[]" value="'.$life_one['case_location'].'">
                  </div>';
                  
                  
                }
                
                //-- 判斷啟用 --
                if($i<7 && empty($row['life_active'])){
                  $active_ch='1';
                }
                else{
                  $active_ch=empty($life_active[$i]) ? '0':'1';
                }

                $disabled=$i<4 ? 'disabled': '';
              
                echo '
                <div class="form-group">
                 <label class="col-sm-2 control-label" ><a class="show_detail" href="javascript:;"><i class="fa fa-chevron-down"></i></a> "'.$life_name[$i].'"範圍:</label>
                 <div class="col-sm-1">
                   <input class="form-control" type="text" name="range[]" placeholder="請輸入地圖範圍" value="'.$range[$i].'">
                 </div>

                 <label class="col-sm-1 control-label" >"'.$life_name[$i].'"關鍵字:</label>
                 <div class="col-sm-2">
                   <input class="form-control" type="text" name="keyword[]" placeholder="請輸入關鍵字" value="'.$keyword[$i].'">
                 </div>

                 <div class="col-sm-2">
                  <select class="form-control" name="active[]" '.$disabled.'>
                    <option value="1">啟用中</option>
                    <option value="0">關閉</option>
                  </select>
                  <input type="hidden" class="active_ch" value="'.$active_ch.'">
                 </div>

                 <label class="col-sm-2 control-label" >"'.$life_name[$i].'"地圖縮放比:</label>
                 <div class="col-sm-2">
                   <input class="form-control" type="text" name="life_zoom[]" placeholder="請輸入比率" value="'.$life_zoom[$i].'">
                   <span class="text-danger">縮放比數字越大地圖越近，反之越小越遠</span>
                 </div>

                 <div class="col-sm-12 life_detail_div" style="display:none;">
                 <button type="button" class="btn btn-success add_life" life_type="'.$life_type[$i].'">+ 新增</button>
                   '.$life_d_txt.'
                 </div>
                </div>';
              }
            ?>

             




            <hr>

            <h2>"行"座標，名稱自訂</h2>
            <div class="form-group">
              <div class="col-sm-10">
                <button type="button" id="traffic_btn" class="btn btn-info"><i class="fa fa-plus"></i> 新增座標</button>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-1 control-label" >"行"地圖縮放比:</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="traffic_zoom" placeholder="請輸入地圖座標" value="<?php echo  $row['traffic_zoom'];?>">
                <span class="text-danger">縮放比數字越小地圖越近，反之越大越遠 ，地圖縮放比預設: 14</span>
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-12">
                <ul class="sortable-list connectList agile-list ui-sortable traffic_div" >

                 <?php 
                  if (!empty($row['traffic_loc'])) {
                    
                    $traffic_loc=explode('|', $row['traffic_loc']);
                    $traffic_name=explode('|', $row['traffic_name']);
                    $traffic_img=explode('|', $row['traffic_img']);
                    //$traffic_img=explode();
                    $traffic_num=count($traffic_loc);

                    for ($i=0; $i <$traffic_num ; $i++) { 

                      if(!empty($traffic_img[$i])){
                        $img_html='<div class="o_file" style="background: url(https://'.WEB_HOST.'/product_html/'.$row['case_id'].'/img/'.$traffic_img[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                        <input type="hidden" name="old_t_file[]" value="'.$traffic_img[$i].'">';
                      }
                      else{
                        $img_html='<input type="hidden" name="old_t_file[]" value="">';
                      }
                      
                       echo '<li class="one_traffic">
                              <span class="mark_num">'.($i+1).'</span>
                              <button type="button"  class="btn btn-danger one_del_div">x</button>
                              <label class="top_traffic">座標位置: <input type="text" name="traffic_loc[]" value="'.$traffic_loc[$i].'" class="form-control"></label>
                              <label>座標名稱: <input type="text" name="traffic_name[]" value="'.$traffic_name[$i].'" class="form-control"></label>
                              <label>座標圖片: <input type="file" name="traffic_img[]"  class="form-control" onchange="file_viewer_load_new(this, \'\')"></label>
                              '.$img_html.'
                            </li>';
                    }
                  }
                 ?>


                                                
                </ul>
              </div>
            </div>

            <hr>

             <h2>"樂"額外座標，名稱自訂</h2>
            <div class="form-group">
              <div class="col-sm-10">
                <button type="button" id="fun_btn" class="btn btn-info"><i class="fa fa-plus"></i> 新增座標</button>
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-12">
                <ul class="sortable-list connectList agile-list ui-sortable fun_div" >

                 <?php 
                  if (!empty($row['fun_loc'])) {
                    
                    $fun_loc=explode('|', $row['fun_loc']);
                    $fun_name=explode('|', $row['fun_name']);
                    $fun_img=explode('|', $row['fun_img']);
                    $fun_num=count($fun_loc);

                    for ($i=0; $i <$fun_num ; $i++) { 


                      if(!empty($fun_img[$i])){
                        $img_html='<div class="o_file" style="background: url(https://'.WEB_HOST.'/product_html/'.$row['case_id'].'/img/'.$fun_img[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                        <input type="hidden" name="old_f_file[]" value="'.$fun_img[$i].'">';
                      }
                      else{
                        $img_html='<input type="hidden" name="old_f_file[]" value="">';
                      }
                       
                       echo '<li class="one_fun">
                              <span class="mark_num">'.($i+1).'</span>
                              <button type="button"  class="btn btn-danger one_del_div">x</button>
                              <label class="top_fun">座標位置: <input type="text" name="fun_loc[]" value="'.$fun_loc[$i].'" class="form-control"></label>
                              <label>座標名稱: <input type="text" name="fun_name[]" value="'.$fun_name[$i].'" class="form-control"></label>
                              <label>座標圖片: <input type="file" name="fun_img[]"  class="form-control" onchange="file_viewer_load_new(this, \'\')"></label>
                              '.$img_html.'
                            </li>';
                    }
                  }
                 ?>
                                                
                </ul>
              </div>
            </div>

            <hr>
            

            <div class="form-group">
              <label class="col-sm-1 control-label" for="OnLineOrNot">是否上線</label>
              <div class="col-sm-11">
                <input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
              </div>
            </div>

            
         
				</form>
			</div>
		</div>
	</div>
</div>



</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
  function get_an (life_type) {
    $.ajax({
        type: "POST",
        url: "life_ajax.php",
        data: {
          type:'google_get',
          Tb_index: $('[name="Tb_index"]').val(),
          life_type: life_type
        },
         //dataType: "json",
        success: function (data) {
          console.log(life_type);
        }
      });
  }
	$(document).ready(function() {

    //-- 預設啟用 --
    $.each($('.active_ch'), function (index, valueOfElement) { 
       $(this).prev().val($(this).val());
    });
    

    //-- google 抓取新資料 --
    $('#google_get_btn').click(function (e) { 
      e.preventDefault();
      // get_an ('food');
      // get_an ('doctor');
      // get_an ('lodging');
      // get_an ('school');
      // get_an ('park');
      // get_an ('bus_station');
      // get_an ('convenience_store');
    });

    //-- 顯示食衣住行詳細資料 --
    $('.show_detail').click(function (e) { 

      if($(this).find('i').attr('class').indexOf('down')!=-1){
        $(this).find('i').removeClass('fa-chevron-down');
        $(this).find('i').addClass('fa-chevron-up');

        $(this).parents('.form-group').find('.life_detail_div').show();
      }
      else{
        $(this).find('i').removeClass('fa-chevron-up');
        $(this).find('i').addClass('fa-chevron-down');

        $(this).parents('.form-group').find('.life_detail_div').hide();
      }
    });


    //-- 清除食衣住行 --
    $('.life_detail_div').on('click', '.one_del_life', function () {
      let life_type=$(this).parents('.life_detail_div').find('.add_life').attr('life_type');
      let Tb_index=$(this).parent().find(`[name="life_id-${life_type}[]"]`).val();
      let life_name=$(this).parent().find(`[name="life_name-${life_type}[]"]`).val();
      let _this=$(this);
      //console.log($(this).parent().find(`[name="life_id-${life_type}[]"]`).val());
      if(confirm(`是否要刪除 "${life_name}" ? \n確認之後將無法再復原`)){
        $.ajax({
        type: "POST",
        url: "life_ajax.php",
        data: {
          type:'del_life',
          Tb_index: Tb_index
        },
        success: function (data) {
          _this.parent().remove();
        }
        });
      }
    });



    //-- 新增食衣住行 --
    $('.add_life').click(function (e) { 
      
      var life_type=$(this).attr('life_type');
      //var life_id=$(this).next().find('[name="life_id-'+life_type+'[]"]').val();
      var case_location=$(this).next().find('[name="case_location-'+life_type+'[]"]').val();
      //console.log($(this).next());

      var txt=`<div class="form-group">
                 <button type="button" style="z-index: 10;" class="btn btn-danger one_del_life">x</button>
                 <label class="col-sm-1 control-label" >座標:</label>
                   <div class="col-sm-3">
                      <input class="form-control" type="text" name="life_location-${life_type}[]" value="">
                   </div>
                <label class="col-sm-1 control-label" >名稱:</label>
                   <div class="col-sm-3">
                      <input class="form-control" type="text" name="life_name-${life_type}[]" value="">
                   </div>
                <label class="col-sm-1 control-label" >電話:</label>
                   <div class="col-sm-3">
                      <input class="form-control" type="text" name="life_phone-${life_type}[]" value="">
                   </div>
                <label class="col-sm-1 control-label" >地址:</label>
                    <div class="col-sm-3">
                      <input class="form-control" type="text" name="life_addr-${life_type}[]" value="">
                   </div>
                <label class="col-sm-1 control-label" >評價:</label>
                   <div class="col-sm-3">
                      <input class="form-control" type="text" name="life_eva-${life_type}[]" value="">
                   </div>
                <label class="col-sm-1 control-label" >照片:</label>
                   <div class="col-sm-4">
                      <input class="form-control" type="file" name="life_photo-${life_type}[]">
                      <input type="hidden" name="old_file-${life_type}[]" value="">
                   </div>
                   <input type="hidden" name="life_id-${life_type}[]" value="">
                   <input type="hidden" name="case_location-${life_type}[]" value="${case_location}">
              </div>`;

        //-- 加在最前面 --
        $(this).after(txt);
    });


      $('#save_btn').click(function(event) {

        if($('[name="active[]"] [value="1"]:selected').length!=7){
           alert("啟用的數量錯誤\n需滿7個生活機能");
        }
        else{
          $('[name="active[]"]').prop('disabled', false);
          $('#fun_form').submit();
        }
         
      });


      //-- 行 新增座標 --
      var traffic_num=$('[name="traffic_loc[]"]').length;
      $('#traffic_btn').click(function(event) {

        var txt=`<li class="one_traffic">
                    <span class="mark_num">${(traffic_num+1)}</span>
                    <button type="button"  class="btn btn-danger one_del_div">x</button>
                    <label class="top_traffic">座標位置: <input type="text" name="traffic_loc[]"  class="form-control"></label>
                    <label>座標名稱: <input type="text" name="traffic_name[]"  class="form-control"></label>
                    <label>座標圖片: <input type="file" name="traffic_img[]"  class="form-control" onchange="file_viewer_load_new(this, '')"></label>
                    <input type="hidden" name="old_t_file[]" value="">
                 </li>`;

         $('.traffic_div').append(txt);
        traffic_num++;
      });
      
      $('.traffic_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();
         if (confirm('是否要刪除此座標??')) {
          $(this).parent().remove();
        }
      });

      // 拖曳多圖檔
       $(".traffic_div").sortable({
         connectWith: ".traffic_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".traffic_div" ).sortable( "toArray" );
         }
      }).disableSelection();

       

      //-- 樂 新增座標 --
      var fun_num=$('[name="fun_loc[]"]').length;
      $('#fun_btn').click(function(event) {

        var txt=`<li class="one_fun">
                    <span class="mark_num">${(fun_num+1)}</span>
                    <button type="button"  class="btn btn-danger one_del_div">x</button>
                    <label class="top_fun">座標位置: <input type="text" name="fun_loc[]"  class="form-control"></label>
                    <label>座標名稱: <input type="text" name="fun_name[]"  class="form-control"></label>
                    <label>座標圖片: <input type="file" name="fun_img[]"  class="form-control" onchange="file_viewer_load_new(this, '')"></label>
                    <input type="hidden" name="old_f_file[]" value="">
                 </li>`;

         $('.fun_div').append(txt);
        fun_num++;
      });
      
      $('.fun_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();
         if (confirm('是否要刪除此座標??')) {
          $(this).parent().remove();
        }
      });

      // 拖曳多圖檔
       $(".fun_div").sortable({
         connectWith: ".fun_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".fun_div" ).sortable( "toArray" );
         }
      }).disableSelection();


      // 拖曳多圖檔
      $(".life_detail_div").sortable({
         connectWith: ".life_detail_div",
      }).disableSelection();

      
	 });

</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
