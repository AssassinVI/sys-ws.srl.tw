<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
 <style type="text/css">
 	.group_name{ color: #007d71; font-weight: 600; margin: 17px 0px; }
 </style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php
 if ($_GET) { //刪除
 	$Tb_index=$_GET['Tb_index'];
	$Admin=$new_pdo->select("SELECT * FROM sysAdmin WHERE Tb_index=:Tb_index", ['Tb_index'=>$Tb_index], 'one');

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='sysAdmin';
	$new_pdo->hs_old_id=$Tb_index;
	$new_pdo->hs_h_location='管理者管理';
	$new_pdo->hs_h_action_type='delete';
	$new_pdo->hs_h_title='刪除管理者-'.$Admin['name'];
	  //-- 舊資料 --
	$new_pdo->old_data();

    $where=array('Tb_index'=>$Tb_index);
 	pdo_delete('sysAdmin', $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
 }

 $pdo=pdo_conn();

 //-- 權限 --
 $sql_group=$pdo->prepare("SELECT Tb_index, Group_name FROM sysAdminGroup");
 $sql_group->execute();


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">管理帳號列表</h2>
		<p>本頁面條列目前網站權限，請勿任意刪除，感恩</p>
	   <div class="new_div">
	    <a href="manager.php">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>
	  </div>
	</div>

	<div class="row">
      
      <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@ 管理員 @@@@@@@@@@@@@@@@@@@@@@@@ -->
   		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
			  <h2 class="group_name">系統管理員</h2>
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th width="50">#</th>
								<th width="150">名稱</th>
								<th width="150">帳號</th>
								<th width="250">信箱</th>
								<th width="50">狀態</th>
								<th>使用期間</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>
						<?php 
                         $sql=$pdo->prepare("SELECT * FROM sysAdmin WHERE admin_per=:admin_per");
                         $sql->execute(array("admin_per"=>'admin'));
						 $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['name']?></td>
								<td><?php echo $row['admin_id']?></td>
                                <td><?php echo $row['email']?></td>
								<td><input class="checkbox switch switch-primary" disabled id="settings7" type="checkbox" 
								 <?php echo $check=$row['is_use']=='1' ? 'checked' : '';?> /></td>
								<td>
								  <?php echo $row['StartDate'].' ~ '.$row['EndDate'];?>
								</td>

								<td class="text-right">

								<a href="manager.php?Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['name']?>] ?')) {return false;}">
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

	 <?php 
     while ($row_group=$sql_group->fetch(PDO::FETCH_ASSOC)) {
	 ?>
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
			  <h2 class="group_name"><?php echo $row_group['Group_name']?></h2>
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th width="50">#</th>
								<th width="150">名稱</th>
								<th width="150">帳號</th>
								<th width="250">信箱</th>
								<th width="100">分析進階功能</th>
								<th width="50">狀態</th>
								<th>使用期間</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>
						<?php 
                         $sql=$pdo->prepare("SELECT * FROM sysAdmin WHERE admin_per=:admin_per");
                         $sql->execute(array("admin_per"=>$row_group['Tb_index']));
						 $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['name']?></td>
								<td><?php echo $row['admin_id']?></td>
                                <td><?php echo $row['email']?></td>
								<td><input class="checkbox switch switch-primary" disabled id="settings7" type="checkbox" 
								 <?php echo $check=$row['is_an_all']=='1' ? 'checked' : '';?> /></td>
								<td><input class="checkbox switch switch-primary" disabled id="settings7" type="checkbox" 
								 <?php echo $check=$row['is_use']=='1' ? 'checked' : '';?> /></td>
								<td>
								  <?php 
								    if($row['StartDate']=='0000-00-00' && $row['EndDate']=='0000-00-00'){
										$Date_txt='';
									}
									elseif(strtotime($row['StartDate'])<= strtotime('today') && $row['EndDate']=='0000-00-00'){
										$Date_txt='';
									}
									elseif(strtotime($row['StartDate'])<= strtotime('today') && strtotime($row['EndDate']) >= strtotime('today')){
										$Date_txt='';
									}
									else{

										$Date_txt='<span class="text-danger">非權限使用期間</span>';
									}
								    echo $row['StartDate'].' ~ '.$row['EndDate'].'｜'.$Date_txt;
								  ?>
								</td>

								<td class="text-right">

								<a href="manager.php?Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['name']?>] ?')) {return false;}">
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
	<?php
     }
	?>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>


