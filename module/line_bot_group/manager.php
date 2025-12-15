<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_GET) {
 	$where=['rowid'=>$_GET['rowid']];
 	$row=pdo_select('SELECT * FROM line_msg_bot_group WHERE rowid=:rowid', $where);
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>LINE bot 加入群組資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="" method="POST"  class="form-horizontal">
						<div class="form-group">
							<label class="col-md-1 control-label" for="groupId">群組ID</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="groupId" name="groupId" readonly value="<?php echo $row['groupId'];?>">
							</div>
							<label class="col-md-1 control-label" for="groupName">群組名稱</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="groupName" name="groupName" value="<?php echo $row['groupName'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-1 control-label" for="case_id">關聯建案</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="case_id" name="case_id" value="<?php echo $row['case_id'];?>">
							</div>
						</div>

						<input type="hidden" id="rowid" name="rowid" value="<?php echo $_GET['rowid'];?>">
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
          	 $.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					type: 'update',
					rowid: $("#rowid").val(),
					groupName: $("#groupName").val(),
					case_id: $("#case_id").val(),
				},
				dataType: "json",
				success: function (data) {
					if (data.success) {
						alert('儲存成功!');
						window.location.href = 'admin.php';
					} else {
						alert('儲存失敗!');
					}
				}
			 });
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

