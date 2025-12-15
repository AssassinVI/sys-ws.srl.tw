<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 


ini_set('display_errors','1');
error_reporting(E_ALL);

if ($_POST) {
	// -- 更新排序 --
   for ($i=0; $i <count($_POST['Tb_index']) ; $i++) { 
	 $data=["OrderBy"=>($i+1)];
	 $where=["Tb_index"=>$_POST['Tb_index'][$i]];
	 pdo_update('line_msg_bot_group', $data, $where);
   }
  }

if ($_GET) {

   $pdo=new PDO_fun();

   if (!empty($_GET['rowid'])) {//刪除
     $where=array('rowid'=>$_GET['rowid']);
   	 pdo_delete('line_msg_bot_group', $where);
   }
   
   $sql_row= $pdo->select("SELECT lmbg.rowid, lmbg.groupName, lmbg.StartDate, ac.pageName, ac.pageId
   							   FROM line_msg_bot_group as lmbg
							   LEFT JOIN appFBpage as ac ON lmbg.groupId = ac.line_bot_group
							   ORDER BY lmbg.rowid");

	$pdo->close();
}

?>


<div class="wrapper wrapper-content animated fadeInRight" style="max-width: 1000px;">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表 </h2>
		<!-- <p class="text-danger">可使用點擊拖曳排序</p> -->
	   <div class="new_div">

        <!-- <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button> -->

	    <!-- <a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a> -->
		
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12" >
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<!-- <th>置頂</th> -->
								<th>LINE群組名稱</th>
								<th>建立時間</th>
								<th>關聯FB粉專</th>
								<th class="text-right">管理</th>
							</tr>
						</thead>
						<tbody >
							

						<?php 
							$i=1; 
							foreach ($sql_row as $row) {

								echo '
								<tr>
									<td>'.$row['groupName'].'</td>
									<td>'.$row['StartDate'].'</td>
									<td><a href="https://www.facebook.com/'.$row['pageId'].'" target="_blank">'.$row['pageName'].'</a></td>
									<td class="text-right">

									<a href="admin.php?MT_id='.$_GET['MT_id'].'&rowid='.$row['rowid'].'" 
									onclick="if (!confirm(\'確定要刪除 ['.$row['groupName'].'] ?\')) {return false;}">
										<button type="button" class="btn btn-rounded btn-warning btn-sm">
										<i class="fa fa-trash" aria-hidden="true"></i>
										刪除</button>
									</a>
						
									</td>
								</tr>';
							$i++; }
						?>
							
						</tbody>
					</table>

					<input type="hidden" class="mt_id" value="<?php echo $_GET['MT_id'];?>">
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {

		let nowURL=new URL(location.href);
		let news_type=nowURL.searchParams.get('news_type');
		$('#select_type').val(news_type);

		$('#select_type').change(function (e) { 
			e.preventDefault();
			location.href='admin.php?MT_id=site2023080912310519&news_type='+$(this).val();
		});


		$( ".sort_tb" ).sortable({
             revert: 300,
             update: function( event, ui ) {
             	$("#sort_btn").css('display', 'inline-block');
             }
   	    });

        $("#sort_btn").click(function(event) {
            var mt_id=$('.mt_id').val();
            var arr_Tb_index=new Array();
    
                $(".sort_in").each(function(index, el) {
                    arr_Tb_index.push($(this).val());
                });
                
                var data={ Tb_index: arr_Tb_index };
                ajax_in('admin.php', data, 'no', 'no');
    
                alert('更新排序');
                location.replace(`admin.php?MT_id=${mt_id}`);
        });


		//-- 置頂 --
		// $('[name="is_top"]').change(function (e) {
		// 	let is_top_ch=$(this).prop('checked') ? 1:0;
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "ajax.php",
		// 		data: {
		// 			type: 'is_top',
		// 			mt_id: $('[name="mt_id"]').val(),
		// 			Tb_index: $(this).attr('Tb_index'),
		// 			is_top:is_top_ch
		// 		},
		// 		dataType: "json",
		// 		success: function (data) {
		// 		  if(data.success){
		// 			location.reload();
		// 		  }
		// 		  else{
		// 			alert(data.msg);
		// 		  }
		// 		}
		// 	});
		// });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
