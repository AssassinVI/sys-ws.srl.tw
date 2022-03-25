<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
?>
<?php
 if ($_GET) { //刪除
	$Tb_index=$_GET['Tb_index'];
	$AdminGroup=$new_pdo->select("SELECT * FROM sysAdminGroup WHERE Tb_index=:Tb_index", ['Tb_index'=>$Tb_index], 'one');

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='sysAdminGroup';
	$new_pdo->hs_old_id=$Tb_index;
	$new_pdo->hs_h_location='群組權限管理';
	$new_pdo->hs_h_action_type='delete';
	$new_pdo->hs_h_title='刪除群組權限管理-'.$AdminGroup['Group_name'];
	  //-- 舊資料 --
	$new_pdo->old_data();

    $where=array('Tb_index'=>$Tb_index);
 	pdo_delete('sysAdminGroup', $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
 }
 $pdo=pdo_conn();
 $sql=$pdo->prepare("SELECT Tb_index, Group_name, StartDate, is_use
 					 FROM sysAdminGroup as sag");
 $sql->execute();
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">群組權限列表</h2>
		<p>本頁面條列目前網站權限，請勿任意刪除，感恩</p>
	   <div class="new_div">
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
								<th>群組權限名稱</th>
								<th>創建時間</th>
								<th>帳號數量</th>
								<th>狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>
						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
						   $total=$new_pdo->select("SELECT COUNT(*) as total FROM sysAdmin WHERE admin_per=:admin_per", ['admin_per'=>$row['Tb_index']], 'one');
						?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['Group_name']; ?></td>
								<td><?php echo $row['StartDate'];?></td>
							    <td><?php echo $total['total'];?></td>
								<td><input class="checkbox switch switch-primary" disabled id="settings7" type="checkbox" 
								 <?php echo $check=$row['is_use']=='1' ? 'checked' : '';?> /></td>
								

								<td class="text-right">

								<a href="manager.php?Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Group_name']?>] ?')) {return false;}">
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
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>


