<?php  include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	.sortable-list{ padding: 0px;  }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
// if ($_POST) {
//    // -- 更新排序 --
//   for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
//     $data=["OrderBy"=>$_POST['OrderBy'][$i]];
//     $where=["Tb_index"=>$_POST['Tb_index'][$i]];
//     pdo_update('FunBox', $data, $where);
//   }
// }


   // if (!empty($_GET['Tb_index'])) {//刪除

   //  $where=['Tb_index'=>$_GET['Tb_index']];

   // 	 pdo_delete('FunBox', $where);
   // }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT * FROM FunBox ORDER BY Tb_index DESC");
   $sql->execute();


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">功能區塊列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

        <!-- <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button> -->

	    <a href="manager.php">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>功能名稱</th>
								<th>按鈕樣式</th>
								<th>擴充功能</th>
								<th>狀態</th>
								<th>建立日期</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

                               $expansion=$row['expansion']==1? "擴充功能":"";
                               $OnLineOrNot=$row['OnLineOrNot']==1 ? "使用中":"關閉";
							?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['box_name'] ?></td>
								<td>
				
				
								   <ul class="sortable-list connectList agile-list ui-sortable">
								   	<li class="<?php echo $row['btn_type'];?>"><i class="fa <?php echo $row['btn_icon'];?>"></i> <?php echo $row['box_name'];?></li>
								   </ul>
								</td>
								<td><?php echo $expansion;?></td>
								<td><?php echo $OnLineOrNot;?></td>
								<td><?php echo $row['StartDate']?></td>
								

								<td class="text-right">

								<a href="manager.php?Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								
					
								</td>
							</tr>
						<?php $i++; }?>
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
		// $("#sort_btn").click(function(event) {
		        
  //       var arr_OrderBy=new Array();
  //       var arr_Tb_index=new Array();

  //         $(".sort_in").each(function(index, el) {
             
  //            arr_OrderBy.push($(this).val());
  //            arr_Tb_index.push($(this).attr('Tb_index'));
  //         });

  //         var data={ 
  //                       OrderBy: arr_OrderBy,
  //                      Tb_index: arr_Tb_index 
  //                     };
  //            ajax_in('admin.php', data, 'no', 'no');

  //         alert('更新排序');
  //        location.replace('admin.php?MT_id=<?php //echo $_GET['MT_id'];?>');
		// });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
