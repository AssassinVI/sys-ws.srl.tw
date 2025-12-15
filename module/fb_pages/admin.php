<?php 
// ini_set('display_errors','1');
// error_reporting(E_ALL);

include("../../core/page/header01.php");//載入頁面heaer01?>

<style>
  .check_label{
	 display: flex;
	 gap: 3px;

	 input{
		margin: 0;
	 }
  }
</style>

<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
	// -- 更新排序 --
   for ($i=0; $i <count($_POST['Tb_index']) ; $i++) { 
	 $data=["OrderBy"=>($i+1)];
	 $where=["Tb_index"=>$_POST['Tb_index'][$i]];
	 pdo_update('appFBpage', $data, $where);
   }
}

if ($_GET) {

   $pdo=new PDO_fun();
   $sql=$pdo->select("SELECT pageId, pageName, pageToken, rowid
   					   FROM appFBpage 
					   WHERE admin_id=:admin_id 
					   ORDER BY rowid DESC", ['admin_id'=>$_SESSION['admin_id']]); // 只顯示該管理者的粉專
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<span class="text-danger">連結粉絲專頁後請至編輯"連結LINE群組"<br>如要刪除粉專，請重新設定連結</span>
	   <div class="new_div">

	   <!-- <button id="sort_btn" type="button" class="btn btn-default" style="display: none;">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>   -->

	    <a id="add_page_btn" class="btn btn-success" href="javascript:;">
			<i class="fa fa-plus" aria-hidden="true"></i> 連結粉絲專頁
        </a>
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		   <div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table id="table_id_example" class="table no-margin">
						<thead>
							<tr>
								<th>核取</th>
								<th width="50">置頂</th>
								<th>粉專ID</th>
								<th>粉專名稱</th>
								<th>已連結的LINE群組</th>
								<!-- <th>粉專Token</th> -->
								
								<th>管理</th>
							</tr>
						</thead>
						<tbody >
							
						
						</tbody>
					</table>

					<input type="hidden" class="mt_id" value="<?php echo $_GET['MT_id'];?>">

					<label class="check_label"><input type="checkbox" id="check_all">勾選全部</label>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-body">
				<button id="batch_btn" type="button" class="btn btn-success">批次置頂粉專</button>
				<button id="batch_not_btn" type="button" class="btn btn-danger">批次取消置頂粉專</button>
			</div>
		</div>

	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">

	const language={
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
	}

	//-- 勾選的FB粉專ID --
	let check_pages=[];

	$(document).ready(function() {

		let get_url=new URL(location.href);
		var ajax_data = {
             'MT_id': get_url.searchParams.get('MT_id'),
        };

		var table = $('#table_id_example').DataTable({
				responsive: true,
				"searching": false,
				"order":[[1, 'desc']],
				"lengthMenu": [ 20, 30, 50 ],
				language:language,
				"ajax": {
				"url": "adminTable_ajax.php",
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

		table.on('draw', function () {

			$('#check_all').prop('checked', false);

			$.each($('.check_one_page input'), function (index, item) { 
				if(check_pages.indexOf($(this).val())!=-1){
					$(this).prop('checked', true);
				}
			});
		});

		$('#add_page_btn').click(function (e) { 
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: "../../core/inc/fb/login.php",
				data: "data",
				dataType: "json",
				success: function (data) {
					console.log(data);
					if(data.success){
						// console.log(data.loginUrl);
						window.open(data.loginUrl, 'FB粉專連結', config='height=600,width=800');
						// $('#add_page_btn').attr('href', data.loginUrl);
						// $('#add_page_btn').attr('target', '_blank');
					}
					else{
						alert('粉絲專頁連結失敗，請稍後再試！');

					}
				}
			});
		});

		$('#check_all').change(function (e) { 

			$.each($('.check_one_page input'), function (index, item) {

				let page_index=check_pages.indexOf($(this).val());

				if($('#check_all').prop('checked')){
					if(page_index==-1){
						$('.check_one_page input').prop('checked', true);
						check_pages.push($(this).val());
					}
				}
				else{
					if(page_index!=-1){
						$('.check_one_page input').prop('checked', false);
						check_pages.splice(page_index, 1);
					}
				}
			});
			
			console.log(check_pages);
		});

		$('#table_id_example').on('change', '.check_one_page input', function () {
			let page_index=check_pages.indexOf($(this).val());
			if($(this).prop('checked')){
				check_pages.push($(this).val());
			}
			else{
				check_pages.splice(page_index, 1);
			}
		});

		$('#batch_btn').click(function (e) { 
			e.preventDefault();
			if(check_pages.length!=0){
				$.ajax({
					type: "POST",
					url: "ajax.php",
					data: {
						type: 'batch_top',
						page_id_arr: check_pages
					},
					dataType: "json",
					success: function (data) {
						alert(data.msg)
					}
				});
			}
			else{
				alert('請先勾選粉專!')
			}
			
		});

		$('#batch_not_btn').click(function (e) { 
			e.preventDefault();
			if(check_pages.length!=0){
				$.ajax({
					type: "POST",
					url: "ajax.php",
					data: {
						type: 'batch_not_top',
						page_id_arr: check_pages
					},
					dataType: "json",
					success: function (data) {
						alert(data.msg)
					}
				});
			}
			else{
				alert('請先勾選粉專!')
			}
		});


		// $('.token_btn').click(function (e) { 
		// 	e.preventDefault();
		// 	Swal.fire({
		// 		title: "粉專Token",
		// 		text: $(this).data('token'),
		// 		icon: "info"
		// 	});
		// });


		// $( ".sort_tb" ).sortable({
        //      revert: 300,
        //      update: function( event, ui ) {
        //      	$("#sort_btn").css('display', 'inline-block');
        //      }
   	    // });

		// $("#sort_btn").click(function(event) {
		// 	var mt_id=$('.mt_id').val();
		// 	var arr_Tb_index=new Array();

		// 		$(".sort_in").each(function(index, el) {
		// 			arr_Tb_index.push($(this).val());
		// 		});
				
		// 		var data={ Tb_index: arr_Tb_index };
		// 		ajax_in('admin.php', data, 'no', 'no');

		// 		alert('更新排序');
		// 		location.replace(`admin.php?MT_id=${mt_id}`);
		// });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
