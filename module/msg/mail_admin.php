<?php  include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.is_deal{ color: green; }
	.no_deal{ color: red; }
	<?php 
      if ($_GET['case_id']=='case2019032609430788') {
    ?>
      .case2019032609430788{display: none;}
    <?php
      }
	?>
</style>
<?php  include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
  $is_process=empty($_POST['is_process']) ? '0' : '1';
  $param=array('is_process'=>$is_process);
  $where=array('Tb_index'=>$_POST['Tb_index']);
  pdo_update('call_record_tb', $param, $where);
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除

     $where=array('Tb_index'=>$_GET['Tb_index']);
   	 pdo_delete('call_record_tb', $where);
   }

   $case_name=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['case_id']]);
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT * FROM call_record_tb WHERE case_id=:case_id ORDER BY set_time DESC");
   $sql->execute(['case_id'=>$_GET['case_id']]);
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $case_name['aTitle'];?> 列表</h2>
	   <div class="new_div">

        <a id="excel_down" href="put_csv.php?case_id=<?php echo $_GET['case_id'];?>" class="btn btn-success">下載Excel檔</a>

	    <!--<a href="manager.php?MT_id=<?php //echo $_GET['MT_id'];?>">
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
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th class="none_420">單號</th>
								<th>姓名</th>
								<th>電話</th>
								<th class="none_420 case2019032609430788">E-mail</th>
								<th class="none_420 case2019032609430788">處理狀態 ( 點擊更新狀態 )</th>
								<th class="text-right case2019032609430788">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php 
						$total=$sql->rowCount(); 
						$i=1; 
						while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $total?></td>
								<td class="none_420"><?php echo $row['Tb_index'] ?></td>
								<td><?php echo $row['use_name'] ?></td>
								<td><?php echo $row['phone'] ?></td>
								<td class="none_420 case2019032609430788"><?php echo $row['use_mail']?></td>
								<td class="none_420 case2019032609430788">
								 <form action="#" method="POST">
								<?php 
                                  if ($row['is_process']=='1') {
                                  	echo '<input type="submit" name="put" class="is_deal" value="已處理">
								    	   <input type="hidden" name="is_process" value="0">';
                                  }else{
                                  	echo '<input type="submit" name="put" class="no_deal" value="未處理">
								 	       <input type="hidden" name="is_process" value="1">';
                                  }
								?>
								<input type="hidden" name="Tb_index" value="<?php echo $row['Tb_index'];?>">
								 </form>
								</td>

								<td class="text-right case2019032609430788">

								<a href="manager.php?case_id=<?php echo $_GET['case_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a class="none_420" href="mail_admin.php?case_id=<?php echo $_GET['case_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Tb_index']?>] ?')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
								</a>

					
								</td>
							</tr>
						<?php $total--; }?>
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
             ajax_in('admin.php', data, 'no', 'no');

          alert('更新排序');
         location.replace('admin.php?MT_id=<?php echo $_GET['MT_id'];?>');
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
