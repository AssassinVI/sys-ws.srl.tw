<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.fb_fans{ display: none; }
	#ph_tool_type_exp img{ width: 100%; height: 750px; }
    
    .other_ad{display: none; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
 
if ($_POST) {

	//-- 新增 --
	if(empty($_POST['history_id'])){
			//-- 歷史紀錄 --
			$new_pdo->hs_tb_name='case_history';
			$new_pdo->hs_h_location='歷史版本';
			$new_pdo->hs_h_action_type='insert';
			$new_pdo->hs_h_title='建立歷史版本-'.$_POST['aTitle'];
			//-- 舊資料 --
			$new_pdo->old_data();

		$his=$new_pdo->select("SELECT COUNT(*)+1 as total FROM case_history WHERE case_id=:case_id", ['case_id'=>$_POST['Tb_index']], 'one');

		$param=[
			'Tb_index'=>'his'.date('YmdHis').rand(10,99),
			'case_id'=>$_POST['Tb_index'],
			'case_ver'=>$his['total'],
			'remark'=>$_POST['remark'],
			'StartDate'=>date('Y-m-d H:i:s'),
		];
		$new_pdo->insert('case_history', $param);


		//-- 新增歷史紀錄 --
		$new_pdo->hs_new_param=$param;
		$new_pdo->add_history();

		location_up('history.php?MT_id='.$_POST['mt_id'].'&Tb_index='.$_POST['Tb_index'],'成功新增');
	}
	//-- 修改 --
	else{

		//-- 歷史紀錄 --
		$new_pdo->hs_tb_name='case_history';
		$new_pdo->hs_old_id=$_POST['history_id'];
		$new_pdo->hs_h_location='歷史版本';
		$new_pdo->hs_h_action_type='update';
		$new_pdo->hs_h_title='修改歷史版本-'.$_POST['aTitle'];
		  //-- 舊資料 --
		$new_pdo->old_data();


		$param=[
			'remark'=>$_POST['remark'],
		];
		$new_pdo->update('case_history', $param, ['Tb_index'=>$_POST['history_id']]);


		//-- 新增歷史紀錄 --
		$new_pdo->add_history();
		
		location_up('history.php?MT_id='.$_POST['mt_id'].'&Tb_index='.$_POST['Tb_index'],'成功更新');

	}
  
}
if ($_GET) {

	$case=$new_pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], 'one');
	$case_history=$new_pdo->select("SELECT * FROM case_history WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['history_id']], 'one');
 	
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header><?php echo $case['aTitle'];?>歷史紀錄編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="" method="POST" enctype='multipart/form-data' class="form-horizontal">
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="remark">備註</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="remark" name="remark" value="<?php echo $case_history['remark'];?>">
							</div>
						</div>
						


						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
						<input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
						<input type="hidden" id="history_id" name="history_id" value="<?php echo $_GET['history_id'];?>">
						<input type="hidden" id="aTitle" name="aTitle" value="<?php echo $case['aTitle'];?>">
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
						  <button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">儲存</button>
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

			 let err_txt='';
			 err_txt+=check_input('#remark', '備註');

			 if(err_txt!=''){
				alert('請輸入：\n'+err_txt);
			 }
			 else{
				$('#put_form').submit();
			 }

          });

   

      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

