<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style>
	.panel-default>.panel-heading{display: flex; justify-content: space-between; align-items: center;}
	.panel-default.block_box{flex:1 1 100%;}
	.sort_block_div{display: flex; flex-wrap: wrap;}
	.sort_block_div .block_box{
		
		border:0;
	}
	.sort_block_div .block_box .panel-body{
		border: 1px solid #e9e9e9;
		border-top: 0;
	}
	.flex-box{
		display: flex;
		gap: 25px;
	}
	#twzipcode{
	  display: flex;
	}

	#twzipcode [data-role="county"],
	#twzipcode [data-role="district"]{
		flex: 1 1 50%
	}
	.one_del_img{
		display: none;
	}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>

<?php
 //-- 歷年業績不顯示 --
 if($_GET['MT_id']=='site2025021416212380'){
	$h_not_show='d-none';
	$h_show='';
 }
 else{
	$h_not_show='';
	$h_show='d-none';
 }

?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
		<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">

			

			<div class="panel panel-default">
				<div class="panel-heading">
					<header>粉絲專頁編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">

					<div class="form-group">
						<label class="col-md-2 control-label" for="pageId">粉絲專頁ID</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="pageId" name="pageId" value="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="pageName">粉絲專頁名稱</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="pageName" name="pageName" value="" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="groupId">連結LINE群組</label>
						<div class="col-md-4">
							<select class="form-control" name="groupId" id="groupId">
								<option value="">-- 請選擇 --</option>
							</select>
							<span class="text-danger">LINE群組選項須透過LINE功能的"<a target="_blank" href="https://help.line.me/line/smartphone/?contentId=20008159&utm_source=help&utm_medium=messaging&utm_campaign=contentId20000369_contentId20008159&utm_term=help&lang=zh-Hant">建立群組</a>"<br>將LINE官方帳號加入至群組中<br>再重新整理此頁面即可</span>
						</div>
					</div>

					<!-- <div class="form-group">
						<label class="col-md-2 control-label" for="ca_c_img"><span class="text-danger">*</span> 建案多圖</label>
						<div class="col-md-10">
							<input type="file" multiple name="ca_c_img" class="form-control" id="ca_c_img" onchange="file_viewer_load_new(this, '#banner_c_box')">
							<span class="text-danger">可批次上傳圖片，建議圖片尺寸：1500 x 1140</span>

							<div id="banner_c_box" >
							</div>

							<div data-img="ca_c_img"  class="sort_div">
							</div>
							<span class="sort_txt"></span>
						</div>
					</div> -->

					
					<!-- <div class="form-group">
						<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
						<div class="col-md-10">
							<input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" checked  />
						</div>
					</div> -->
				</div>
			</div>

				<input type="hidden" id="get_pageId" name="get_pageId" value="<?php echo $_GET['pageId'];?>">
				<input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
			</form>



		</div>

		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>儲存您的資料</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body text-center">
					    <?php if(empty($_GET['Tb_index'])){?>
							<button type="button" id="submit_btn" class="btn btn-success btn-raised">儲存資料</button>
						<?php }else{?>
						    <button type="button" id="submit_btn" class="btn btn-success btn-raised">更新資料</button>
						<?php }?>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">


	$(document).ready(function() {

		


		//-- 撈資料 --
		let pageId=$('#get_pageId').val();
		if(pageId!=''){
		  let ajax= select_one ('ajax.php', {
						type: 'select_one',
						pageId: pageId
					});

		  ajax.done((data)=>{
			
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					type: 'select_line_group',
					pageId: pageId
				},
				dataType: "json",
				success: function (line_data) {
					console.log(line_data);
					if(line_data.success){
						line_data.data.forEach(item => {
							$('#groupId').append(`<option value="${item.groupId}" ${item.groupId==data.data.line_bot_group ? 'selected':''}>${item.groupName}</option>`);
						});
					}
					else{
						alert(line_data.msg);
					}
				}
			});
		  });
		}



		
	//-- 儲存、更新 --
	$("#submit_btn").click(function(event) {
		let nowURL=new URL(window.location.href);
		let err_txt='';
			// err_txt+=check_input('#ca_year', '年份\n');
			
		if(err_txt!=''){
			alert('以下為必填項目：\n'+err_txt);
		}
		else{
			let ajax_type= $('[name="Tb_index"]').val()=='' ? 'insert':'update';

			sub_data({
				// img_arr: ['ca_t_img1', 'ca_banner'], 
				// imgs_arr: ['ca_c_img'],
				// ck_arr: ['aTXT'], 
				ajax_type: ajax_type,
			});
		}
	});

		//------------------------------ 刪圖 ---------------------------------
		$('#put_form').on('click', '.one_del_img', function () {
			if (confirm('是否要刪除圖檔?')) {
			del_img_ajax($(this), 'case', $('#Tb_index').val());
			}
		});



		//------------------------------ 刪多圖 ---------------------------------
		$('#put_form').on('click', '.one_del_m_img', function () {
			if (confirm('是否要刪除圖檔?')) {
				del_img_m_ajax ($(this), 'case', $('#Tb_index').val());
			}
		});

    });

</script>

<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

