<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
require_once '../../core/inc/function.php';
?>

<?php
  if ($_POST) {

  	if (!empty($_POST['type']) && $_POST['type']=='delete') { //刪除圖片
       unlink('../../img/'.$_POST['aPic']);
       $param=array( 'aPic'=> '' );
       $where=array('Tb_index'=> $_POST['Tb_index']);
        pdo_update('maintable', $param, $where);
       exit();
  	}


   //---------------------- 新增 -------------------------
  	 if (empty($_POST['Tb_index'])) { 

      if (!empty($_FILES['aPic']['name'])) {
      	$type=explode('.', $_FILES['aPic']['name']);
      	$aPic='site'.date('YmdHis').rand(0,99).'.'.$type[1];
		if(test_img($aPic)){
			move_uploaded_file($_FILES['aPic']['tmp_name'], '../../img/'.$aPic);
		}
		else{
			echo '這不是圖檔';
		    exit();
		}
      }
      else{
        $aPic='';
      }

      $OnLineOrNot=empty($_POST['OnLineOrNot'])? '': $_POST['OnLineOrNot'];
      $isTopbar=empty($_POST['isTopbar'])? '': $_POST['isTopbar'];
       
       	 $param=array(
  	 		          'Tb_index'=> 'site'.date('YmdHis').rand(0,99),
  	 		              'aPic'=> $aPic,
  	 		           'MT_Name'=> $_POST['MT_Name'],
  	 		             'MT_EX'=> $_POST['MT_EX'],
  	 		         'parent_id'=> $_POST['parent_id'],
  	 		           'is_data'=> 0,
  	 		          'isTopbar'=> $isTopbar,
  	 		         'StartDate'=> date('Y-m-d'),
  	 		           'use_web'=> $_POST['use_web'],
  	 		       'OnLineOrNot'=> $OnLineOrNot,
  	 		           'weblang'=> $weblang
  	 		         );
 
  	 	pdo_insert('maintable', $param);
  	 	location_up('admin.php','成功新增');
  	 }

  	//--------------------------- 修改 --------------------------- 
  	 else{ 
        
        $where=array('Tb_index'=> $_POST['Tb_index']);
        $is_pic=pdo_select("SELECT aPic FROM maintable WHERE Tb_index=:Tb_index", $where);

     if (empty($_FILES['aPic']['name'])) {
        $aPic=empty($is_pic['aPic'])? '' : $is_pic['aPic'];
     }
     else{
        unlink('../../img/'.$is_pic['aPic']);
        $type=explode('.', $_FILES['aPic']['name']);
      	$aPic='site'.date('YmdHis').rand(0,99).'.'.$type[1];
		if(test_img($aPic)){
			move_uploaded_file($_FILES['aPic']['tmp_name'], '../../img/'.$aPic);
		}
		else{
			echo '這不是圖檔';
		    exit();
		}
     }
  	  
  	  $OnLineOrNot=empty($_POST['OnLineOrNot'])? '': $_POST['OnLineOrNot'];
  	  $isTopbar=empty($_POST['isTopbar'])? '': $_POST['isTopbar'];
  	  
        $param=array(
        	              'aPic'=> $aPic,
  	 		           'MT_Name'=> $_POST['MT_Name'],
  	 		             'MT_EX'=> $_POST['MT_EX'],
  	 		           'use_web'=> $_POST['use_web'],
  	 		          'isTopbar'=> $isTopbar,
  	 		       'OnLineOrNot'=> $OnLineOrNot
        	         );
        pdo_update('maintable', $param, $where);
        location_up('admin.php','成功更新');
  	 }
  }
  elseif ($_GET) {//讀取資料
    
    $where=array('Tb_index'=>$_GET['Tb_index'], 'weblang'=>$weblang);
    $row=pdo_select("SELECT * FROM maintable WHERE Tb_index=:Tb_index AND weblang=:weblang", $where);

    $parent_where=array('parent_id'=>$_GET['parent_id']);
    $parent_row=pdo_select("SELECT MT_Name FROM maintable WHERE Tb_index=:parent_id LIMIT 0,1", $parent_where);
  }

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>[ <?php echo $parent_row['MT_Name']?> ] 子分類編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="site_form" class="form-horizontal" action="manager.php" method="POST" enctype='multipart/form-data'>
						<div class="form-group">
							<label class="col-md-2 control-label" for="MT_Name">單元名稱</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="MT_Name" name="MT_Name" value="<?php echo $row['MT_Name'];?>">
							</div>
						</div>
						<div class="form-group" id="img_fire">
							<label class="col-md-2 control-label" for="aPic">代表圖檔</label>
							<div class="col-md-10">
								<input type="file" name="aPic" class="form-control" id="aPic" onchange="file_viewer_load_new(this, '#img_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <!-- 舊圖檔 -->
						   <div class="col-md-4">
								<div id="img_box"  >

								</div>
							</div>

							<?php if(!empty($row['aPic'])){?>
							<div  class="col-md-4">
							   <div id="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" id="one_del_img"> X </button>
								  <span class="img_check"><i class="fa fa-check"></i></span>
								  <img id="one_img" src="../../img/<?php echo $row['aPic'];?>" alt="請上傳代表圖檔">
								</div>
							</div>
						<?php }?>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="MT_EX">摘要內容</label>
							<div class="col-md-10">
								<textarea class="form-control" id="MT_EX" name="MT_EX" placeholder="摘要內容"><?php echo $row['MT_EX'];?></textarea>
							</div>
						</div>
						<div  class="form-group">
							<label class="col-md-2 control-label" for="use_web">前台網址</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="use_web" name="use_web" value="<?php echo $row['use_web'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="isTopbar">是否顯示在巡覽列</label>
							<div class="col-md-10">
								<input class="checkbox switch switch-primary" id="isTopbar" name="isTopbar" type="checkbox" value="1" <?php echo $check=!isset($row['isTopbar']) || $row['isTopbar']==1 ? 'checked' : ''; ?>/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								<input class="checkbox switch switch-primary" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?> value="1" />
							</div>
						</div>

						<input type="hidden" id="parent_id" name="parent_id" value="<?php echo $_GET['parent_id'];?>"><!-- 父資料ID -->
						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
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

						  <?php  if (empty($_GET['Tb_index'])) {?>
							<button type="button" id="site_btn" class="btn btn-info btn-block btn-raised">儲存</button>
						  <?php }else{?>
						    <button type="button" id="site_btn" class="btn btn-info btn-block btn-raised">更新</button>
						  <?php }?>
						</div>
					</div>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#site_btn').click(function(event) {
			$("#site_form").submit();
		});


        $('#img_fire').on('change', '#aPic', function(event) {
        	event.preventDefault();
        	$("#img_div").css('display', 'block');
        });

		$("#one_del_img").click(function(event) {
			if (confirm('是否要刪除圖檔?')) {
			 var data={
			 	    Tb_index: $("#Tb_index").val(),
                        aPic: '<?php echo $row["aPic"]?>',
                        type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $("#img_div").css('display', 'none');
			}
		});

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

