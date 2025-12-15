<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	#sel_com{ padding: 5px; margin-right: 5px; }
	a.disabled{
		pointer-events: none;
		cursor: no-drop;
	}
	.download_box,
	.static_page_box{
		display: inline-block;
		position: relative;
	}
	.del_btn{
		position: absolute;
		top: -5px;
		right: -5px;
	}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

$pdo=pdo_conn();//資料庫初始化

if ($_POST) {
   // -- 更新排序 --
  for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
    $data=["OrderBy"=>$_POST['OrderBy'][$i]];
    $where=["Tb_index"=>$_POST['Tb_index'][$i]];
    pdo_update('build_case', $data, $where);
  }
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除

	//-- 歷史紀錄 --
	$new_pdo->hs_tb_name='build_case';
	$new_pdo->hs_old_id=$_GET['Tb_index'];
	$new_pdo->hs_h_location='專案編輯';
	$new_pdo->hs_h_action_type='update';
	$new_pdo->hs_h_title='修改專案(刪除)-'.$_GET['aTitle'];
	  //-- 舊資料 --
	$new_pdo->old_data();
    
    $param=['OnLineOrNot'=>'0'];
    $where=['Tb_index'=>$_GET['Tb_index']];

   	 pdo_update('build_case', $param, $where);

	//-- 新增歷史紀錄 --
	$new_pdo->add_history();
   }

   $com_id=empty($_GET['com_id']) ? '':$_GET['com_id'];

   $sql=$pdo->prepare("SELECT Tb_index, aTitle, OrderBy, OnLineOrNot, version, google_code, ga4_code, EndDate
   					   FROM build_case WHERE com_id LIKE :com_id AND OnLineOrNot!=-1 ORDER BY OnLineOrNot DESC, OrderBy DESC, Tb_index DESC");
   $sql->execute( ['com_id'=>'%'.$com_id.'%'] );

   
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

       
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	<h5><?php echo $page_name['MT_Name']?>列表 </h5>
			 	<div class="ibox-tools">
			 		        <select id="sel_com">
			 		        	<option value="all">全部</option>
			 		        	<?php
                                   $sql_com=$pdo->prepare("SELECT * FROM company ORDER BY Tb_index ASC");
                                   $sql_com->execute();
                                   while ($row_com=$sql_com->fetch(PDO::FETCH_ASSOC)) {
                                  
                                   	if ($_SESSION['admin_per']=='admin' || in_array($row_com['Tb_index'], $_SESSION['group_com'])) {
                                   		echo '<option value="'.$row_com['Tb_index'].'">'.$row_com['com_name'].'</option>';
                                   	}
                                   }
			 		        	?>

			 		        </select>
			 		        <button id="sort_btn" type="button" class="btn btn-default">
			 		        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>

			 			    <a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
			 		        <button type="button" class="btn btn-success">
			 		        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
			 		        </a>
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table no-margin table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th class="none_420">ID</th>
								<th>專案名稱</th>
								<th class="none_420">google分析</th>
								<th class="none_420">google GA4</th>
								<!-- <th class="none_420">排序</th> -->
								<th class="none_420">啟用/停用</th>
								<th class="none_420">版本</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody id="case_tb">
						
						<?php 
						 $pdo_job= new PDO_fun('job');

						$i=1; 
						
						while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

							
							 if($_SESSION['admin_per']=='admin' || in_array($row['Tb_index'], $_SESSION['group_case'])){
							

                              $OnLineOrNot=$row['OnLineOrNot']=='1' ? '<span class="label">啟用</span>' : '<span class="label label-danger">停用</span>';

							  //-- 停用日期判斷 --
							  if($row['OnLineOrNot']=='1' && $row['EndDate']!='0000-00-00' && strtotime($row['EndDate'])<strtotime(date('Y-m-d'))){
								  $OnLineOrNot='<span class="label label-danger">停用 (到期日：'.$row['EndDate'].')</span>';
							  }

                              switch ($row['version']) {
                              	case '1':
                              	  $version='<span style="color:#2196f3; padding: 5px 10px; border: 1px solid; background: #e8f5ff;">正常版</span>';
                              		break;
                              	case '2':
                              	  $version='<span style="color:#2196f3; padding: 5px 10px; border: 1px solid; background: #e8f5ff;">全屏滑動版</span>';
                              		break;
                              	case '0':
                              	  $version='<span style="padding: 5px 10px; border: 1px solid; background: #e7e7e7;>簡易版</span>';
                              		break;
                                case '3':
                              	  $version='<span style="color:#bc2cd5; padding: 5px 10px; border: 1px solid; background: #fbe3ff;">特殊版</span>';
                              		break;
                              }
							  
							  $website_job=$pdo_job->select("SELECT COUNT(*) as total FROM put_website WHERE case_id=:case_id", ['case_id'=>$row['Tb_index']], 'one');
							   
							 
							  if((int)$website_job['total']>0){
								$put_web='<a class="none_420 btn btn-default btn-sm put_website disabled" case_id="'.$row['Tb_index'].'" href="javascript:;" >
											<i class="fa fa-cogs" aria-hidden="true"></i> 匯出中..
										</a>';
							  }
							  elseif( is_file('/home/srltw/sys-ws.srl.tw/system/cron_job/website_tmp/'.$row['Tb_index'].'.zip')){
								$put_web='<span class="download_box">
										  <a class="none_420 btn btn-primary btn-sm download_website" case_id="'.$row['Tb_index'].'" href="javascript:;" >
											<i class="fa fa-file-zip-o" aria-hidden="true"></i> 下載網站
										  </a>
										  <a href="javascript:;" class="badge badge-danger del_btn" case_id="'.$row['Tb_index'].'">x</a>
										  </span>';
							  }
							  else{
								$put_web='<a class="none_420 btn btn-default btn-sm put_website" case_id="'.$row['Tb_index'].'" href="javascript:;" >
												<i class="fa fa-file-zip-o" aria-hidden="true"></i> 匯出網站
											</a>';
							  }


							  if(is_file('/home/srltw/ws.srl.tw/product_html/'.$row['Tb_index'].'/index.html')){
								$static_page='<span class="static_page_box">
											    <a class="none_420 btn btn-default btn-sm static_page_btn" case_id="'.$row['Tb_index'].'" href="javascript:;" >更新靜態網頁</a>
												<a href="javascript:;" class="badge badge-danger del_btn" case_id="'.$row['Tb_index'].'">x</a>
											  </span>';
							  }
							  else{
								$static_page='<a class="none_420 btn btn-success btn-sm static_page_btn" case_id="'.$row['Tb_index'].'" href="javascript:;" >產生靜態網頁</a>';
							  }
							  




							  

							  echo '
							   <tr>
									<td>'.$i.'</td>
									<td class="none_420">'.$row['Tb_index'].'</td>
									<td style="font-size: 1.5em;">'.$row['aTitle'].'</td>
									<td class="none_420">'.$row['google_code'].'</td>
									<td class="none_420">'.$row['ga4_code'].'</td>
									<!--<td class="none_420"><input type="number" class="sort_in" name="OrderBy" Tb_index="'.$row['Tb_index'].'" value="'.$row['OrderBy'].'"></td>-->
									<td class="none_420">'.$OnLineOrNot.'</td>
									<td class="none_420"><span style="color:#2196f3">'.$version.'</span></td>

									<td class="text-right">
										'.$static_page.'
										<a class="none_420 btn btn-success btn-sm" href="history.php?MT_id='.$_GET['MT_id'].'&Tb_index='.$row['Tb_index'].'" >
											<i class="fa fa-history" aria-hidden="true"></i> 歷史紀錄
										</a>
										'.$put_web.'
										<a class="none_420 btn btn-default btn-sm" href="manager.php?MT_id='.$_GET['MT_id'].'&Tb_index='.$row['Tb_index'].'" ><i class="fa fa-pencil-square" aria-hidden="true"></i> 編輯</a>
										<a class="none_420 btn btn-danger btn-sm" href="admin.php?MT_id='.$_GET['MT_id'].'&Tb_index='.$row['Tb_index'].'&aTitle='.$row['aTitle'].'" 
										onclick="if (!confirm(\'確定要刪除 ['.$row['aTitle'].'] ?\')) {return false;}"><i class="fa fa-trash" aria-hidden="true"></i> 刪除</a>
									</td>
								</tr> 
							  ';

							  $i++; 
							}
						}

						$pdo_job->close();
					   ?>
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

		 $(".iframe_box").fancybox({
		 	'padding'               :'0',
            'type'                  : 'iframe'
		 });
      
        //-------------- 排序 ---------------
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

     
     //----------------- 選公司 ------------------
		$('#sel_com').change(function(event) {
			$.ajax({
				url: 'admin_ajax.php',
				type: 'POST',
				dataType: 'json',
				data: {
					com_id: $(this).val(),
					type:'company'
			    },
				success:function (data) {
			     $('#case_tb').html('');
	 			   var x=1;
	 			   var txt='';
	 		   	  $.each(data, function() {

	 		   	  	var OnLineOrNot=this['OnLineOrNot']=='1' ? '<span class="label">啟用</span>' : '<span class="label label-danger">停用</span>';

	 		   	  	switch(this['version']){
	 		   	  		case '1':
	 		   	  		 var version='<span style="color:#2196f3; padding: 5px 10px; border: 1px solid; background: #e8f5ff;">正常版</span>';
	 		   	  		 break;
	 		   	  		case '2':
	 		   	  		 var version='<span style="color:#2196f3; padding: 5px 10px; border: 1px solid; background: #e8f5ff;">全屏滑動版</span>';
	 		   	  		 break;
	 		   	  		case '0':
	 		   	  		 var version='<span style="padding: 5px 10px; border: 1px solid; background: #e7e7e7;>簡易版</span>';
	 		   	  		 break;
	 		   	  		case '3':
	 		   	  		 var version='<span style="color:#bc2cd5; padding: 5px 10px; border: 1px solid; background: #fbe3ff;">特殊版</span>';
                         break;
	 		   	  	}
	 				    txt+='<tr>';
						txt+=' <td>'+x+'</td>';
						txt+=' <td>'+this['Tb_index']+'</td>';
						txt+=' <td style="font-size: 1.5em;">'+this['aTitle']+'</td>';
						txt+=' <td class="none_420">'+this['google_code']+'</td>'
						txt+=' <td><input type="number" class="sort_in" name="OrderBy" Tb_index="'+this['Tb_index']+'" value="'+this['OrderBy']+'"></td>';
						txt+=' <td>'+OnLineOrNot+'</td>';
						txt+=' <td>'+version+'</td>';
						txt+=' <td class="text-right">';
						txt+='    <a class="btn btn-default btn-sm" href="manager.php?MT_id=site2017111611004594&Tb_index='+this['Tb_index']+'" ><i class="fa fa-pencil-square" aria-hidden="true"></i>編輯</a>';
						txt+='    <a class="btn btn-danger btn-sm" href="admin.php?MT_id=site2017111611004594&Tb_index='+this['Tb_index']+'&aTitle='+this['aTitle']+'" onclick="if (!confirm(\'確定要刪除 ['+this['aTitle']+'] ?\')) {return false;}"><i class="fa fa-trash" aria-hidden="true"></i>刪除</a>';
						txt+=' </td>';
						txt+='</tr>';
				       x++;
	 			   });

	 			$('#case_tb').append(txt);
				}
			});
			
		});


		//-- 匯出網站 --
		$('.put_website').click(function (e) { 
			let _this=$(this);
			let case_id=$(this).attr('case_id');

			if(confirm('是否要匯出網站??')){
				$.ajax({
					type: "POST",
					url: "admin_ajax.php",
					data: {
						type: 'put_website',
						case_id: case_id
					},
					dataType: "json",
					success: function (data) {
						if(data['success']){
							alert(data['msg']);
							_this.addClass('disabled');
							_this.html('<i class="fa fa-cogs" aria-hidden="true"></i> 匯出中..');
						}
						else{
							alert(data['msg']);
						}
					}
				});
			}
		
		});

		//-- 下載網站 --
		$('.download_website').click(function (e) { 
			let case_id=$(this).attr('case_id');
			$.ajax({
				type: "POST",
				url: "admin_ajax.php",
				data: {
					type: 'download_website',
					case_id: case_id
				},
				success: function (data) {
					//console.log(data);
					location.replace('download_website.php?file='+data);
				}
			});
		});


		//-- 刪除匯出的檔案 --
		$('.download_box .del_btn').click(function (e) { 
			if(confirm('是否要刪除匯出的檔案??')){
				let case_id=$(this).attr('case_id');
				$.ajax({
					type: "POST",
					url: "admin_ajax.php",
					data: {
						type: 'delete_website',
						case_id: case_id
					},
					dataType: "json",
					success: function (data) {
						if(data.success){
							alert(data.msg);
							location.reload();
						}
						else{
							alert(data.msg);
						}
					}
				});
			}
		});

		//-- 刪除靜態網頁 --
		$('.static_page_box .del_btn').click(function (e) { 
			if(confirm('是否要刪除靜態網頁??')){
				let case_id=$(this).attr('case_id');
				$.ajax({
					type: "POST",
					url: "admin_ajax.php",
					data: {
						type: 'delete_static_page',
						case_id: case_id
					},
					dataType: "json",
					success: function (data) {
						if(data.success){
							alert(data.msg);
							location.reload();
						}
						else{
							alert(data.msg);
						}
					}
				});
			}
		});


		//-- 產生靜態網頁 --
		$('.static_page_btn').click(function (e) { 
			e.preventDefault();
			let _this=$(this);
			let case_id=$(this).attr('case_id');
			$.ajax({
				type: "POST",
				url: "admin_ajax.php",
				data: {
					type: 'static_page',
					case_id: case_id
				},
				dataType: "json",
				success: function (data) {
					if(data.success){
						alert(data.msg);
						_this.removeClass('btn-success');
						_this.addClass('btn-default');
						_this.html('更新靜態網頁');
					}
					else{
						alert(data.msg);
					}
				}
			});
		});
		
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
