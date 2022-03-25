<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }

  #Animate_txt0, #Animate_txt1, #Animate_txt2, #Animate_txt3{ display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 

      $del_row=pdo_select("SELECT aPic FROM col_word WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['fun_id']]);
      $aPic=explode(',', $del_row['aPic']);

      //-- 清空該值 --
      $aPic[array_search($_POST['aPic'], $aPic)]='';

      $aPic=implode(',', $aPic);
      
      $param=['aPic'=>$aPic];
      $where=['Tb_index'=>$_POST['fun_id']];
      pdo_update('col_word', $param, $where);

      unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['aPic']);
      
       exit();
    }


  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='col'.date('YmdHis').rand(0,99);

    $pic_array=array();
    //-------------- 圖檔新增 ------------------
          for ($i=0; $i <count($_FILES['aPic']['name']) ; $i++) { 

             if (!empty($_FILES['aPic']['name'][$i])){
               $type=explode('/', $_FILES['aPic']['type'][$i]);
               $pic_array[$i]=$Tb_index.'_pic_'.$i.'.'.$type[1];
               more_fire_upload('aPic', $i, $Tb_index.'_pic_'.$i.'.'.$type[1], $_GET['Tb_index']);
             }
          }
        

    //===================== 背景圖 ========================
    //   if (!empty($_FILES['back_img']['name'])){

    //     if (test_img($_FILES['back_img']['name'])) {
    //      $type=explode('/', $_FILES['back_img']['type']);
    //      $back_img=$Tb_index.'_back.'.$type[1];
    //      fire_upload('back_img', $back_img, $_GET['Tb_index']);
    //     }
    //     else{
    //      location_up('iframe_base.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$_GET['fun_id'].'&rel_id='.$_GET['rel_id'],'檔案錯誤!請上傳正確檔案');
    //       exit();
    //     }
    // }


    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'col_num'=>$_POST['col_num'],
       'Title'=>implode(',', $_POST['Title']),
       'Title2'=>implode(',', $_POST['Title2']),
       'aPic'=>implode(',', $pic_array),
       'animate_txt'=>implode(',', $_POST['animate_txt']),
       'animate_img'=>implode(',', $_POST['animate_img']),
       'col_txt'=>implode('|,|', $_POST['col_txt']),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('col_word', $param);
    location_up('iframe_col.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    
    //-------------- 圖檔修改 ------------------

    $img_all=pdo_select("SELECT aPic FROM col_word WHERE Tb_index=:Tb_index", ['Tb_index'=>$Tb_index]);
    $img_all=explode(',', $img_all['aPic']);
    
    for ($i=0; $i <count($img_all) ; $i++) { 
       if (!empty($_FILES['aPic']['name'][$i])) {
         
         unlink('../../../product_html/'.$_GET['Tb_index'].'/img/'.$img_all[$i]);

         $type=explode('/', $_FILES['aPic']['type'][$i]);
         $img_all[$i]=$Tb_index.'_pic_'.$i.date('His').'.'.$type[1];
      
         more_fire_upload('aPic', $i, $img_all[$i], $_GET['Tb_index']);
       }
    }

    //-------------- 圖檔修改-END ------------------


    $aPic=implode(',', $img_all);
    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;
    
    $param=[
       'col_num'=>$_POST['col_num'],
       'Title'=>implode(',', $_POST['Title']),
       'Title2'=>implode(',', $_POST['Title2']),
       'aPic'=>$aPic,
       'animate_txt'=>implode(',', $_POST['animate_txt']),
       'animate_img'=>implode(',', $_POST['animate_img']),
       'col_txt'=>implode('|,|', $_POST['col_txt']),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('col_word', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);
    location_up('iframe_col.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM col_word WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);

  $Title=explode(',', $row['Title']);
  $Title2=explode(',', $row['Title2']);
  $aPic=explode(',', $row['aPic']);
  $col_txt=explode('|,|', $row['col_txt']);
  $animate_txt=explode(',', $row['animate_txt']);
  $animate_img=explode(',', $row['animate_img']);

  for ($i=1; $i <=4 ; $i++) { 
    if ($i==$row['col_num']) {
      ${'col'.$i}='selected';
    }else{
      ${'col'.$i}='';
    }
  }

?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-多段圖文</h2>
      
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
            <label class="col-sm-1 control-label">分段數量</label>
            <div class="col-sm-3"> 
              <select id="col_num" name="col_num" class="form-control">

                <option <?php echo $col1;?> value="1">1</option>
                <option <?php echo $col2;?> value="2">2</option>
                <option <?php echo $col3;?> value="3">3</option>
                <option <?php echo $col4;?> value="4">4</option>
              </select>
            </div>
            
          </div>

          <div class="tabs-container">
              <ul class="nav nav-tabs">
                 <?php 
                  if(!empty($row['col_num'])){

                   for ($i=1; $i <=$row['col_num'] ; $i++) { 
                      if ($i==1) {
                        echo '<li class="active"><a data-toggle="tab" href="#tab-'.$i.'"> 第'.$i.'段</a></li>';
                      }else{
                        echo '<li class=""><a data-toggle="tab" href="#tab-'.$i.'"> 第'.$i.'段</a></li>';
                      }
                    }
                  }
                  else{
                    echo '<li class="active"><a data-toggle="tab" href="#tab-1"> 第1段</a></li>';
                  }
                 ?>
                  
                  
              </ul>

              <div class="tab-content">

              <?php
                for ($i=0; $i <4 ; $i++) { 
                  $active=$i==0 ? 'active':'';
              ?>

                 <!-- TAB -->
                 <!-- TAB -->
                 <!-- TAB -->

                 <div id="tab-<?php echo $i+1;?>" class="tab-pane <?php echo $active;?>">
                     <div class="panel-body">

                        <div class="form-group">
                           <label class="col-sm-2 control-label" >主標</label>
                           <div class="col-sm-10">
                             <input type="text" name="Title[]" class="form-control" value="<?php echo $Title[$i];?>">
                           </div>
                         </div>

                         <div class="form-group">
                           <label class="col-sm-2 control-label" >副標</label>
                           <div class="col-sm-10">
                             <input type="text" name="Title2[]" class="form-control" value="<?php echo $Title2[$i];?>">
                           </div>
                         </div>


                         <div class="form-group">
                           <label class="col-sm-2 control-label" >圖片上傳</label>
                           <div class="col-sm-10">
                             <input type="file" class="form-control" name="aPic[]" onchange="file_viewer_load_new(this,'#back_box<?php echo $i;?>')">
                           </div>
                         </div>

                         <div class="form-group">
                            <label class="col-md-2 control-label" ></label>
                            <div id="back_box<?php echo $i;?>" class="col-md-4">
                             
                           </div>
                         <?php if(!empty($aPic[$i])){
                            $aPic_url='../../../product_html/'.$_GET['Tb_index'].'/img/'.$aPic[$i];
                           ?>
                           <div  class="col-md-4">
                              <div id="img_div" >
                               <p>目前圖檔</p>
                              <button type="button" class="one_del_img"> X </button>
                               <img id="one_img" src="<?php echo $aPic_url;?>" alt="請上傳代表圖檔">
                               <input type="hidden" value="<?php echo $aPic[$i];?>">
                             </div>
                           </div>
                         <?php }?>   
                         </div>


                         <div class="form-group">
                           <label class="col-sm-2 control-label" for="col_txt">內容</label>
                           <div class="col-sm-10">
                             <textarea id="ckeditor<?php echo $i;?>" name="col_txt[]" class="form-control"><?php echo $col_txt[$i];?></textarea>
                           </div>
                         </div>


                      
                         <div class="form-group">
                           <label class="col-sm-2 control-label" for="">圖文動畫效果</label>
                           <div class="col-sm-3">
                             文字特效:
                             <select id="animate_txt<?php echo $i;?>" name="animate_txt[]" onchange="animate_select('animate_txt<?php echo $i;?>', 'Animate_txt<?php echo $i;?>')">
                                                  <option value="">無特效</option>
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
                             圖片特效:
                             <select id="animate_img<?php echo $i;?>" name="animate_img[]" onchange="animate_select('animate_img<?php echo $i;?>', 'Animate_txt<?php echo $i;?>')">
                                                  <option value="">無特效</option>
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
                             <h1 id="Animate_txt<?php echo $i;?>" >Animate</h1>
                             <div><button type="button" id="re_animate_btn" class="btn btn-default" onclick="animate_btn('animate_img<?php echo $i;?>', 'Animate_txt<?php echo $i;?>')">重播一次</button></div>
                             <input type="hidden" id="re_animate">
                           </div>
                         </div>


                     </div>
                 </div>

              <?php
                }
              ?>
                       
                <div class="form-group">
                  <label class="col-sm-2 control-label" >是否上線</label>
                  <div class="col-sm-10">
                    <input style="width: 20px; height: 20px;"  name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
                  </div>
                </div>



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

    CKEDITOR.replace('ckeditor0',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php', height:300});
   // CKEDITOR.replace('ckeditor1',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php', height:300}); footer已有
    CKEDITOR.replace('ckeditor2',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php', height:300});
    CKEDITOR.replace('ckeditor3',{filebrowserUploadUrl:'../../js/plugins/ckeditor/php/upload.php',filebrowserImageUploadUrl : '../../js/plugins/ckeditor/php/upload_img.php', height:300});

    //----------- 文字動畫判斷 -------------
    <?php 
    for ($i=0; $i < count($animate_txt) ; $i++) { 
      if(!empty($animate_txt[$i])){
    ?>
      $('#animate_txt<?php echo $i;?>').css('display', 'block');
      $('#animate_txt<?php echo $i;?> [value="<?php echo $animate_txt[$i];?>"]').prop('selected', true);
    <?php
         }
      }
    ?>


    //----------- 圖片動畫判斷 -------------
    <?php 
    for ($i=0; $i < count($animate_img) ; $i++) { 
      if(!empty($row['animate_img'])){
    ?>
      $('#animate_img<?php echo $i;?>').css('display', 'block');
      $('#animate_img<?php echo $i;?> [value="<?php echo $animate_img[$i]?>"]').prop('selected', true);
    <?php
        }
      }
    ?>


      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });


    //------------------------------ 刪圖檔 ---------------------------------
    $(".one_del_img").click(function(event) { 
      if (confirm('是否要刪除檔案?')) {
       var data={
                      case_id:'<?php echo $_GET['Tb_index'];?>',
                      fun_id: '<?php echo $_GET['fun_id'];?>',
                       aPic: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_col.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });

      //------------------------------ 刪圖檔 ---------------------------------
    // $(".one_del_file").click(function(event) { 
    //   if (confirm('是否要刪除檔案?')) {
    //    var data={
    //                   case_id:'<?php //echo $_GET['Tb_index'];?>',
    //                   fun_id: '<?php //echo $_GET['fun_id'];?>',
    //                    base_img: $(this).next().next().val(),
    //                         type: 'delete'
    //             };  
    //            ajax_in('iframe_base.php', data, '成功刪除', 'no');
    //            $(this).parent().html('');
    //   }
    // });



    //---------------------------- 更改段數 --------------------------------------
     $('#col_num').change(function(event) {
        $('.nav-tabs').html('');
        for (var i = 1; i <= parseInt($(this).val()) ; i++) {

          if (i=='1') {
            $('.nav-tabs').append('<li class="active"><a data-toggle="tab" href="#tab-'+i+'"> 第'+i+'段</a></li>');
          }else{
            $('.nav-tabs').append('<li class=""><a data-toggle="tab" href="#tab-'+i+'"> 第'+i+'段</a></li>');
          }
          
        }
        $('#tab-1').addClass('active');
        $('#tab-2').removeClass('active');
        $('#tab-3').removeClass('active');
        $('#tab-4').removeClass('active');
     });


    //------------------------------ 啟用動態文字或圖片 ----------------------------
    $('#txt_fadein').change(function(event) {
       if($(this).prop('checked')==true){
          $('#animate_txt').css('display', 'block');
       }else{
          $('#animate_txt').css('display', 'none');
       }
    });

    $('#img_fadein').change(function(event) {
       if($(this).prop('checked')==true){
          $('#animate_img').css('display', 'block');
       }else{
          $('#animate_img').css('display', 'none');
       }
    });
	});

   //-------------------------------- 動畫展示 --------------------------------
 function animate_select(select_id, Animate_id) {
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    var animate=$('#'+select_id).val();
    $('#'+Animate_id).addClass('animated '+animate).on(animationEnd, function() {
         $('#'+Animate_id).removeClass('animated '+animate);
         $('#re_animate').val(animate);
    });
 }

  function animate_btn(select_id, Animate_id) {
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    var animate=$('#re_animate').val();
    $('#'+Animate_id).addClass('animated '+animate).on(animationEnd, function() {
         $('#'+Animate_id).removeClass('animated '+animate);
    });
 }
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
