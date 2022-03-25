<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
  // ======================== 刪除 ===========================
  	//----------------------- 代表圖刪除 -------------------------------
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    	if (!empty($_POST['aPic'])) {
    		$param=['aPic'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('case_news', $param, $where);
            unlink('../../img/'.$_POST['aPic']);
    	}else{
        //----------------------- 多檔刪除 -------------------------------
    		$sel_where=['Tb_index'=>$_POST['Tb_index']];
    		$otr_file=pdo_select('SELECT OtherFile FROM case_news WHERE Tb_index=:Tb_index', $sel_where);
    		$otr_file=explode(',', $otr_file['OtherFile']);
    		for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
    			 if ($otr_file[$i]!=$_POST['OtherFile']) {
    			 	$new_file.=$otr_file[$i].',';
    			 }else{
    			 	 unlink('../../other_file/'.$_POST['OtherFile']);
    			 }
    		}
    		$param=['OtherFile'=>$new_file];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('case_news', $param, $where);
    	}
       exit();
  	}
	if (empty($_POST['Tb_index'])) {//新增
		$Tb_index='news'.date('YmdHis').rand(0,99);
     
     //===================== 代表圖 ========================
      if (!empty($_FILES['aPic']['name'])){

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.'.'.$type[count($type)-1];
         fire_upload('aPic', $aPic);
      }
      else{
      	 $aPic='';
      }
     //===================== 多圖檔 ========================
    //   if (!empty($_FILES['OtherFile']['name'][0])){

      	
    //     for ($i=0; $i <count($_FILES['OtherFile']['name']) ; $i++) { 

    //        if (test_file($_FILES['OtherFile']['name'][$i])){

    //          $type=explode('/', $_FILES['OtherFile']['type'][$i]);
    //   	     $OtherFile.=$Tb_index.'_other_'.$i.'.'.$type[1].',';
    //          more_other_upload('OtherFile', $i, $Tb_index.'_other_'.$i.'.'.$type[1]);
    //   	   }
    //   	   else{
    //   	   	 location_up('admin.php?MT_id='.$_POST['mt_id'],'檔案錯誤!請上傳正確檔案');
    //          exit();
    //   	   }
    //     }
    //   }

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='case_news';
	$new_pdo->hs_h_location='建案新聞';
	$new_pdo->hs_h_action_type='insert';
	$new_pdo->hs_h_title='建立建案新聞-'.$_POST['aTitle'];
	//-- 舊資料 --
	$new_pdo->old_data();

     $OnLineOrNot=empty($_POST['OnLineOrNot']) ? 0:1;

	$param=  ['Tb_index'=>$Tb_index,
			            'case_id'=>$_POST['case_id'],
			             'aTitle'=>$_POST['aTitle'],
			          'aAbstract'=>$_POST['aAbstract'],
			               'aPic'=>$aPic,
			             'source'=>$_POST['source'],
			               'aUrl'=>$_POST['aUrl'],
						'aTarget'=>$_POST['aTarget'],
			         'youtubeUrl'=>$_POST['youtubeUrl'],
			          'StartDate'=>$_POST['StartDate'],
			        'OnLineOrNot'=>$OnLineOrNot
			         ];
	pdo_insert('case_news', $param);

	//-- 新增歷史紀錄 --
	$new_pdo->hs_new_param=$param;
	$new_pdo->add_history();

	location_up('news_admin.php?case_id='.$_POST['case_id'],'成功新增');
   }
   else{  //修改
   	$Tb_index =$_POST['Tb_index'];

   	 //===================== 代表圖 ========================
      if (!empty($_FILES['aPic']['name'])) {

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.'.'.$type[count($type)-1];
         fire_upload('aPic', $aPic);
        $aPic_param=['aPic'=>$aPic];
        $aPic_where=['Tb_index'=>$Tb_index];
        pdo_update('case_news', $aPic_param, $aPic_where);

        }
      //-------------------- 多檔上傳 ------------------------------
    //   if (!empty($_FILES['OtherFile']['name'][0])) {
    //   	$sel_where=['Tb_index'=>$Tb_index];
    //   	$now_file =pdo_select("SELECT OtherFile FROM case_news WHERE Tb_index=:Tb_index", $sel_where);
    //   	if (!empty($now_file['OtherFile'])) {
    //   	   $sel_file=explode(',', $now_file['OtherFile']);
    //        $file_num=explode('_', $sel_file[count($sel_file)-2]);
    //        $file_num=explode('.', $file_num[2]);
    //        $file_num=(int)$file_num[0]+1;
    //   	}else{
    //   	   $file_num=0;
    //   	}
    //   	for ($i=0; $i <count($_FILES['OtherFile']['name']) ; $i++) { 

    //   		 if (test_file($_FILES['OtherFile']['name'][$i])){

    //   		 	   $type=explode('/', $_FILES['OtherFile']['type'][$i]);
    //   		 	   $OtherFile.=$Tb_index.'_other_'.($file_num+$i).'.'.$type[1].',';
    //   		 	   more_other_upload('OtherFile', $i, $Tb_index.'_other_'.($file_num+$i).'.'.$type[1]);
    //   		 }
    //   		 else{

    //   		 	location_up('admin.php?MT_id='.$_POST['mt_id'],'檔案錯誤!請上傳正確檔案');
    //   		 	exit();
    //   		 }
    //   	}

    //   	$OtherFile=$now_file['OtherFile'].$OtherFile;
      	 
    //     $OtherFile_param=['OtherFile'=>$OtherFile];
    //     $OtherFile_where=['Tb_index'=>$Tb_index];
    //     pdo_update('case_news', $OtherFile_param, $OtherFile_where);
    //   }
      	//--------------------------- END -----------------------------------

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='case_news';
	$new_pdo->hs_old_id=$Tb_index;
	$new_pdo->hs_h_location='建案新聞';
	$new_pdo->hs_h_action_type='update';
	$new_pdo->hs_h_title='修改建案新聞-'.$_POST['aTitle'];
	  //-- 舊資料 --
	$new_pdo->old_data();
    
    $OnLineOrNot=empty($_POST['OnLineOrNot']) ? 0:1;
    
    $param=  [
			             'aTitle'=>$_POST['aTitle'],
			          'aAbstract'=>$_POST['aAbstract'],
			             'source'=>$_POST['source'],
			               'aUrl'=>$_POST['aUrl'],
					    'aTarget'=>$_POST['aTarget'],
			         'youtubeUrl'=>$_POST['youtubeUrl'],
			          'StartDate'=>$_POST['StartDate'],
			        'OnLineOrNot'=>$OnLineOrNot
			         ];
    $where= ['Tb_index'=>$Tb_index] ;
	pdo_update('case_news', $param, $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
	
	location_up('news_admin.php?case_id='.$_POST['case_id'],'成功更新');
   }
}
if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM case_news WHERE Tb_index=:Tb_index', $where);

 	$StartDate= empty($row['StartDate']) ? date('Y-m-d'):$row['StartDate'];
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>網頁資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST"  class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="aTitle">新聞標題</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aTitle" name="aTitle" value="<?php echo $row['aTitle'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="source">來源</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="source" name="source" value="<?php echo $row['source'];?>">
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-md-2 control-label" for="aPic">圖檔</label>
							<div class="col-md-10">
								<input type="file" name="aPic" class="form-control" accept="image/*" id="aPic" onchange="file_viewer_load_new(this, '#img_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="img_box" class="col-md-4">
								
							</div>
						<?php /*if(!empty($row['aPic'])){?>
							<div  class="col-md-4">
							   <div id="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" id="one_del_img"> X </button>
								  <span class="img_check"><i class="fa fa-check"></i></span>
								  <img id="one_img" src="../../img/<?php echo $row['aPic'];?>" alt="請上傳代表圖檔">
								</div>
							</div>
						<?php }*/?>		
						</div> -->

						<div class="form-group">
							<label class="col-md-2 control-label" for="aAbstract">摘要內容</label>
							<div class="col-md-10">
								<textarea style="height: 150px;" class="form-control" id="aAbstract" name="aAbstract" placeholder="摘要內容"><?php echo $row['aAbstract'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="aTarget">連結方式</label>
							<div class="col-md-10">
								<select name="aTarget" id="" class="form-control">
								  <option value="iframe">iframe</option>
								  <option value="blank">開新視窗</option>
								</select>
								<input type="hidden" id="aTarget" value="<?php echo $row['aTarget'];?>">
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="aUrl">連結</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aUrl" name="aUrl" value="<?php echo $row['aUrl'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="youtubeUrl">Youtube連結</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="youtubeUrl" name="youtubeUrl" value="<?php echo $row['youtubeUrl'];?>">
							</div>
						</div>
                        
                        <div class="form-group">
							<label class="col-md-2 control-label" for="StartDate">時間</label>
							<div class="col-md-10">
								<input type="date" class="form-control" id="StartDate" name="StartDate" value="<?php echo $StartDate;?>">
							</div>
						</div>
                        

						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
							</div>
						</div>

						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
						<input type="hidden" id="case_id" name="case_id" value="<?php echo $_GET['case_id'];?>">
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

		if($('#aTarget').val()!=''){
			$('[name="aTarget"]').val($('#aTarget').val());
		}

          $("#submit_btn").click(function(event) {
          	 $('#put_form').submit();
          });
    //------------------------------ 刪圖 ---------------------------------
          $("#one_del_img").click(function(event) { 
			if (confirm('是否要刪除圖檔?')) {
			 var data={
			 	        Tb_index: $("#Tb_index").val(),
                            aPic: '<?php echo $row["aPic"]?>',
                            type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $("#img_div").html('');
			}
		});
      //------------------------------ 刪檔 ---------------------------------
          $(".one_del_file").click(function(event) { 
			if (confirm('是否要刪除檔案?')) {
			 var data={
			 	        Tb_index: $("#Tb_index").val(),
                       OtherFile: $(this).next().next().val(),
                            type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $(this).parent().html('');
			}
		});
      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

