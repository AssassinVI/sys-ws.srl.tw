<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
    

  // ---------------- 新增 -------------------
	if (empty($_POST['Tb_index'])) {
		$Tb_index='fbt'.date('YmdHis').rand(0,99);
        $expansion=empty($_POST['expansion'])? 0:1;
        $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;

	$param=  [        
		              'Tb_index'=>$Tb_index,
			          'box_name'=>$_POST['box_name'],
			          'aUrl'=>$_POST['aUrl'],
			          'btn_type'=>$_POST['btn_type'],
			          'btn_icon'=>$_POST['btn_icon'],
			          'fun_tb'=>$_POST['fun_tb'],
			          'expansion'=>$expansion,
			          'OnLineOrNot'=>$OnLineOrNot,
			          'StartDate'=>date('Y-m-d')
			         ];
	pdo_insert('FunBox', $param);
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功新增');
   }

  // -------------------- 修改 ---------------------
   else{  

   	$Tb_index =$_POST['Tb_index'];
   	$expansion=empty($_POST['expansion'])? 0:1;
   	$OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
    
    $param=  [     
			          'box_name'=>$_POST['box_name'],
			          'aUrl'=>$_POST['aUrl'],
			          'btn_type'=>$_POST['btn_type'],
			          'btn_icon'=>$_POST['btn_icon'],
			          'fun_tb'=>$_POST['fun_tb'],
			          'expansion'=>$expansion,
			          'OnLineOrNot'=>$OnLineOrNot
			         ];
    $where= ['Tb_index'=>$Tb_index] ;
	pdo_update('FunBox', $param, $where);
	
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
   }
}
if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM FunBox WHERE Tb_index=:Tb_index', $where);
}
	$btn_type=empty($row['btn_type'])? 'default-element' : $row['btn_type'];
    $btn_icon=empty($row['btn_icon'])? 'fa-car' : $row['btn_icon'];
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>網頁資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="box_name">功能名稱</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="box_name" name="box_name" value="<?php echo $row['box_name'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label" for="aUrl">功能連結</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aUrl" name="aUrl" value="<?php echo $row['aUrl'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="fun_tb">功能資料表</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="fun_tb" name="fun_tb" value="<?php echo $row['fun_tb'];?>">
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="btn_type">功能樣式</label>
							<div class="col-md-4">
								<select name="btn_type" class="form-control">
                                        <option <?php echo $selected=$row['btn_type']=='default-element'? 'selected':'';?> value="default-element">預設白</option>
                                        <option <?php echo $selected=$row['btn_type']=='success-element'? 'selected':'';?> value="success-element">通過綠</option>
                                        <option <?php echo $selected=$row['btn_type']=='info-element'? 'selected':'';?> value="info-element">訊息藍</option>
                                        <option <?php echo $selected=$row['btn_type']=='warning-element'? 'selected':'';?> value="warning-element">警告黃</option>
                                        <option <?php echo $selected=$row['btn_type']=='danger-element'? 'selected':'';?> value="danger-element">危險紅</option>
                                </select>
							</div>
							<label class="col-md-2 control-label" for="btn_icon">功能圖示</label>
							<div class="col-md-3">
								<input type="text" class="form-control" name="btn_icon" value="<?php echo $row['btn_icon'];?>">
							</div>
							<div class="col-md-1">
								<a href="icon.php" id="show-icon" data-fancybox-type="iframe" class="btn btn-info iframe_box fancybox">圖示</a>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="expansion">展示</label>
							<div class="col-md-4">
								<!-- <button type="button" id="show_btn" class="btn btn-w-m dim <?php //echo $row['btn_type'];?>"><i class="fa <?php //echo $row['btn_icon'];?>"></i> 展示</button> -->
								<ul class="sortable-list connectList agile-list ui-sortable">
									<li id="show_btn" class="<?php echo $btn_type;?>"><i class="fa <?php echo $btn_icon;?>"></i> 展示</li>
								</ul>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="expansion">擴充功能</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="expansion" name="expansion" type="checkbox" value="1" <?php echo $check=!isset($row['expansion']) || $row['expansion']==1 ? 'checked' : ''; ?>  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
							</div>
						</div>

						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
						<input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
					</form>
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->




		</div>

		<div class="col-lg-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>儲存您的資料</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<button type="button" class="btn btn-danger btn-block btn-flat" data-toggle="modal" data-target="#settingsModal1" onclick="clean_all()">重設表單</button>
						</div>
						<div class="col-lg-6">
						<?php if(empty($_GET['Tb_index'])){?>
							<button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">儲存</button>
						<?php }else{?>
						    <button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">更新</button>
						<?php }?>
						</div>
					</div>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {
          $("#submit_btn").click(function(event) {
          	 $('#put_form').submit();
          });


          $('[name="btn_type"]').change(function(event) {
          	 $('#show_btn').attr('class', $(this).val());
          });

          $('[name="btn_icon"]').change(function(event) {
          	$('#show_btn i').attr('class', 'fa '+$(this).val());
          });

          
       
      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

