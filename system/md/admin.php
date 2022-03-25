<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
require_once '../../core/inc/config.php';
?>
<?php
 if ($_POST) { //新增、修改

 	 if (empty($_POST['Tb_index'])) {
  		
            $param=array('Tb_index'=>'mod'.date('YmdHis').rand(0,99), 
            	         'Mod_name'=>$_POST['mod_name'], 
            	         'Mod_code'=>$_POST['mod_code'], 
            	          'version'=>$_POST['mod_version'], 
            	           'is_use'=>'1');

            pdo_insert('sysModule', $param);
            exit();
  	}
  	else{   
            
          $param=array(  'Mod_name'=>$_POST['mod_name'], 
            	         'Mod_code'=>$_POST['mod_code'], 
            	          'version'=>$_POST['mod_version'], 
            	           'is_use'=>$_POST['is_use']);

            $where=array('Tb_index'=>$_POST['Tb_index']);
            
            pdo_update('sysModule', $param, $where);
            exit();
  	}
 }
 else{

 	if ($_GET) {
  		
  	  $Tb_index=$_GET['Tb_index'];
  	  $where=array('Tb_index'=>$_GET['Tb_index']);
  	  pdo_delete('sysModule', $where);

  	}

 	 $pdo=pdo_conn();
     $sql=$pdo->prepare("SELECT * FROM sysModule");
     $sql->execute();
 }
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">模組列表</h2>
		<p>本頁面條列目前網站所使用的模組，請勿任意刪除，感恩</p>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-md-1 control-label" for="mod_name">模組名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="mod_name" value="">
							</div>
							<label class="col-md-1 control-label" for="mod_code">模組代碼</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="mod_code" value="">
							</div>
							<label class="col-md-1 control-label" for="mod_version">版本</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="mod_version" value="">
							</div>
							
							<div class="col-md-1">
								<button id="mod_btn" type="button" class="btn btn-info btn-block btn-raised">儲存</button>
							</div>
						</div>
					</form>
				</div>
			</div>
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
								<th>模組名稱</th>
								<th>模組代碼</th>
								<th>版本</th>
								<th>狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>
						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)){?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['Mod_name']?></td>
								<td><?php echo $row['Mod_code']?></td>
								<td><?php echo $row['version']?></td>
								<td><input class="checkbox switch switch-primary" id="settings7" disabled type="checkbox" <?php echo $check=$row['is_use']=='0' ? '' : 'checked';?> /></td>
								

								<td class="text-right">

								<a href="#" >
								<button type="button" class="btn btn-rounded btn-info btn-sm" onclick="mod_edit('<?php echo $row['Mod_name']?>', '<?php echo $row['Mod_code']?>', '<?php echo $row['version']?>', '<?php echo $check?>', '<?php echo $row['Tb_index']?>')">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?Tb_index=<?php echo $row['Tb_index'];?>"
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Mod_name']?>] ?')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
								</a>

								
 
								</td>

							</tr>
						<?php $i++;}?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
		<div id="mod_update" class="col-lg-12">
			
		</div>
	</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
 <script type="text/javascript">
 	$(document).ready(function() {

 		/* -- 新增 -- */
 		$("#mod_btn").click(function(event) {
 			var data={
				       mod_name: $("#mod_name").val(),
                       mod_code: $("#mod_code").val(),
                       mod_version: $("#mod_version").val()
			         };
			ajax_in('admin.php', data, '新增模組', 'admin.php');
 		});

 		/* -- 修改 -- */
		$("#mod_update").on('click', '#mod_btn_up', function(event) {
			event.preventDefault();
			var data={  
				        Tb_index: $("#Tb_index").val(),
			            mod_name: $("#mod_name_up").val(),
                        mod_code: $("#mod_code_up").val(),
                     mod_version: $("#mod_version_up").val(),
                         is_use : $(":checked#is_use").val()
			         };

            ajax_in('admin.php', data, '更新模組', 'admin.php');
			
		});

		$("#mod_update").on('click', '#mod_close', function(event) {
        	event.preventDefault();
        	$("#mod_update").slideUp('500');
        });
 	});

 	function mod_edit(name, code, version, check, Tb_index) {
 		
 		   var tb_txt='<div class="panel panel-default">';
        tb_txt=tb_txt+'<div  class="panel-body">';
        tb_txt=tb_txt+'<form class="form-horizontal" >';
        tb_txt=tb_txt+'  <div class="form-group">';

        tb_txt=tb_txt+'    <button type="button" id="mod_close" class="close_btn">CLOSE</button>';

        tb_txt=tb_txt+'    <label class="col-md-1 control-label" for="mod_name_up">模組名稱</label>';
        tb_txt=tb_txt+'    <div class="col-md-2">';
        tb_txt=tb_txt+'       <input type="text" class="form-control" id="mod_name_up" value="'+name+'">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" for="mod_code_up">模組代碼</label>';
        tb_txt=tb_txt+'    <div class="col-md-2">';
        tb_txt=tb_txt+'        <input type="text" class="form-control" id="mod_code_up" value="'+code+'">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" for="mod_version_up">版本</label>';
        tb_txt=tb_txt+'    <div class="col-md-2">';
        tb_txt=tb_txt+'        <input type="text" class="form-control" id="mod_version_up" value="'+version+'">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" >狀態</label>';
        tb_txt=tb_txt+'    <div class="col-md-1">';
        tb_txt=tb_txt+'        <input class="checkbox" type="checkbox" id="is_use" '+check+' value="1">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <div class="col-md-1">';
        tb_txt=tb_txt+'        <button type="button" id="mod_btn_up" class="btn btn-info btn-block btn-raised">更新</button>';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'  <input type="hidden" id="Tb_index" value="'+Tb_index+'">';
        tb_txt=tb_txt+'</form>';
        tb_txt=tb_txt+'</div>';
        tb_txt=tb_txt+'</div>';

			$("#mod_update").html(tb_txt);
			$("#mod_update").slideDown('500');
 	}
 </script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>


