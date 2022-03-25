<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

$test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    
        //----------------------- 電腦多檔刪除 -------------------------------
        if (!empty($_POST['show_img'])) {
          
           $sel_where=['Tb_index'=>$_POST['fun_id']];
           $otr_file=pdo_select('SELECT show_img FROM slideshow_tb WHERE Tb_index=:Tb_index', $sel_where, $test_case_db);
           $otr_file=explode(',', $otr_file['show_img']);
           for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
              if ($otr_file[$i]!=$_POST['show_img']) {
               $new_file.=$otr_file[$i].',';
              }else{
                unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['show_img']);
              }
           }
           $param=['show_img'=>$new_file];
               $where=['Tb_index'=>$_POST['fun_id']];
               pdo_update('slideshow_tb', $param, $where, $test_case_db);
          
          exit();
        }

        //----------------------- 手機多檔刪除 -------------------------------
        else{
          
           $sel_where=['Tb_index'=>$_POST['fun_id']];
           $otr_file=pdo_select('SELECT show_img_ph FROM slideshow_tb WHERE Tb_index=:Tb_index', $sel_where, $test_case_db);
           $otr_file=explode(',', $otr_file['show_img_ph']);
           for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
              if ($otr_file[$i]!=$_POST['show_img_ph']) {
               $new_file.=$otr_file[$i].',';
              }else{
                unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['show_img_ph']);
              }
           }
           $param=['show_img_ph'=>$new_file];
               $where=['Tb_index'=>$_POST['fun_id']];
               pdo_update('slideshow_tb', $param, $where, $test_case_db);
          
          exit();
        }
       
    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='ss'.date('YmdHis').rand(0,99);
   
    //-------------- 圖檔新增 ------------------
    if (!empty($_FILES['show_img'])){

          for ($i=0; $i <count($_FILES['show_img']['name']) ; $i++) { 

               $type=explode('.', $_FILES['show_img']['name'][$i]);
               $show_img.=$Tb_index.'_slider_'.$i.'.'.$type[count($type)-1].',';
               more_fire_upload('show_img', $i, $Tb_index.'_slider_'.$i.'.'.$type[count($type)-1], $_GET['Tb_index']);
             
          }
    }
    else{
      $show_img='';
    }

    //-------------- 圖檔新增 ------------------
    if (!empty($_FILES['show_img_ph'])){

          for ($i=0; $i <count($_FILES['show_img_ph']['name']) ; $i++) { 

               $type=explode('.', $_FILES['show_img_ph']['name'][$i]);
               $show_img_ph.=$Tb_index.'_slider-ph_'.$i.'.'.$type[count($type)-1].',';
               more_fire_upload('show_img_ph', $i, $Tb_index.'_slider-ph_'.$i.'.'.$type[count($type)-1], $_GET['Tb_index']);
             
          }
    }
    else{
      $show_img_ph='';
    }

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']], $test_case_db);
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'play_speed'=>$_POST['play_speed'],
       'effect'=>$_POST['effect'],
       'show_img'=>$show_img,
       'show_img_ph'=>$show_img_ph,
       'ImgWord_type'=>$_POST['ImgWord_type'],
       'ImgWord_ph_type'=>$_POST['ImgWord_ph_type'],
       'aTXT'=>$_POST['aTXT'],
       'img_txt'=>$_POST['img_txt'],
       'StartDate'=>date('Y-m-d H:i:s'),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('slideshow_tb', $param, $test_case_db);
    location_up('iframe_show.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    
    //-------------- 圖檔修改 ------------------

    if (!empty($_FILES['show_img'])) {
        $sel_where=['Tb_index'=>$Tb_index];
        $now_file =pdo_select("SELECT show_img FROM slideshow_tb WHERE Tb_index=:Tb_index", $sel_where, $test_case_db);
        if (!empty($now_file['show_img'])) {
           $sel_file=explode(',', $now_file['show_img']);
           $file_num=explode('_', $sel_file[count($sel_file)-2]);
           $file_num=explode('.', $file_num[2]);
           $file_num=(int)$file_num[0]+1;
        }else{
           $file_num=0;
        }
        for ($i=0; $i <count($_FILES['show_img']['name']) ; $i++) { 

            if(!empty($_FILES['show_img']['name'][$i])){
               $type=explode('.', $_FILES['show_img']['name'][$i]);
               $show_img.=$Tb_index.'_slider_'.($file_num+$i).'.'.$type[count($type)-1].',';
               more_fire_upload('show_img', $i, $Tb_index.'_slider_'.($file_num+$i).'.'.$type[count($type)-1], $_GET['Tb_index']);

                unlink('../../../product_html/'.$_GET['Tb_index'].'/img/'.$_POST['old_file'][$i]);
            }
            else{
              $show_img.=$_POST['old_file'][$i].',';
            }
        }
         
        $show_img_param=['show_img'=>$show_img];
        $show_img_where=['Tb_index'=>$Tb_index];
        pdo_update('slideshow_tb', $show_img_param, $show_img_where, $test_case_db);
      }
    //-------------- 圖檔修改-END ------------------


    //-------------- 手機圖檔修改 ------------------
    if (!empty($_FILES['show_img_ph'])) {
        $sel_where=['Tb_index'=>$Tb_index];
        $now_file =pdo_select("SELECT show_img_ph FROM slideshow_tb WHERE Tb_index=:Tb_index", $sel_where, $test_case_db);
        if (!empty($now_file['show_img_ph'])) {
           $sel_file=explode(',', $now_file['show_img_ph']);
           $file_num=explode('_', $sel_file[count($sel_file)-2]);
           $file_num=explode('.', $file_num[2]);
           $file_num=(int)$file_num[0]+1;
        }else{
           $file_num=0;
        }
        for ($i=0; $i <count($_FILES['show_img_ph']['name']) ; $i++) { 

            if(!empty($_FILES['show_img_ph']['name'][$i])){
               $type=explode('.', $_FILES['show_img_ph']['name'][$i]);
               $show_img_ph.=$Tb_index.'_slider-ph_'.($file_num+$i).'.'.$type[count($type)-1].',';
               more_fire_upload('show_img_ph', $i, $Tb_index.'_slider-ph_'.($file_num+$i).'.'.$type[count($type)-1], $_GET['Tb_index']);

                unlink('../../../product_html/'.$_GET['Tb_index'].'/img/'.$_POST['old_file_ph'][$i]);
            }
            else{
              $show_img_ph.=$_POST['old_file_ph'][$i].',';
            }
        }
         
        $show_img_ph_param=['show_img_ph'=>$show_img_ph];
        $show_img_ph_where=['Tb_index'=>$Tb_index];
        pdo_update('slideshow_tb', $show_img_ph_param, $show_img_ph_where, $test_case_db);
      }
    //-------------- 手機圖檔修改-END ------------------


      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
      $param=[
       'play_speed'=>$_POST['play_speed'],
       'effect'=>$_POST['effect'],
       'ImgWord_type'=>$_POST['ImgWord_type'],
       'ImgWord_ph_type'=>$_POST['ImgWord_ph_type'],
       'aTXT'=>$_POST['aTXT'],
       'img_txt'=>$_POST['img_txt'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('slideshow_tb', $param, ['Tb_index'=>$Tb_index], $test_case_db);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index], $test_case_db);
    location_up('iframe_show.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], $test_case_db);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM slideshow_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']], $test_case_db);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-圖片輪播 <span class="text-danger">(此為測試修改區)</span></h2>
      
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
              <label class="col-sm-2 control-label" for="play_speed">延遲時間</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="play_speed" name="play_speed" value="<?php echo $row['play_speed'];?>">
                <span >單位：毫秒</span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="effect">輪播特效</label>
              <div class="col-sm-6">
                <select class="form-control" name="effect">
                  <option value="slide">預設(滑動)</option>
                  <option value="fade">淡入淡出</option>
                  <option value="coverflow">3D切換</option>
                </select>
              </div>
            </div>

             <div class="form-group">
              <label class="col-sm-1 control-label" >圖片</label>
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
                       if(!empty($row['show_img'])){
                                  $show_img=explode(',', $row['show_img']);
                                   for ($i=0; $i <count($show_img)-1 ; $i++) { 
                                     $img_txt='<li class="oneFile_div">
                                                   <span class="mark_num">'.($i+1).'</span>
                                                   <div class="">
                                                     <input type="file" name="show_img[]" class="form-control" id="show_img" onchange="file_viewer_load_new(this, \'#other_div'.$i.'\')">
                                                     <button type="button" class="btn btn-danger one_del_div">x</button>
                                                   </div>
                                                   <div id="other_div'.$i.'" class="other_div"></div>
                                                   <div class="old_file" style="background: url(../../../product_html/'.$_GET['Tb_index'].'/img/'.$show_img[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                                                   <input type="hidden" name="old_file[]" value="'.$show_img[$i].'">
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
               <button type="button" id="new_show_img_ph" class="btn btn-info"><i class="fa fa-plus"></i> 新增檔案</button><br>
                <span class="text-danger">順序由左至右，由上到下</span>
              </div>
            </div>

            <div class="form-group">
               <label class="col-md-2 control-label" ></label>

              <div class="col-md-10">
                <ul class="sortable-list connectList agile-list ui-sortable show_img_ph_div" >

                  <?php 
                       if(!empty($row['show_img_ph'])){
                                  $show_img_ph=explode(',', $row['show_img_ph']);
                                   for ($i=0; $i <count($show_img_ph)-1 ; $i++) { 
                                     $img_txt='<li class="oneFile_div">
                                                   <span class="mark_num">'.($i+1).'</span>
                                                   <div class="">
                                                     <input type="file" name="show_img_ph[]" class="form-control" id="show_img_ph" onchange="file_viewer_load_new(this, \'#one_img_ph'.$i.'\')">
                                                     <button type="button" class="btn btn-danger one_del_div">x</button>
                                                   </div>
                                                   <div id="one_img_ph'.$i.'" class="other_div"></div>
                                                   <div class="old_file" style="background: url(../../../product_html/'.$_GET['Tb_index'].'/img/'.$show_img_ph[$i].') center; background-size: cover;"><p>目前圖檔</p> </div>
                                                   <input type="hidden" name="old_file_ph[]" value="'.$show_img_ph[$i].'">
                                                 </li>';
                                                 echo $img_txt;
                                              }
                                           }
                  ?>

                </ul>
                </div>
            </div>


            <div class="form-group">
              <label class="col-sm-1 control-label" for="img_txt">圖說</label>
              <div class="col-sm-11">
                <input type="text" name="img_txt" class="form-control" value="<?php echo $row['img_txt'];?>">
                <span>使用","分隔字串</span>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-1 control-label" for="aTXT">內容</label>
              <div class="col-sm-11">
                <textarea id="ckeditor" name="aTXT" class="form-control"><?php echo $row['aTXT'];?></textarea>
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


    // 目前特效
    <?php if(!empty($row['effect'])){ ?>
     $('[value="<?php echo $row['effect'];?>"]').prop('selected', 'true');
    <?php }?>
    
        

      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });

      //------------------------------ 刪圖檔 ---------------------------------
          $(".one_del_file").click(function(event) { 
      if (confirm('是否要刪除檔案?')) {
       var data={
                      case_id:'<?php echo $_GET['Tb_index'];?>',
                      fun_id: '<?php echo $_GET['fun_id'];?>',
                       show_img: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_show.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });


    //=============================== 電腦多圖檔 ==================================
      //-- 多檔刪除 --
      $('.OtherFile_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();

        if (confirm('是否要刪除檔案?')){
        
          if ($(this).parent().next().next().next().length>0) {
             $.ajax({
             url: 'iframe_show.php',
             type: 'POST',
             data: {
               fun_id: '<?php echo $_GET['fun_id'];?>',
               case_id: '<?php echo $_GET['Tb_index'];?>',
               show_img: $(this).parent().next().next().next().val(),
               type: 'delete'
             }

            });
          }
        $(this).parent().parent().remove();
      }
      });


       // 新增多檔
       var otherfile_num=$('[name="show_img[]"]').length;
       $('#new_OtherFile').click(function(event) {

         var otherfile_txt='<li class="oneFile_div">'
                           +'<span class="mark_num">'+(otherfile_num+1)+'</span>'
                           +'<div class="">'
                             + '<input type="file"  name="show_img[]" class="form-control" id="OtherFile" onchange="file_viewer_load_new(this, \'#other_div'+otherfile_num+'\')">'
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


      //=============================== 手機多圖檔 ==================================
        //-- 多檔刪除 --
      $('.show_img_ph_div').on('click', '.one_del_div', function(event) {
        event.preventDefault();

        if (confirm('是否要刪除檔案?')){
        
          if ($(this).parent().next().next().next().length>0) {
             $.ajax({
             url: 'iframe_show.php',
             type: 'POST',
             data: {
               fun_id: '<?php echo $_GET['fun_id'];?>',
               case_id: '<?php echo $_GET['Tb_index'];?>',
               show_img_ph: $(this).parent().next().next().next().val(),
               type: 'delete'
             }

            });
          }
        $(this).parent().parent().remove();
      }
      });


       // 新增多檔
       var otherfile_ph_num=$('[name="show_img_ph[]"]').length;
       $('#new_show_img_ph').click(function(event) {

         var otherfile_txt='<li class="oneFile_div">'
                           +'<span class="mark_num">'+(otherfile_ph_num+1)+'</span>'
                           +'<div class="">'
                             + '<input type="file"  name="show_img_ph[]" class="form-control" id="OtherFile" onchange="file_viewer_load_new(this, \'#one_img_ph'+otherfile_ph_num+'\')">'
                              +'<button type="button"  class="btn btn-danger one_del_div">x</button>'
                          +'</div>'
                             +'<div id="one_img_ph'+otherfile_ph_num+'" class="other_div">'
                             +'</div>'
                             +'<input type="hidden" name="old_file[]" value="">'
                          +'</li>';

         $('.show_img_ph_div').append(otherfile_txt);
         otherfile_ph_num++;
       });

      
      // 拖曳多圖檔
       $(".show_img_ph_div").sortable({
         connectWith: ".show_img_ph_div",
         update: function( event, ui ) {

              var OtherFile_arr = $( ".show_img_ph_div" ).sortable( "toArray" );
         }
      }).disableSelection();


	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
