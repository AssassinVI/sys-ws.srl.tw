<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	#sel_com{ padding: 5px; margin-right: 5px; }
	.data_m{margin:2px 0; display:inline-block;}
	.table-responsive{overflow:auto; }
	#table_id_example_wrapper{min-width: 1100px;}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
$pdo=pdo_conn();//資料庫初始化

if ($_GET) {

$case_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);
   
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $case_name['aTitle'].' - '.$page_name['MT_Name']?> 列表</h2>
	   <div class="new_div">

       
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	<h5><?php echo $page_name['MT_Name']?>列表 </h5>
			 	<div class="ibox-tools">
			 		        
			 		       
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="table_id_example" class="table no-margin responsive">
						<thead>
							<tr>
								<th width="80">日期</th>
								<th width="80">每日人數</th>
								<th width="80">性別</th>
								<th width="160">年齡</th>
								<th>地區</th>
								<th width="60">媒體</th>
								<th width="200">使用的功能</th>
								<th>流量來源</th>
								<th>管理</th>
								<!-- <th class="none_420">版本</th>
								<th class="text-right">管理</th> -->
							</tr>
						</thead>
						<tbody>

						
						</tbody>
					</table>
					<input type="hidden" id="case_id" value="<?php echo $_GET['Tb_index'];?>">
				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {

       var ajax_data = {
          'case_id': $('#case_id').val()
       };

            var table = $('#table_id_example').DataTable({
                "order":[[0, 'desc']],
                "searching": false,
                "lengthMenu": [ 20, 50, 100 ],
                "language":tb_language,
                "ajax": {
                "url": "edit_list_ajax.php",
                "type": "POST",
                "data": function ( d ) {
                            return  $.extend(d, ajax_data);
                        }
                },
                "columnDefs": [
                  {"targets": [ 2,3,4,5,6,7,8 ], "orderable": false }
                ],
            
                "processing": true,
                "serverSide": true,
            });

       $(".iframe_box").fancybox({
			'padding'               :'0',
			'type'                  : 'iframe',
			beforeClose: function () {
				table.draw();
			}
		});

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
