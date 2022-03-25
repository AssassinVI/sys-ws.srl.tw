<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }

  #txt_fadein_type{ display: none; }
  #img_fadein_type{ display: none; }
	#Animate_txt{ display: inline-block; }

</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

$test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 

      if (!empty($_POST['back_img'])) {
            $param=['back_img'=>''];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('base_word', $param, $where, $test_case_db);
            unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['back_img']);
      }
      elseif(!empty($_POST['base_img'])){
    
        //----------------------- 圖片刪除 -------------------------------
        $sel_where=['Tb_index'=>$_POST['fun_id']];
        $otr_file=pdo_select('SELECT base_img FROM base_word WHERE Tb_index=:Tb_index', $sel_where, $test_case_db);
        $otr_file=explode(',', $otr_file['base_img']);
        for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
           if ($otr_file[$i]!=$_POST['base_img']) {
            $new_file.=$otr_file[$i].',';
           }else{
             unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['base_img']);
           }
        }
        $param=['base_img'=>$new_file];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('base_word', $param, $where, $test_case_db);
         exit();
      }

      //----------------------- 手機圖片刪除 -------------------------------
      elseif(!empty($_POST['base_img_ph'])){

        $sel_where=['Tb_index'=>$_POST['fun_id']];
        $otr_file=pdo_select('SELECT base_img_ph FROM base_word WHERE Tb_index=:Tb_index', $sel_where, $test_case_db);
        $otr_file=explode(',', $otr_file['base_img_ph']);
        for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
           if ($otr_file[$i]!=$_POST['base_img_ph']) {
            $new_file.=$otr_file[$i].',';
           }else{
             unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['base_img_ph']);
           }
        }
        $param=['base_img_ph'=>$new_file];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('base_word', $param, $where, $test_case_db);
      }
       exit();

    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='bs'.date('YmdHis').rand(0,99);

   
    //-------------- 圖檔新增 ------------------
    if (!empty($_FILES['base_img'])){

          for ($i=0; $i <count($_FILES['base_img']['name']) ; $i++) { 

               $type=explode('.', $_FILES['base_img']['name'][$i]);
               $base_img.=$Tb_index.'_base_'.$i.'.'.$type[count($type)-1].',';
               more_fire_upload('base_img', $i, $Tb_index.'_base_'.$i.'.'.$type[count($type)-1], $_GET['Tb_index']);
             
          }
        }
        else{
          $base_img='';
        }


    //-------------- 手機圖片檔新增 ------------------
    if (!empty($_FILES['base_img_ph'])){

          for ($i=0; $i <count($_FILES['base_img_ph']['name']) ; $i++) { 

               $type=explode('.', $_FILES['base_img_ph']['name'][$i]);
               $base_img_ph.=$Tb_index.'_base-ph_'.$i.'.'.$type[count($type)-1].',';
               more_fire_upload('base_img_ph', $i, $Tb_index.'_base-ph_'.$i.'.'.$type[count($type)-1], $_GET['Tb_index']);
             
          }
        }
        else{
          $base_img_ph='';
        }


    //===================== 背景圖 ========================
      if (!empty($_FILES['back_img']['name'])){

        if (test_img($_FILES['back_img']['name'])) {
         $type=explode('/', $_FILES['back_img']['type']);
         $back_img=$Tb_index.'_back.'.$type[1];
         fire_upload('back_img', $back_img, $_GET['Tb_index']);
        }
        else{
         location_up('iframe_base.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$_GET['fun_id'].'&rel_id='.$_GET['rel_id'],'檔案錯誤!請上傳正確檔案');
          exit();
        }
    }else{
      $back_img='';
    }

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']], $test_case_db);

    $line_show=empty($_POST['line_show'])? 0 : 1;
    $zoomin_img=empty($_POST['zoomin_img'])? 0 : 1;
    $txt_fadein=empty($_POST['txt_fadein']) ? '' : $_POST['txt_fadein_type'];
    $img_fadein=empty($_POST['img_fadein']) ? '' : $_POST['img_fadein_type'];
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'aTitle'=>$_POST['aTitle'],
       'Title_two'=>$_POST['Title_two'],
       'content'=>$_POST['content'],
       'base_img'=>$base_img,
       'base_img_ph'=>$base_img_ph,
       'back_img'=>$back_img,
       'txt_fadein'=>$txt_fadein,
       'img_fadein'=>$img_fadein,
       'ImgWord_type'=>$_POST['ImgWord_type'],
       'ImgWord_ph_type'=>$_POST['ImgWord_ph_type'],
       'zoomin_img'=>$zoomin_img,
       'line_show'=>$line_show,
       'OnLineOrNot'=>$OnLineOrNot,
       'StartDate'=>date('Y-m-d H:i:s')
    ];
    pdo_insert('base_word', $param, $test_case_db);
    location_up('iframe_base.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    
    //-------------- 圖檔修改 ------------------

    if (!empty($_FILES['base_img'])) {
        $sel_where=['Tb_index'=>$Tb_index];
        $now_file =pdo_select("SELECT base_img FROM base_word WHERE Tb_index=:Tb_index", $sel_where, $test_case_db);
        if (!empty($now_file['base_img'])) {
           $sel_file=explode(',', $now_file['base_img']);
           $file_num=explode('_', $sel_file[count($sel_file)-2]);
           $file_num=explode('.', $file_num[2]);
           $file_num=(int)$file_num[0]+1;
        }else{
           $file_num=0;
        }
        for ($i=0; $i <count($_FILES['base_img']['name']) ; $i++) { 

            if (!empty($_FILES['base_img']['name'][$i])) {
               $type=explode('.', $_FILES['base_img']['name'][$i]);
               $base_img.=$Tb_index.'_base_'.($file_num+$i).'.'.$type[count($type)-1].',';
               more_fire_upload('base_img', $i, $Tb_index.'_base_'.($file_num+$i).'.'.$type[count($type)-1], $_GET['Tb_index']);

               unlink('../../../product_html/'.$_GET['Tb_index'].'/img/'.$_POST['old_file'][$i]);
            }
            else{
               $base_img.=$_POST['old_file'][$i].',';
            }
        }
         
        $base_img_param=['base_img'=>$base_img];
        $base_img_where=['Tb_index'=>$Tb_index];
        pdo_update('base_word', $base_img_param, $base_img_where, $test_case_db);
      }

    //-------------- 圖檔修改-END ------------------



    //-------------- 手機圖檔修改 ------------------

    if (!empty($_FILES['base_img_ph'])) {
        $sel_where=['Tb_index'=>$Tb_index];
        $now_file =pdo_select("SELECT base_img_ph FROM base_word WHERE Tb_index=:Tb_index", $sel_where, $test_case_db);
        if (!empty($now_file['base_img_ph'])) {
           $sel_file=explode(',', $now_file['base_img_ph']);
           $file_num=explode('_', $sel_file[count($sel_file)-2]);
           $file_num=explode('.', $file_num[2]);
           $file_num=(int)$file_num[0]+1;
        }else{
           $file_num=0;
        }
        for ($i=0; $i <count($_FILES['base_img_ph']['name']) ; $i++) { 

            if (!empty($_FILES['base_img_ph']['name'][$i])) {
               $type=explode('.', $_FILES['base_img_ph']['name'][$i]);
               $base_img_ph.=$Tb_index.'_base-ph_'.($file_num+$i).'.'.$type[count($type)-1].',';
               more_fire_upload('base_img_ph', $i, $Tb_index.'_base-ph_'.($file_num+$i).'.'.$type[count($type)-1], $_GET['Tb_index']);

               unlink('../../../product_html/'.$_GET['Tb_index'].'/img/'.$_POST['old_ph_img'][$i]);
            }
            else{
               $base_img_ph.=$_POST['old_ph_img'][$i].',';
            }
        }
         
        $base_img_ph_param=['base_img_ph'=>$base_img_ph];
        $base_img_ph_where=['Tb_index'=>$Tb_index];
        pdo_update('base_word', $base_img_ph_param, $base_img_ph_where, $test_case_db);
      }

    //-------------- 手機圖檔修改-END ------------------



    //===================== 背景圖 ========================

    if (!empty($_FILES['back_img']['name'])) {

        if (test_img($_FILES['back_img']['name'])){

         $type=explode('/', $_FILES['back_img']['type']);
         $back_img=$Tb_index.'_back'.date('His').'.'.$type[1];
         fire_upload('back_img', $back_img, $_GET['Tb_index']);
        $back_img_param=['back_img'=>$back_img];
        $back_img_where=['Tb_index'=>$Tb_index];
        pdo_update('base_word', $back_img_param, $back_img_where, $test_case_db);

        }
        else{

         location_up('iframe_base.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index,'檔案錯誤!請上傳正確檔案');
         exit();
        }
      }


      $line_show=empty($_POST['line_show'])? 0 : 1;
      $zoomin_img=empty($_POST['zoomin_img'])? 0 : 1;
      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;
      
      $txt_fadein=empty($_POST['txt_fadein']) ? '' : $_POST['txt_fadein_type'];
      $img_fadein=empty($_POST['img_fadein']) ? '' : $_POST['img_fadein_type'];

      $param=[
       'aTitle'=>$_POST['aTitle'],
       'Title_two'=>$_POST['Title_two'],
       'content'=>$_POST['content'],
       'txt_fadein'=>$txt_fadein,
       'img_fadein'=>$img_fadein,
       'ImgWord_type'=>$_POST['ImgWord_type'],
       'ImgWord_ph_type'=>$_POST['ImgWord_ph_type'],
       'zoomin_img'=>$zoomin_img,
       'line_show'=>$line_show,
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('base_word', $param, ['Tb_index'=>$Tb_index], $test_case_db);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index], $test_case_db);
    location_up('iframe_base.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], $test_case_db);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM base_word WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']], $test_case_db);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-基本圖文 <span class="text-danger">(此為測試修改區)</span></h2>
      
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 		<button id="save_btn" type="button" class="btn btn-primary">儲存</button>      
			 	</div>
			 </div>
			<div class="ibox-content">
				<form id="fun_form" action="#" method="POST" class="form-horizontal" enctype='multipart/form-data'>
				   <div class="form-group">
              <label class="col-sm-1 control-label" for="play_speed">主標</label>
              <div class="col-sm-11">
                <textarea id="play_speed" name="aTitle" class="form-control"><?php echo $row['aTitle'];?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-1 control-label" for="Title_two">副標</label>
              <div class="col-sm-11">
                <textarea id="ckeditor" name="Title_two" class="form-control"><?php echo $row['Title_two'];?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-1 control-label" for="content">內容</label>
              <div class="col-sm-11">
                <textarea id="ckeditor1" name="content" class="form-control"><?php echo $row['content'];?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-1 control-label" for="base_img">圖片</label>
              <div class="col-sm-11">
               <button type="button" id="new_OtherFile" class="btn btn-info"><i class="fa fa-plus"></i> 新增檔案</button><br>
                <span class="text-danger">順序由左至右，由上到下</span>
              </div>
            </div>

            <div class="form-group">
               <label class="col-md-2 control-label" ></label>

              <div class="col-md-10">
                <ul class="sortable-list connectList agile-list ui-sortable OtherFile_div" >

                  <?php 
                       if(!empty($row['base_img'])){
                                  $base_img=explode(',', $row['base_img']);
                                   for ($i=0; $i <count($base_img)-1 ; $i++) { 
                                     $img_txt='<li class="oneFile_div">
                                                   <span class="mark_num">'.($i+1).'</span>
                                                   <div class="">
                                                     <input type="file" name="base_img[]" class="form-control" id="base_img" onchange="file_viewer_load_new(this, \'#other_div'.$i.'\')">
                                                     <button type="button" class="btn btn-danger one_del_div">x</button>
                                                   </div>
                                                   <div id="other_div'.$i.'" class="other_div"></div>
                                                   <div class="old_file" style="background: url(../../../product_html/'.$_GET['Tb_index'].'/img/'.$base_img[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                                                   <input type="hidden" name="old_file[]" value="'.$base_img[$i].'">
                                                 </li>';
                                                 echo $img_txt;
                                              }
                                           }
                  ?>

                </ul>
                </div>
            </div>


            <div class="form-group">
              <label class="col-sm-1 control-label" >手機圖片</label>
              <div class="col-sm-11">
               <button type="button" id="new_ph_img" class="btn btn-info"><i class="fa fa-plus"></i> 新增檔案</button><br>
                <span class="text-danger">順序由左至右，由上到下</span>
              </div>
              
            </div>

            <div class="form-group">
               <label class="col-md-2 control-label" ></label>

              <div class="col-md-10">
                <ul class="sortable-list connectList agile-list ui-sortable ph_img_div" >

                  <?php 
                       if(!empty($row['base_img_ph'])){
                                  $base_img_ph=explode(',', $row['base_img_ph']);
                                   for ($i=0; $i <count($base_img_ph)-1 ; $i++) { 
                                     $img_txt='<li class="oneFile_div">
                                                   <span class="mark_num">'.($i+1).'</span>
                                                   <div class="">
                                                     <input type="file" name="base_img_ph[]" class="form-control" id="base_img_ph" onchange="file_viewer_load_new(this, \'#one_ph_img_div'.$i.'\')">
                                                     <button type="button" class="btn btn-danger one_del_div">x</button>
                                                   </div>
                                                   <div id="one_ph_img_div'.$i.'" class="other_div"></div>
                                                   <div class="old_file" style="background: url(../../../product_html/'.$_GET['Tb_index'].'/img/'.$base_img_ph[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                                                   <input type="hidden" name="old_ph_img[]" value="'.$base_img_ph[$i].'">
                                                 </li>';
                                                 echo $img_txt;
                                              }
                                           }
                  ?>

                </ul>
                </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2 control-label" for="back_img">背景圖片</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="back_img" name="back_img" onchange="file_viewer_load_new(this,'#back_box')">
              </div>
            </div>

            <div class="form-group">
               <label class="col-md-2 control-label" ></label>
               <div id="back_box" class="col-md-4">
                
              </div>
            <?php if(!empty($row['back_img'])){
               $back_img_url='../../../product_html/'.$_GET['Tb_index'].'/img/'.$row['back_img'];
              ?>
              <div  class="col-md-4">
                 <div id="img_div" >
                  <p>目前圖檔</p>
                 <button type="button" id="one_del_img"> X </button>
                  <img id="one_img" src="<?php echo $back_img_url;?>" alt="請上傳代表圖檔">
                  <input type="hidden" value="<?php echo $row['back_img'];?>">
                </div>
              </div>
            <?php }?>   
            </div>


            <div class="form-group">
              <label class="col-sm-2 control-label" for="">圖文動畫效果</label>
              <div class="col-sm-3">
                <input type="checkbox" id="txt_fadein" name="txt_fadein" value="1"> <label for="txt_fadein">文字特效</label><br>
                特效:
                <select id="txt_fadein_type" name="txt_fadein_type" onchange="animate_select('txt_fadein_type')">
                                     <optgroup label="跳入系列">
                                     <option value="bounceIn">跳入</option>
                                     <option value="bounceInDown">跳入(下)</option>
                                     <option value="bounceInLeft">跳入(左)</option>
                                     <option value="bounceInRight">跳入(右)</option>
                                     <option value="bounceInUp">跳入(上)</option>
                                   </optgroup>
                                   <optgroup label="飛入系列">
                                     <option value="fadeIn">飛入</option>
                                     <option value="fadeInDown">飛入(下)</option>
                                     <option value="fadeInLeft">飛入(左)</option>
                                     <option value="fadeInRight">飛入(右)</option>
                                     <option value="fadeInUp">飛入(上)</option>
                                   </optgroup>
                                   <optgroup label="3D翻轉系列">
                                     <option value="flip">360翻轉(水平)</option>
                                     <option value="flipInX">180翻轉(垂直)</option>
                                     <option value="flipInY">180翻轉(水平)</option>
                                   </optgroup>
                                   <optgroup label="平面旋轉系列">
                                     <option value="rotateIn">360旋轉</option>
                                     <option value="rotateInDownLeft">90度右下旋轉</option>
                                     <option value="rotateInDownRight">90度左下旋轉</option>
                                     <option value="rotateInUpLeft">90度右上旋轉</option>
                                     <option value="rotateInUpRight">90度左上旋轉</option>
                                   </optgroup>
                                   <optgroup label="放大系列">
                                     <option value="zoomIn">放大</option>
                                     <option value="zoomInDown">放大(下)</option>
                                     <option value="zoomInLeft">放大(左)</option>
                                     <option value="zoomInRight">放大(右)</option>
                                     <option value="zoomInUp">放大(上)</option>
                                   </optgroup>
                                   </select>
              </div>
              <div class="col-sm-3">
                <input type="checkbox" id="img_fadein" name="img_fadein" value="1"> <label for="img_fadein">圖片特效</label><br>
                特效:
                <select id="img_fadein_type" name="img_fadein_type" onchange="animate_select('img_fadein_type')">
                                   <optgroup label="跳入系列">
                                     <option value="bounceIn">跳入</option>
                                     <option value="bounceInDown">跳入(下)</option>
                                     <option value="bounceInLeft">跳入(左)</option>
                                     <option value="bounceInRight">跳入(右)</option>
                                     <option value="bounceInUp">跳入(上)</option>
                                   </optgroup>
                                   <optgroup label="飛入系列">
                                     <option value="fadeIn">飛入</option>
                                     <option value="fadeInDown">飛入(下)</option>
                                     <option value="fadeInLeft">飛入(左)</option>
                                     <option value="fadeInRight">飛入(右)</option>
                                     <option value="fadeInUp">飛入(上)</option>
                                   </optgroup>
                                   <optgroup label="3D翻轉系列">
                                     <option value="flip">360翻轉(水平)</option>
                                     <option value="flipInX">180翻轉(垂直)</option>
                                     <option value="flipInY">180翻轉(水平)</option>
                                   </optgroup>
                                   <optgroup label="平面旋轉系列">
                                     <option value="rotateIn">360旋轉</option>
                                     <option value="rotateInDownLeft">90度右下旋轉</option>
                                     <option value="rotateInDownRight">90度左下旋轉</option>
                                     <option value="rotateInUpLeft">90度右上旋轉</option>
                                     <option value="rotateInUpRight">90度左上旋轉</option>
                                   </optgroup>
                                   <optgroup label="放大系列">
                                     <option value="zoomIn">放大</option>
                                     <option value="zoomInDown">放大(下)</option>
                                     <option value="zoomInLeft">放大(左)</option>
                                     <option value="zoomInRight">放大(右)</option>
                                     <option value="zoomInUp">放大(上)</option>
                                   </optgroup>
                </select>
              </div>
              <div class="col-sm-4">
                <h1 id="Animate_txt" >Animate</h1>
                <div><button type="button" id="re_animate_btn" class="btn btn-default" onclick="animate_btn()">重播一次</button></div>
                <input type="hidden" id="re_animate">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="line_show">圖文排版樣式(電腦)</label>
              <div class="col-sm-10">
                <label><input type="radio" name="ImgWord_type" value="1" checked> 上圖下文</label>｜
                <label><input type="radio" name="ImgWord_type" value="2"> 上文下圖</label>｜
                <label><input type="radio" name="ImgWord_type" value="3"> 左圖右文</label>｜
                <label><input type="radio" name="ImgWord_type" value="4"> 左文右圖</label>｜
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="line_show">圖文排版樣式(手機)</label>
              <div class="col-sm-10">
                <label><input type="radio" name="ImgWord_ph_type" value="1" checked> 上圖下文</label>｜
                <label><input type="radio" name="ImgWord_ph_type" value="2"> 上文下圖</label>｜
              </div>
            </div>

            <!-- <div class="form-group">
              <label class="col-sm-2 control-label" for="line_show">是否顯示分割線</label>
              <div class="col-sm-10">
                <input style="width: 20px; height: 20px;" id="line_show" name="line_show" type="checkbox" value="1" <?php //echo $check=!isset($row['line_show']) || $row['line_show']==1 ? 'checked' : ''; ?>  />
              </div>
            </div> -->


            <div class="form-group">
              <label class="col-sm-2 control-label" for="zoomin_img">是否圖片放大</label>
              <div class="col-sm-10">
                <input style="width: 20px; height: 20px;" id="zoomin_img" name="zoomin_img" type="checkbox" value="1" <?php echo $check=$row['zoomin_img']==1 ? 'checked' : ''; ?>  />
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2 control-label" for="OnLineOrNot">是否上線</label>
              <div class="col-sm-10">
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
	$(document).ready(function() {

    //----------- 文字動畫判斷 -------------
    <?php 
      if(!empty($row['txt_fadein'])){
    ?>
      $('#txt_fadein').prop('checked', true);
      $('#txt_fadein_type').css('display', 'block');
      $('#txt_fadein_type [value="<?php echo $row['txt_fadein']?>"]').prop('selected', true);
    <?php
      }
    ?>


    //----------- 圖片動畫判斷 -------------
    <?php 
      if(!empty($row['img_fadein'])){
    ?>
      $('#img_fadein').prop('checked', true);
      $('#img_fadein_type').css('display', 'block');
      $('#img_fadein_type [value="<?php echo $row['img_fadein']?>"]').prop('selected', true);
    <?php
      }
    ?>

    //------------- 圖文排版樣式(電腦) ---------------
    <?php if(!empty($row['ImgWord_type'])){ ?>
     $('[name="ImgWord_type"][value="<?php echo $row['ImgWord_type'];?>"]').prop('checked', true);

    <?php }else{ ?>
      $('[name="ImgWord_type"][value="1"]').prop('checked', true);
    <?php } ?>


    //------------- 圖文排版樣式(手機) ---------------
    <?php if(!empty($row['ImgWord_ph_type'])){ ?>
     $('[name="ImgWord_ph_type"][value="<?php echo $row['ImgWord_ph_type'];?>"]').prop('checked', true);

    <?php }else{ ?>
      $('[name="ImgWord_ph_type"][value="1"]').prop('checked', true);
    <?php } ?>



      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });


    //------------------------------ 刪背景圖 ---------------------------------
    $("#one_del_img").click(function(event) { 
      if (confirm('是否要刪除檔案?')) {
       var data={
                      case_id:'<?php echo $_GET['Tb_index'];?>',
                      fun_id: '<?php echo $_GET['fun_id'];?>',
                       back_img: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_base.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });

      //------------------------------ 刪圖檔 ---------------------------------
    $(".one_del_file").click(function(event) { 
      if (confirm('是否要刪除檔案?')) {
       var data={
                      case_id:'<?php echo $_GET['Tb_index'];?>',
                      fun_id: '<?php echo $_GET['fun_id'];?>',
                       base_img: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_base.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });


    //------------------------------ 啟用動態文字或圖片 ----------------------------
    $('#txt_fadein').change(function(event) {
       if($(this).prop('checked')==true){
          $('#txt_fadein_type').css('display', 'block');
       }else{
          $('#txt_fadein_type').css('display', 'none');
       }
    });

    $('#img_fadein').change(function(event) {
       if($(this).prop('checked')==true){
          $('#img_fadein_type').css('display', 'block');
       }else{
          $('#img_fadein_type').css('display', 'none');
       }
    });


    //==================================== 圖片 ===================================
      //-- 多檔刪除 --
      $('.OtherFile_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();

        if (confirm('是否要刪除檔案?')){
        
          if ($(this).parent().next().next().next().length>0) {
             $.ajax({
             url: 'iframe_base.php',
             type: 'POST',
             data: {
               fun_id: '<?php echo $_GET['fun_id'];?>',
               case_id: '<?php echo $_GET['Tb_index'];?>',
               base_img: $(this).parent().next().next().next().val(),
               type: 'delete'
             }

            });
          }
        $(this).parent().parent().remove();
      }
      });


       // 新增多檔
       var otherfile_num=$('[name="base_img[]"]').length;
       $('#new_OtherFile').click(function(event) {

         var otherfile_txt='<li class="oneFile_div">'
                           +'<span class="mark_num">'+(otherfile_num+1)+'</span>'
                           +'<div class="">'
                             + '<input type="file"  name="base_img[]" class="form-control" id="OtherFile" onchange="file_viewer_load_new(this, \'#other_div'+otherfile_num+'\')">'
                              +'<button type="button"  class="btn btn-danger one_del_div">x</button>'
                          +'</div>'
                             +'<div id="other_div'+otherfile_num+'" class="other_div">'
                             +'</div>'
                             +'<input type="hidden" name="old_file[]" value="">'
                          +'</li>';

         $('.OtherFile_div').append(otherfile_txt);
         otherfile_num++;
       });

      
      // 拖曳多圖檔
       $(".OtherFile_div").sortable({
         connectWith: ".OtherFile_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".OtherFile_div" ).sortable( "toArray" );
         }
      }).disableSelection();


    //==================================== 手機圖片 ===================================
      //-- 多檔刪除 --
      $('.ph_img_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();

        if (confirm('是否要刪除檔案?')){
        
          if ($(this).parent().next().next().next().length>0) {
             $.ajax({
             url: 'iframe_base.php',
             type: 'POST',
             data: {
               fun_id: '<?php echo $_GET['fun_id'];?>',
               case_id: '<?php echo $_GET['Tb_index'];?>',
               base_img_ph: $(this).parent().next().next().next().val(),
               type: 'delete'
             }

            });
          }
        $(this).parent().parent().remove();
      }
      });


       // 新增多檔
       var ph_img_num=$('[name="base_img_ph[]"]').length;
       $('#new_ph_img').click(function(event) {

         var ph_img_txt='<li class="oneFile_div">'
                           +'<span class="mark_num">'+(ph_img_num+1)+'</span>'
                           +'<div class="">'
                             + '<input type="file"  name="base_img_ph[]" class="form-control" id="OtherFile" onchange="file_viewer_load_new(this, \'#one_ph_img_div'+ph_img_num+'\')">'
                              +'<button type="button"  class="btn btn-danger one_del_div">x</button>'
                          +'</div>'
                             +'<div id="one_ph_img_div'+ph_img_num+'" class="other_div">'
                             +'</div>'
                             +'<input type="hidden" name="old_ph_img[]" value="">'
                          +'</li>';

         $('.ph_img_div').append(ph_img_txt);
         ph_img_num++;
       });

      
      // 拖曳多圖檔
       $(".ph_img_div").sortable({
         connectWith: ".ph_img_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".ph_img_div" ).sortable( "toArray" );
         }
      }).disableSelection();



	});

   //-------------------------------- 動畫展示 --------------------------------
 function animate_select(id) {
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    var animate=$('#'+id).val();
    $('#Animate_txt').addClass('animated '+animate).on(animationEnd, function() {
         $('#Animate_txt').removeClass('animated '+animate);
         $('#re_animate').val(animate);
    });
 }

  function animate_btn() {
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    var animate=$('#re_animate').val();
    $('#Animate_txt').addClass('animated '+animate).on(animationEnd, function() {
         $('#Animate_txt').removeClass('animated '+animate);
    });
 }
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
