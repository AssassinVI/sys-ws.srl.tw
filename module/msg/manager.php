<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	#UserMsg, #backMsg{ height: 200px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if ($_GET) {

     $case_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['case_id']]);

 	$where=array('Tb_index'=>$_GET['Tb_index']);
 	$row=pdo_select('SELECT * FROM call_record_tb WHERE Tb_index=:Tb_index', $where);
 	$backMsg_readyonly=$row['is_process']=='0' ? '' : 'readonly';
}

if ($_POST) {
    $case_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['case_id']]);

	$is_process=empty($_POST['is_process']) ? '0' : '1';
	//$param=array('is_process'=>$is_process, 'reply'=>$_POST['reply']);
	$param=array('is_process'=>$is_process);
    $where=array('Tb_index'=>$_POST['Tb_index']);
    pdo_update('call_record_tb', $param, $where);
    
    if ($is_process=='1') {
    	    $name_data=array($_POST['use_name']);
            $adds_data=array($_POST['use_mail']);
            //send_Mail($case_name['aTitle'].'系統', 'server@srl.tw', $case_name['aTitle'].'-客服回信', $_POST['reply'], $name_data, $adds_data);
    }


    location_up('mail_admin.php?case_id='.$_POST['case_id'],'成功更新');
}


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header><?php echo $case_name['aTitle'];?>回信表單
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="Tb_index">單號:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="Tb_index" name="Tb_index" value="<?php echo $row['Tb_index'];?>">
							</div>

							<label class="col-md-2 control-label" for="use_name">姓名:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="use_name" name="use_name" value="<?php echo $row['use_name'];?>">
							</div>
						</div>
						<div class="form-group">
						
							<label class="col-md-2 control-label" for="use_mail">E-mail:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="use_mail" name="use_mail" value="<?php echo $row['use_mail'];?>">
							</div>

							<label class="col-md-2 control-label" for="phone">聯絡電話:</label>
							<div class="col-md-4">
								<input type="text" disabled class="form-control" id="phone" name="phone" value="<?php echo $row['phone'];?>">
							</div>
						</div>

						<div class="form-group">
						
							<label class="col-md-2 control-label" for="call_title">主旨:</label>
							<div class="col-md-10">
								<input type="text" disabled class="form-control" id="call_title" name="call_title" value="<?php echo $row['call_title'];?>">
							</div>
						</div>


					
						<div class="form-group">
							<label class="col-md-2 control-label" for="call_content">訊息:</label>
							<div class="col-md-10">
								<textarea class="form-control" disabled id="call_content" name="call_content" style="height: 150px;"><?php echo $row['call_content'];?></textarea>
							</div>
						</div>


						<!-- <div class="form-group">
							<label class="col-md-2 control-label" for="ckeditor">回信:</label>
							<div class="col-md-10">
								<textarea class="form-control"  <?php echo $backMsg_readyonly;?> name="reply"><?php echo $row['reply']?></textarea>
								<span>如需重新回信，請更新處理狀態為"未處理"</span>
							</div>
						</div> -->

						<div class="form-group">
							<label class="col-md-2 control-label" for="is_process">是否處理</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="is_process" name="is_process" type="checkbox" value="1" <?php echo $check=!isset($row['is_process']) || $row['is_process']==1 ? 'checked' : ''; ?>  />
								<!-- <span class="text-danger">留空將不會回信</span>
								<span>(打勾後更新，會隨即發送回信)</span> -->
							</div>
						</div>
                        
						<input type="hidden" id="case_id" name="case_id" value="<?php echo $_GET['case_id'];?>">
						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
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
						<button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">更新</button>
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
          $("#submit_btn").click(function(event) {
          	 $('#put_form').submit();
          });


      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

