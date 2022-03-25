<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
?>
<?php
 if ($_POST) { //新增、修改
  	
  	if (empty($_POST['Tb_index'])) {

            $param=array('Tb_index'=>'lang'.date('YmdHis').rand(0,99), 
            	        'Lang_name'=>$_POST['Lang_name'], 
            	        'Lang_code'=>$_POST['Lang_code'], 
            	           'is_use'=>'1');

            pdo_insert('sysLang', $param);
           exit();
  	}
  	else{   
             
           $param=array('Lang_name'=>$_POST['Lang_name'], 
            	        'Lang_code'=>$_POST['Lang_code'], 
            	           'is_use'=>$_POST['is_use']);

           $where=array('Tb_index'=>$_POST['Tb_index']);

            pdo_update('sysLang', $param, $where);
            exit();
  	}
  }
  else{

  	if ($_GET) {
  		
  	  $where=array('Tb_index'=>$_GET['Tb_index']);
      pdo_delete('sysLang', $where);
  	}

     $pdo=pdo_conn();
     $sql=$pdo->prepare("SELECT * FROM sysLang");
     $sql->execute();

  } 
  

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">語系列表</h2>
		<p>本頁面條列目前網站所使用的語系，請勿任意刪除，感恩</p>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form class="form-horizontal" >
						<div class="form-group">
							<label class="col-md-1 control-label" for="Lang_name">語系名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="Lang_name" value="">
							</div>
							<label class="col-md-1 control-label" for="Lang_code">代碼</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="Lang_code" value="">
							</div>	
							<div class="col-md-2">
								<button type="button" id="lang_btn" class="btn btn-info btn-block btn-raised">儲存</button>
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
					<table id="lang_tb" class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>語系名稱</th>
								<th>語系代碼</th>
								<th>狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)){ ?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['Lang_name']?></td>
								<td><?php echo $row['Lang_code']?></td>
								<td><?php echo $check=$row['is_use']=='0' ? '未上線' : '上線中';?></td>

								<td class="text-right">

								<a href="#" >
								<button type="button" onclick="lang_edit('<?php echo $row['Lang_name']?>', '<?php echo $row['Lang_code']?>', '<?php echo $check?>', '<?php echo $row['Tb_index']?>')" class="lang_edit btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Lang_name']?>] ?')) {return false;}">
								<button type="button" class=" btn btn-rounded btn-warning btn-sm">
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

<div class="row">
		<div id="lang_update" class="col-lg-12">
			
		</div>
	</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
 <script type="text/javascript">
 		$(document).ready(function() {


       /* -- 新增 -- */
		$("#lang_btn").click(function(event) {
			var data={
				       Lang_name: $("#Lang_name").val(),
                       Lang_code: $("#Lang_code").val()
			         };
			ajax_in('admin.php', data, '新增語系', 'admin.php');
		});
       
       /* -- 修改 -- */
		$("#lang_update").on('click', '#lang_btn_up', function(event) {
			event.preventDefault();
			var data={  
				        Tb_index: $("#Tb_index").val(),
			            Lang_name: $("#Lang_name_up").val(),
                        Lang_code: $("#Lang_code_up").val(),
                        is_use : $(":checked#is_use").val()
			         };

            ajax_in('admin.php', data, '更新語系', 'admin.php');
			
		});


        $("#lang_update").on('click', '#lang_close', function(event) {
        	event.preventDefault();
        	$("#lang_update").slideUp('500');
        });

	});//JQUERY END

 	function lang_edit(name, code, check, Tb_index) {
 		
 		           var tb_txt='<div class="panel panel-default">';
        tb_txt=tb_txt+'<div  class="panel-body">';
        tb_txt=tb_txt+'<form class="form-horizontal" >';

        tb_txt=tb_txt+'        <button type="button" id="lang_close" class="close_btn">CLOSE</button>';

        tb_txt=tb_txt+'  <div class="form-group">';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" for="Lang_name_up">語系名稱</label>';
        tb_txt=tb_txt+'    <div class="col-md-3">';
        tb_txt=tb_txt+'       <input type="text" class="form-control" id="Lang_name_up" value="'+name+'">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" for="Lang_code_up">代碼</label>';
        tb_txt=tb_txt+'    <div class="col-md-3">';
        tb_txt=tb_txt+'        <input type="text" class="form-control" id="Lang_code_up" value="'+code+'">';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <label class="col-md-1 control-label" >狀態</label>';
        tb_txt=tb_txt+'    <div class="col-md-1">';
        if (check=='未上線') { 
          tb_txt=tb_txt+'        <input type="checkbox" id="is_use" value="1">';
        }else{
          tb_txt=tb_txt+'        <input type="checkbox" id="is_use" checked value="1">';
        }
        
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'    <div class="col-md-2">';
        tb_txt=tb_txt+'        <button type="button" id="lang_btn_up" class="btn btn-info btn-block btn-raised">更新</button>';
        tb_txt=tb_txt+'    </div>';
        tb_txt=tb_txt+'  <input type="hidden" id="Tb_index" value="'+Tb_index+'">';
        tb_txt=tb_txt+'</form>';
        tb_txt=tb_txt+'</div>';
        tb_txt=tb_txt+'</div>';

			$("#lang_update").html(tb_txt);
			$("#lang_update").slideDown('500');
 	}
 </script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>


