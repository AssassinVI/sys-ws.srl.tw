<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="../../style/shop_all.min.css?1">
<style>
	.panel-default>.panel-heading{display: flex; justify-content: space-between; align-items: center; cursor: move;}
	.panel-default.block_box{flex:1 1 100%;}
	.sort_block_div{display: flex; flex-wrap: wrap;}
	.sort_block_div .block_box{
		
		border:0;
	}
	.sort_block_div .block_box .panel-body{
		border: 1px solid #e9e9e9;
		border-top: 0;
	}
	.one_del_img{
		display: none;
	}
	[data-img="aImg"] #one_img{
		background-color: #919191;
	}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>列表資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						

						<!-- <div class="form-group">
							<label class="col-md-2 control-label" for="aTitle_one"><span class="text-danger">*</span> 標題名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="aTitle_one" name="aTitle_one" value="">
								<span class="text-danger">建議字數：20字內</span>
							</div>
						</div> -->

						<!-- <div class="form-group">
							<label class="col-md-2 control-label" for="abstract"><span class="text-danger">*</span> 摘要</label>
							<div class="col-md-4">
								<textarea class="form-control" name="abstract" id="abstract"></textarea>
								<span class="text-danger">建議字數：2行，每行30字內</span>
							</div>
						</div> -->
						
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="aImg"><span class="text-danger">*</span> 建案LOGO</label>
							<div class="col-md-4">
								<input type="file" name="aImg" class="form-control" id="aImg" onchange="file_viewer_load_new(this, '#img_box')">
								<span class="text-danger">建議圖片尺寸：600 x 160</span>
								<div id="img_box" >
								</div>
								<div data-img="aImg" >
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="aTitle_one"><span class="text-danger">*</span> 建案小字</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="aTitle_one" name="aTitle_one" value="">
								<span class="text-danger">建議字數：20字內</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="index_aImg"><span class="text-danger">*</span> 新聞圖檔1</label>
							<div class="col-md-4">
								<input type="file" name="index_aImg" class="form-control" id="index_aImg" onchange="file_viewer_load_new(this, '#img_index_box')">
								<span class="text-danger">建議圖片尺寸：1400 x 1280</span>
								<div id="img_index_box" >
								</div>
								<div data-img="index_aImg" >
								</div>
							</div>
							<label class="col-md-2 control-label" for="index2_aImg"><span class="text-danger">*</span> 新聞圖檔2</label>
							<div class="col-md-4">
								<input type="file" name="index2_aImg" class="form-control" id="index2_aImg" onchange="file_viewer_load_new(this, '#img_index_box_ph')">
								<span class="text-danger">建議圖片尺寸：840 x 1280</span>
								<div id="img_index_box_ph" >
								</div>
								<div data-img="index2_aImg" >
								</div>
							</div>
						</div>


						<!-- <div class="form-group">
							<label class="col-md-2 control-label" for="StartDate">日期</label>
							<div class="col-md-4">
								<input type="text" class="form-control datepicker" id="StartDate" name="StartDate" value="<?php echo date('Y-m-d');?>">
							</div>
						</div> -->

						
						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" checked  />
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
						<div class="col-lg-12">
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

	<!-- 標籤選擇 -->
	<div id="inline_box" class="inline_div d-none sel_Label">
		<button class="inline_close_btn">Ｘ</button>
		<h2>標籤選擇</h2>
		<div class="form_box">
		<div >
			<div class="input-group">
				<input type="text" class="form-control search_label">
				<span class="input-group-btn">
					<button id="search_label_btn" type="button" class="btn btn-success"><i class="fa fa-search"></i> 查詢</button>
					<button id="add_label_btn" type="button" class="btn btn-default"><i class="fa fa-plus"></i> 新增</button>
				</span>
			</div>
		</div>
		<div class="label_box">
		</div>
		
		<div class="flex_box between tool_div" style="margin-top: 10px;">
			<div class="now_type">
			<p>目前已選擇的：<span></span></p>
			</div>
			<div class="tool_box">
				<div>
				<button type="button" class="btn btn-default close_type_btn">取消</button>
				<button type="button" class="btn btn-success submit_label_btn">確認</button>
				</div>
			</div>
		</div>
		</div>
	</div>
	<!-- 標籤選擇 END -->

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

		//-- 撈資料 --
		let Tb_index=$('#Tb_index').val();
		if(Tb_index!=''){
			let ajax= select_one ('ajax.php', {
						type: 'select_one',
						Tb_index: Tb_index
					  });
		}


		

      	  //-- 儲存、更新 --
          $("#submit_btn").click(function(event) {

			let err_txt='';
			    err_txt+=check_input('#aTitle_one', '標題名稱\n');
				err_txt+=$('[data-img="aImg"] [name="old_img"]').length==0 ? check_input('#aImg', '代表圖檔\n'):'';
				err_txt+=$('[data-img="index_aImg"] [name="old_img"]').length==0 ? check_input('#index_aImg', '新聞圖檔1\n'):'';
				err_txt+=$('[data-img="index2_aImg"] [name="old_img"]').length==0 ? check_input('#index2_aImg', '新聞圖檔2\n'):'';

			 if(err_txt!=''){
				alert('以下為必填項目：\n'+err_txt);
			 }
			 else{
					let ajax_type= $('[name="Tb_index"]').val()=='' ? 'insert':'update';
					sub_data({
						img_arr: ['aImg', 'index_aImg', 'index2_aImg'], 
						// ck_arr: ['aTXT'], 
						ajax_type: ajax_type
					});
			  }
          });


		//------------------------------ 刪圖 ---------------------------------
		$('#put_form').on('click', '.one_del_img', function () {
			if (confirm('是否要刪除圖檔?')) {
				let _this=$(this);
				let data_img=$(this).parents('[data-img]').attr('data-img');
				let old_img=$(this).parent().find('[name="old_img"]').val();
				$.ajax({
					type: "POST",
					url: "ajax.php",
					data: {
						type: 'delete_img',
						Tb_index: $('#Tb_index').val(),
						data_img: data_img,
						old_img: old_img
					},
					dataType: "json",
					success: function (data) {
						//console.log(data);
						if(data.success){
							_this.parent().remove();
						}
					}
				});
				}
		});

      });


</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

