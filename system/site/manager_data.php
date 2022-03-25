<?php include("../../core/page/header01.php");//載入頁面heaer01?>
 <style type="text/css">
 	.footer{ position: relative; }
 </style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>

<?php

  if ($_POST) {

  	if (!empty($_POST['type']) && $_POST['type']=='delete') { //刪除圖片
       $param=array('aPic'=>'');
       $where=array('Tb_index'=>$_POST['Tb_index']);
       pdo_update('maintable', $param, $where);

       unlink('../../img/'.$_POST['aPic']);
       exit();
  	}

  //-------------------------- 新增 ------------------------------
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
      $isTopbar=empty($_POST['isTopbar'])? '0' : '1';

      	 $param=array(
  	 		          'Tb_index'=> 'site'.date('YmdHis').rand(0,99),
  	 		       'UseModuleID'=> $_POST['UseModuleID'],
  	 		              'aPic'=> $aPic,
  	 		           'MT_Name'=> $_POST['MT_Name'],
  	 		             'MT_EX'=> $_POST['MT_EX'],
 	 		         'parent_id'=> $_POST['parent_id'],
  	 		           'is_data'=> 1,
  	 		           'use_web'=> $_POST['use_web'],
  	 		       'outside_web'=> $_POST['outside_web'],
  	 		         'StartDate'=> date('Y-m-d'),
  	 		       'OnLineOrNot'=> $OnLineOrNot,
  	 		          'isTopbar'=>$isTopbar,
  	 		           'weblang'=> $weblang
  	 		         );
      
  	 	pdo_insert('maintable', $param);
        
        //-- 判斷推播 --
        if ($_POST['UseModuleID']=='mod2017011612455213') {
        	$key=pdo_select("SELECT Tb_index FROM appWebPush_key LIMIT 0,1");
        	if (empty($key['Tb_index'])) {

        		$VAPID=new VAPID();
                   $key= $VAPID->createVapidKeys();
                   $param_VAPID=array(
                   	              'Tb_index'=>'wpk'.date('YmdHis').rand(0,99),
                   	              'publicKey'=>$key['publicKey'], 
                   	              'privateKey'=>$key['privateKey'],
                   	           );
                   pdo_insert('appWebPush_key', $param_VAPID);
        	}
        }

  	 	location_up('admin.php','成功新增');
  	 }

  //-------------------------- 修改 ------------------------------ 	 
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

        $param=array(
        	       'UseModuleID'=> $_POST['UseModuleID'],
        	              'aPic'=> $aPic,
  	 		           'MT_Name'=> $_POST['MT_Name'],
  	 		             'MT_EX'=> $_POST['MT_EX'],
  	 		           'use_web'=> $_POST['use_web'],
  	 		       'outside_web'=> $_POST['outside_web'],
  	 		       'OnLineOrNot'=> $OnLineOrNot,
  	 		          'isTopbar'=>$_POST['isTopbar']
        	         );
        $where=array('Tb_index'=> $_POST['Tb_index']);
        pdo_update('maintable', $param, $where);
        location_up('admin.php','成功更新');
  	 }
  }
  elseif ($_GET) {//讀取資料
  	
  	 $where=array('Tb_index'=>$_GET['Tb_index'], 'weblang'=>$weblang);
     $row=pdo_select("SELECT * FROM maintable WHERE Tb_index=:Tb_index AND weblang=:weblang", $where);

     $parent_where=array('parent_id'=>$_GET['parent_id']);
     $parent_row=pdo_select("SELECT MT_Name FROM maintable WHERE Tb_index=:parent_id LIMIT 0,1", $parent_where);

     $select_mod=$row['UseModuleID'];
  }

?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>[ <?php echo $parent_row['MT_Name']?> ] 子單元編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="site_form" class="form-horizontal" action="manager_data.php" method="POST" enctype='multipart/form-data' >
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


						<div class="form-group">
							<label class="col-md-2 control-label" for="UseModuleID">模組</label>
							<div class="col-md-10">
								<select name="UseModuleID" class="form-control m-b" id="UseModuleID">
								 <?php 
								    $pdo=pdo_conn();
                                    $sql_mod=$pdo->prepare("SELECT * FROM sysModule WHERE is_use='1'");
                                    $sql_mod->execute();
                                    while ($row_mod=$sql_mod->fetch(PDO::FETCH_ASSOC)) {

                                       if ($select_mod==$row_mod['Tb_index']) {
                                       	 echo "<option value='".$row_mod['Tb_index']."' selected>".$row_mod['Mod_name']." </option>";
                                       }else{
                                       	 echo "<option value='".$row_mod['Tb_index']."'>".$row_mod['Mod_name']."</option>";
                                       }
                                    	
                                    }
                                    $pdo=NULL;
								 ?>

								</select>
							</div>
						</div>

						<div id="use_web_div" class="form-group">
							<label class="col-md-2 control-label" for="use_web">前台網址</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="use_web" name="use_web" value="<?php echo $row['use_web'];?>">
							</div>
						</div>

						<div id="outside_web_div" class="form-group">
							<label class="col-md-2 control-label" for="outside_web">連外網址</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="outside_web" name="outside_web" value="<?php echo $row['outside_web'];?>">
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
								<input class="checkbox switch switch-primary" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>/>
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
		
    //------------------------------ 送出 ---------------------------------
		$('#site_btn').click(function(event) {
			$("#site_form").submit();
		});
 


    //------------------------------ 刪圖 ---------------------------------
		$("#one_del_img").click(function(event) { 
			if (confirm('是否要刪除圖檔?')) {
			 var data={
			 	        Tb_index: $("#Tb_index").val(),
                            aPic: '<?php echo $_SESSION["img_id"]?>',
                            type: 'delete'
			          };	
               ajax_in('manager_data.php', data, '成功刪除', 'no');
               $("#img_div").css('display', 'none');
			}
		});


        $('#img_fire').on('change', '#aPic', function(event) {
        	event.preventDefault();
        	$("#img_div").css('display', 'block');
        });


        $('#UseModuleID').change(function(event) {
        	if ($(this).val()=='mod2017031514201522') {
        		$('#outside_web_div').css('display', 'inherit');
        		$('#use_web_div').css('display', 'none');
        		$('#use_web_div').val('');
        	}else{
        		$('#outside_web_div').css('display', 'none');
        		$('#use_web_div').css('display', 'inherit');
        		$('#outside_web_div').val('');
        	}
        });

        if ($('#UseModuleID').val()=='mod2017031514201522') {
        	$('#outside_web_div').css('display', 'inherit');
        	$('#use_web_div').css('display', 'none');
        }else{
        	$('#outside_web_div').css('display', 'none');
        	$('#use_web_div').css('display', 'inherit');
        }
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

