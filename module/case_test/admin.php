<?php include("../../core/page/header01.php");//載入頁面heaer01?>
<style type="text/css">
	#sel_case{ padding: 5px; margin-right: 5px; }
	.sk-spinner-wave.sk-spinner{ display: inline-block; }
	.sk-spinner-wave div{ background-color: #bbb; }
	.loading{ display: none; opacity: 0; position: absolute; width: 100%; height: 100%; text-align: center; }
	.loading h4{ font-size: 20px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
$pdo=pdo_conn('srltw_test_case');//資料庫初始化


if ($_GET) {


   $com_id=empty($_GET['com_id']) ? '':$_GET['com_id'];

   $sql=$pdo->prepare("SELECT Tb_index, aTitle, OrderBy, OnLineOrNot, version FROM build_case");
   $sql->execute();

   
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
			 		        <select id="sel_case">
                                <option value="">-- 選擇建案 --</option>
			 		        	<?php
			 		        	   $pdo_case=pdo_conn();
                                   $sql_com=$pdo_case->prepare("SELECT Tb_index, aTitle FROM build_case WHERE OnLineOrNot=1 ORDER BY Tb_index ASC");
                                   $sql_com->execute();
                                   while ($row_com=$sql_com->fetch(PDO::FETCH_ASSOC)) {
                                   	
                                   		echo '<option value="'.$row_com['Tb_index'].'">'.$row_com['aTitle'].'</option>';
                                   	
                                   }
                                   $pdo_case=NULL;
			 		        	?>

			 		        </select>

			 		        <button id="copy_case" type="button" class="btn btn-success">複製建案</button>
			 		        <!-- <button id="sort_btn" type="button" class="btn btn-default">
			 		        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>

			 			    <a href="manager.php?MT_id=<?php //echo $_GET['MT_id'];?>">
			 		        <button type="button" class="btn btn-success">
			 		        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
			 		        </a> -->
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th class="none_420">ID</th>
								<th>專案名稱</th>
								<th class="none_420">排序</th>
								<th class="none_420">啟用/停用</th>
								<th class="none_420">版本</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody id="case_tb">

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

							//if($_SESSION['admin_per']=='admin' || in_array($row['Tb_index'], $_SESSION['group_case'])){

                              $OnLineOrNot=$row['OnLineOrNot']=='1' ? '<span class="label">啟用</span>' : '<span class="label label-danger">停用</span>';

                              switch ($row['version']) {
                              	case '1':
                              	  $version='<span style="color:#2196f3; padding: 5px 10px; border: 1px solid; background: #e8f5ff;">正常版</span>';
                              		break;
                              	case '0':
                              	  $version='<span style="padding: 5px 10px; border: 1px solid; background: #e7e7e7;>簡易版</span>';
                              		break;
                                case '3':
                              	  $version='<span style="color:#bc2cd5; padding: 5px 10px; border: 1px solid; background: #fbe3ff;">特殊版</span>';
                              		break;
                              }
							?>
							<tr>
								<td><?php echo $i?></td>
								<td class="none_420"><?php echo $row['Tb_index'];?></td>
								<td style="font-size: 1.5em;"><?php echo $row['aTitle'];?></td>
								<td class="none_420"><input type="number" class="sort_in" name="OrderBy" Tb_index="<?php echo $row['Tb_index'];?>" value="<?php echo $row['OrderBy'] ?>"></td>
								<td class="none_420"><?php echo $OnLineOrNot;?></td>
								<td class="none_420"><span style="color:#2196f3"><?php echo $version;?></span></td>

								<td class="text-right">
                                
                                <a class=" btn btn-default btn-sm iframe_box" href="../case_url_test_case/catch_web.php?Tb_index=<?php echo $row['Tb_index'];?>"><i class="fa fa-globe"></i> 網址</a>
								<a class="none_420 btn btn-info btn-sm" href="../case_area_test_case/case_fun_box.php?Tb_index=<?php echo $row['Tb_index'];?>"><i class="fa fa-cubes"></i> 功能區塊</a>
								<a class=" btn btn-warning btn-sm" href="javascript:;" onclick="replace_case('<?php echo $row['Tb_index'];?>', '<?php echo $row['aTitle'];?>')"><i class="fa fa-cubes"></i> 替換</a>
								<a class="none_420 btn btn-danger btn-sm" href="javascript:;" onclick="del_test_case('<?php echo $row['Tb_index'];?>', '<?php echo $row['aTitle'];?>', this)"><i class="fa fa-trash-o"></i> 刪除</a>

					
								</td>
							</tr>
						<?php 
 
						    $i++; 
                          // }
					    }
					     ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="loading">
			<h4>
			<span>資料複製中... </span>
			<div class="sk-spinner sk-spinner-wave">
                                    <div class="sk-rect1"></div>
                                    <div class="sk-rect2"></div>
                                    <div class="sk-rect3"></div>
                                    <div class="sk-rect4"></div>
                                    <div class="sk-rect5"></div>
                                </div>
		    </h4>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php $pdo=NULL;?>
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {

		 $(".iframe_box").fancybox({
		 	'padding'               :'0',
            'type'                  : 'iframe'
		 });
      
     
     //-----------------  複製 ------------------
     $('#copy_case').click(function(event) {

     	if ($('#sel_case').val()=='') {
     		alert('請選擇建案');
     	}
     	else{
     	  $.ajax({
     	  	url: 'copy_case_ajax.php',
     	  	type: 'POST',
     	  	dataType: 'json',
     	  	data: {
     	  		Tb_index: $('#sel_case').val()
     	  	},
     	  	success:function (data) {
                
                var txt='';
     	  		var OnLineOrNot=data['OnLineOrNot']=='1' ? '<span class="label">啟用</span>' : '<span class="label label-danger">停用</span>';

	 		   	  	switch(data['version']){
	 		   	  		case '1':
	 		   	  		 var version='正常版';
	 		   	  		 break;
	 		   	  		case '0':
	 		   	  		 var version='簡易版';
	 		   	  		 break;
	 		   	  		case '3':
	 		   	  		 var version='特殊版';

	 		   	  	}
	 				    txt+='<tr>';
						txt+=' <td></td>';
						txt+=' <td>'+data['Tb_index']+'</td>';
						txt+=' <td style="font-size: 1.5em;">'+data['aTitle']+'</td>';
						txt+=' <td><input type="number" class="sort_in" name="OrderBy" Tb_index="'+data['Tb_index']+'" value="'+data['OrderBy']+'"></td>';
						txt+=' <td>'+OnLineOrNot+'</td>';
						txt+=' <td>'+version+'</td>';
						txt+=' <td class="text-right">';
						txt+='    <a class=" btn btn-default btn-sm iframe_box" href="../case_url_test_case/catch_web.php?Tb_index='+data['Tb_index']+'"><i class="fa fa-globe"></i> 網址</a>';
						txt+='    <a class="btn btn-info btn-sm" href="../case_area_test_case/case_fun_box.php?Tb_index='+data['Tb_index']+'"><i class="fa fa-cubes"></i> 功能區塊</a>';
						txt+='    <a class=" btn btn-warning btn-sm" href="javascript:;" onclick="replace_case(\''+data['Tb_index']+'\', \''+data['aTitle']+'\')"><i class="fa fa-cubes"></i> 替換</a>';
						txt+='    <a class="btn btn-danger btn-sm" href="javascript:;" onclick="del_test_case(\''+data['Tb_index']+'\', \''+data['aTitle']+'\', this)"><i class="fa fa-trash-o"></i> 刪除</a>';
						txt+=' </td>';
						txt+='</tr>';
     	  		
     	  		$('#case_tb').append(txt);

     	  	},
     	  	beforeSend:function () {
     	  		$('.loading h4 span').html('資料複製中...');
     	  		$('.loading').css('display', 'block');
     	  		TweenMax.to( '.loading', 0.5, { opacity:1});
     	  	},
     	  	complete:function () {
     	  		TweenMax.to( '.loading', 0.5, { opacity:0, display:'none'});
     	  	}
     	  });
     	}
     	
     	
     });
     
    
	});


	function del_test_case(Tb_index, case_name, dom_this) {
		var _this=dom_this;
		if (confirm('是否要刪除"'+case_name+'" 測試版??')) {
           $.ajax({
           	url: 'del_case_ajax.php',
           	type: 'POST',
           	data: {Tb_index: Tb_index},
           	success:function () {
           		_this.parentNode.parentNode.remove();
           	}
           });
           
		}
	}


	function replace_case(Tb_index, case_name) {
		if (confirm('是否要替換"'+case_name+'" 測試版??')){
          if (confirm('警告!! 替換後無法再恢復原狀，是否要替換??')){
             $.ajax({
             	url: 'replace_case_ajax.php',
             	type: 'POST',
             	data: {Tb_index: Tb_index},
             	success:function () {
             	alert('替換完成!');	
             },
     	  	beforeSend:function () {
     	  		$('.loading h4 span').html('資料替換中...');
     	  		$('.loading').css('display', 'block');
     	  		TweenMax.to( '.loading', 0.5, { opacity:1});
     	  	},
     	  	complete:function () {
     	  		TweenMax.to( '.loading', 0.5, { opacity:0, display:'none'});
     	  	}
             });
		  }
		}
	}
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
