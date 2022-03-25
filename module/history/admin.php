<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">歷史紀錄</h2>
	   <div class="new_div">
	  </div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table id="table_id_example"  class=" table table-stripped" >
						<thead>
						<tr>
							<th>類型</th>
							<th>位置</th>
							<th>標題</th>
							<th>編輯者</th>
							<th>編輯時間</th>
							<th data-hide="phone" class="text-right">管理功能</th>
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
<script>
	$(document).ready(function () {

        var ajax_data = {
		 
        };

        var table = $('#table_id_example').DataTable({
        "order":[[4, 'desc']],
        "lengthMenu": [ 20, 50, 100 ],
        language:{
            "sProcessing": "處理中...",
            "sLengthMenu": "顯示 _MENU_ 項結果",
            "sZeroRecords": "没有匹配結果",
            "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "表中數據為空",
            "sLoadingRecords": "載入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首頁",
                "sPrevious": "上一頁",
                "sNext": "下一頁",
                "sLast": "末頁"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        },
        "ajax": {
            	"url": "tb_ajax.php",
            	"type":"POST",
            	"data": function ( d ) {
                   	return  $.extend(d, ajax_data);
                }
        },
        "processing": true,
        "serverSide": true
       
      });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>


