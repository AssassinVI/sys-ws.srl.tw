<?php  include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.is_deal{ color: green; }
	.no_deal{ color: red; }
</style>
<?php  include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
  // -- 更新排序 --
  for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
    $data=array("OrderBy"=>$_POST['OrderBy'][$i]);
    $where=array("Tb_index"=>$_POST['Tb_index'][$i]);
    pdo_update('case_news', $data, $where);
  }
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除
	$news=$new_pdo->select("SELECT * FROM case_news WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], 'one');

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='case_news';
	$new_pdo->hs_old_id=$_GET['Tb_index'];
	$new_pdo->hs_h_location='建案新聞';
	$new_pdo->hs_h_action_type='delete';
	$new_pdo->hs_h_title='刪除建案新聞-'.$news['aTitle'];
	  //-- 舊資料 --
	$new_pdo->old_data();

    $where=array('Tb_index'=>$_GET['Tb_index']);
   	 pdo_delete('case_news', $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
   }

   $case_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['case_id']]);
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT * FROM case_news WHERE case_id=:case_id ORDER BY OrderBy DESC, StartDate DESC, Tb_index DESC");
   $sql->execute(['case_id'=>$_GET['case_id']]);
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $case_name['aTitle'];?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

        <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>

	    <a href="manager.php?case_id=<?php echo $_GET['case_id'];?>">
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
								<th>新聞標題</th>
								<th>來源</th>
								<th>排序</th>
								<th>目前狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['aTitle'] ?></td>
								<td><?php echo $row['source'] ?></td>
								<td><input type="number" class="sort_in" name="OrderBy" Tb_index="<?php echo $row['Tb_index'];?>" value="<?php echo $row['OrderBy'] ?>"></td>
								<td><?php echo $online= $row['OnLineOrNot']==1 ? '上線中' : '已下線';?></td>
								

								<td class="text-right">

								<a href="manager.php?case_id=<?php echo $_GET['case_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="news_admin.php?case_id=<?php echo $_GET['case_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Tb_index']?>] ?')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
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
		$("#sort_btn").click(function(event) {
		        
        var arr_OrderBy=new Array();
        var arr_Tb_index=new Array();

          $(".sort_in").each(function(index, el) {
             
             arr_OrderBy.push($(this).val());
             arr_Tb_index.push($(this).attr('Tb_index'));
          });

          var data={ 
                        OrderBy: arr_OrderBy,
                       Tb_index: arr_Tb_index 
                      };
             ajax_in('news_admin.php', data, 'no', 'no');

          alert('更新排序');
         location.replace('news_admin.php?case_id=<?php echo $_GET['case_id'];?>');
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
