<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
  #video_div{ display: none; }
  #video_div2{ display: block; }

  video{ width: 150px !important; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    
        //----------------------- 影片刪除 -------------------------------
        $param=['video_file'=>''];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('youtube_tb', $param, $where);
            unlink('../../../product_html/'.$_POST['case_id'].'/video/'.$_POST['video_file']);
      
      // exit();
    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='yu'.date('YmdHis').rand(0,99);
   
    //-------------- 影片新增 ------------------
    if (!empty($_FILES['video_file']['name'])){

        if (test_file($_FILES['video_file']['name'])) {
         $type=explode('.', $_FILES['video_file']['name']);
         $video_file=$Tb_index.'.'.$type[1];
         video_upload('video_file', $video_file, $_GET['Tb_index']);
        }
        else{
          location_up('iframe_you.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$_GET['fun_id'].'&rel_id='.$_GET['rel_id'],'檔案錯誤!請上傳MP4檔');
          exit();
        }
      }
      else{
        $video_file='';
      }

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0 : 1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);

    $you_adds=empty($_POST['you_adds'])? '':$_POST['you_adds'];
    $autoPlay=empty($_POST['autoPlay'])? 0:1;
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'aTitle'=>$_POST['aTitle'],
       'video_type'=>$_POST['video_type'],
       'you_adds'=>$you_adds,
       'video_file'=>$video_file,
       'autoPlay'=>$autoPlay,
       'StartDate'=>date('Y-m-d H:i:s'),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('youtube_tb', $param);
    location_up('iframe_you.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    
    //-------------- 影片修改 ------------------

     if (!empty($_FILES['video_file']['name'])) {

        if (test_file($_FILES['video_file']['name'])){

         $type=explode('/', $_FILES['video_file']['type']);
         $video_file=$Tb_index.date('His').'.'.$type[1];
         video_upload('video_file', $video_file, $_GET['Tb_index']);

        $video_file_param=['video_file'=>$video_file];
        $video_file_where=['Tb_index'=>$Tb_index];
        pdo_update('youtube_tb', $video_file_param, $video_file_where);

        }
        else{

         location_up('iframe_you.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index,'圖檔錯誤!請上傳圖片檔');
         exit();
        }
      }

    //-------------- 影片修改-END ------------------

     $autoPlay=empty($_POST['autoPlay'])? 0:1;
     $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;

      $param=[
       'aTitle'=>$_POST['aTitle'],
       'you_adds'=>$_POST['you_adds'],
       'autoPlay'=>$autoPlay,
       'video_type'=>$_POST['video_type'],
       'OnLineOrNot'=>$OnLineOrNot,
      
    ];
    pdo_update('youtube_tb', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);
    location_up('iframe_you.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM youtube_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);

  $video_type1=empty($row['video_type']) || $row['video_type']=='0' ? 'checked' : '';
  $video_type2=$row['video_type']=='1' ? 'checked' : '';
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-YouTube影片</h2>
      
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
              <label class="col-sm-2 control-label" for="aTitle">主標</label>
              <div class="col-sm-10">
                <textarea id="aTitle" class="form-control" name="aTitle"><?php echo $row['aTitle'];?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="video_type">影片類型</label>
              <div class="col-sm-10">
                <input type="radio" id="video_type1" name="video_type" <?php echo $video_type1;?> value="0"> <label for="video_type1">YouTube影片</label>｜
                <input type="radio" id="video_type2" name="video_type" <?php echo $video_type2;?> value="1"> <label for="video_type2">上傳影片</label>｜
              </div>
            </div>

            <div id="you_div" class="form-group">
              <label class="col-sm-2 control-label" for="you_adds">YouTube網址</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="you_adds" name="you_adds" value="<?php echo $row['you_adds'];?>">
              </div>
            </div>

            <div id="video_div" class="form-group">
              <label class="col-sm-2 control-label" for="video_file">上傳影片</label>
              <div class="col-sm-6">
                <input type="file" class="form-control" accept=".mp4" id="video_file" name="video_file" onchange="video_load(this,'#img_box')">
              </div>
              <div class="col-sm-4">
               <input type="checkbox" id="autoPlay" name="autoPlay" value="1" <?php echo $check=!empty($row['autoPlay']) || $row['autoPlay']==1 ? 'checked' : ''; ?> > <label for="autoPlay">自動撥放</label>
              </div> 
            </div>

            <div id="video_div2" class="form-group">
               <label class="col-sm-2 control-label" ></label>
               <div id="img_box" class="col-sm-10">
                
              </div>

             <?php if(!empty($row['video_file'])){?>
              <div  class="col-sm-4">
                 <div id="img_div" >
                  <p>目前影片</p>
                 <button type="button" id="one_del_file"> X </button>
                  <video src="../../../product_html/<?php echo $_GET['Tb_index'];?>/video/<?php echo $row['video_file'];?>" controls>無影片或不支援</video>
                  <input type="hidden" value="<?php echo $row['video_file'];?>">
                </div>
              </div>
            <?php }?> 

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
        

      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });

      //------------------------------ 刪圖檔 ---------------------------------
          $("#one_del_file").click(function(event) { 
      if (confirm('是否要刪除影片?')) {
       var data={
                case_id:'<?php echo $_GET['Tb_index'];?>',
                fun_id: '<?php echo $_GET['fun_id'];?>',
            video_file: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_you.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });

    //------------------------------ 影片類型 ---------------------------------
   if ($('[name="video_type"]:checked').val()=='0') {
     $('#you_div').css('display', 'block');
     $('#video_div').css('display', 'none');
     $('#video_div2').css('display', 'none');
   }
   else{
     $('#you_div').css('display', 'none');
     $('#video_div').css('display', 'block');
     $('#video_div2').css('display', 'block');
   }
  

    $('[name="video_type"]').change(function(event) {

       if ($(this).val()=='0') {
         $('#you_div').css('display', 'block');
         $('#video_div').css('display', 'none');
         $('#video_div2').css('display', 'none');
       }
       else{
         $('#you_div').css('display', 'none');
         $('#video_div').css('display', 'block');
         $('#video_div2').css('display', 'block');
       }
    });

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
