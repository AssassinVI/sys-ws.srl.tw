<?php  include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" href="/js/plugins/dataTables/Responsive-2.4.1/css/responsive.dataTables.min.css">
<style type="text/css">
	.is_deal{ color: green; }
	.no_deal{ color: red; }
	.new_div{
		display: flex;
		gap:3%;
		margin-bottom: 0.5%;
	}
</style>
<?php  include("../../core/page/header02.php");//載入頁面heaer02?>



<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

       <!-- <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>-->

	    <!--<a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>-->

		
		
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table id="table_id_example" class="table responsive">
						<thead>
							<tr>
								<th width="150">ID</th>
								<th width="150">時間</th>
								<th width="100">分類</th>
								<th class="none">log json</th>
								<!-- <th class="none">log json</th> -->
							</tr>
						</thead>
						<tbody>

						
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script src="/js/plugins/dataTables/Responsive-2.4.1/dataTables.responsive.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		let get_url=new URL(location.href);
		var ajax_data = {
             'MT_id': get_url.searchParams.get('MT_id'),
             'pageId': get_url.searchParams.get('pageId'),
           	 'OnLineOrNot':$('.OnLineOrNot').val(),
        };

		var table = $('#table_id_example').DataTable({
				responsive: true,
				"searching": false,
				"order":[[0, 'desc']],
				"lengthMenu": [ 20, 50, 100 ],
				language:language,
				"ajax": {
				"url": "formTable_log_ajax.php",
				"type": "POST",
				"data": function ( d ) {
							return  $.extend(d, ajax_data);
						}
				},
				"columnDefs": [
				//   {"targets": [ 0,4 ], "orderable": false },
				//   {"targets": [ 7 ], "visible": false }
				],
				"processing": true,
				"serverSide": true
		});

		//-- 撈表單 --
		$.ajax({
			type: "POST",
			url: "ajax.php",
			data: {
				type: 'get_form',
				pageId: get_url.searchParams.get('pageId')
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				
			}
		});


	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
