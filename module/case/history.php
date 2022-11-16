<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	#sel_com{ padding: 5px; margin-right: 5px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

$case=$new_pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], 'one');

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $case['aTitle']?> 歷史紀錄</h2>
	   <div class="new_div">

       
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	<div class="ibox-tools">

			 			    <a href="manager_h.php?MT_id=<?php echo $_GET['MT_id'];?>&Tb_index=<?php echo $_GET['Tb_index'];?>">
			 		        <button type="button" class="btn btn-success">
			 		        <i class="fa fa-plus" aria-hidden="true"></i> 新增歷史紀錄</button>
			 		        </a>
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>版本</th>
								<th>備註</th>
								<th>狀態</th>
								<th>日期</th>
								<th>網址</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						
						<tbody id="case_tb">

							<?php
							  $history_case=$new_pdo->select("SELECT * FROM case_history WHERE case_id=:case_id ORDER BY StartDate DESC", ['case_id'=>$_GET['Tb_index']]);
							  foreach ($history_case as $his) {
								$case_type=$his['case_type']==0 ? '製作中...':'製作完成';
								$a_URL=$his['case_type']==0 ? '':'<a href="https://ws.srl.tw/history/'.$his['case_id'].'/'.$his['case_ver'].'/" target="_blank">https://ws.srl.tw/history/'.$his['case_id'].'/'.$his['case_ver'].'/</a>';
								echo '
								<tr>
									<td>'.$his['case_ver'].'</td>
									<td>'.$his['remark'].'</td>
									<td>'.$case_type.'</td>
									<td>'.$his['StartDate'].'</td>
									<td>'.$a_URL.'</td>
									<td class="text-right">
									  <a class=" btn btn-success btn-sm" href="manager_h.php?MT_id='.$_GET['MT_id'].'&Tb_index='.$_GET['Tb_index'].'&history_id='.$his['Tb_index'].'" ><i class="fa fa-pencil-square" aria-hidden="true"></i> 編輯</a>
									</td>
								</tr>
								 ';
							  }
							?>

							
					
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {

		 $(".iframe_box").fancybox({
		 	'padding'               :'0',
            'type'                  : 'iframe'
		 });

		 
     
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
