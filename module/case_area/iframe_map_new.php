<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 


if($_POST){


  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='gm'.date('YmdHis').rand(0,99);
   
    
    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);

    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'aTitle'=>$_POST['aTitle'],
       'location'=>$_POST['location'],
       //'url'=>$_POST['url'],
       'embed_url'=>$_POST['embed_url'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('googlemap_tb', $param);
    location_up('iframe_map_new.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    
      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
      $param=[
       'aTitle'=>$_POST['aTitle'],
       'location'=>$_POST['location'],
       //'url'=>$_POST['url'],
       'embed_url'=>$_POST['embed_url'],
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('googlemap_tb', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);

    location_up('iframe_map_new.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM googlemap_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-Google Map</h2>
      
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 		<button id="save_btn" type="button" class="btn btn-primary">儲存</button>      
			 	</div>
			 </div>
			<div class="ibox-content">
				<form id="fun_form" action="#" method="POST" class="form-horizontal" enctype='multipart/form-data'>
				   <div class="form-group">
              <label class="col-sm-2 control-label" for="aTitle">主標</label>
              <div class="col-sm-10">
                <textarea name="aTitle" class="form-control"><?php echo $row['aTitle'];?></textarea>
              </div>
              
            </div>

            <!-- <div class="form-group">
              <label class="col-sm-2 control-label" for="url">地圖連結</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="url" name="url" value="<?php echo $row['url'];?>">
              </div>
            </div> -->

            <div class="form-group">
              <label class="col-sm-2 control-label" for="location">地圖連結</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $row['location'];?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="embed_url">嵌入連結</label>
              <div class="col-sm-10">
                <textarea name="embed_url" style="height: 200px;" class="form-control"><?php echo $row['embed_url'];?></textarea>
                <a href="#" onclick="window.open('https://www.google.com.tw/maps?hl=zh-TW', 'Google Map', config='height=800,width=800');">google Map 連結</a>
              </div>
            </div>
           


            <div class="form-group">
              <label class="col-sm-2 control-label" for="OnLineOrNot">是否上線</label>
              <div class="col-sm-10">
                <input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
              </div>
            </div>
            
         
				</form>
			</div>
		</div>
	</div>
</div>





</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
        

      $('#save_btn').click(function(event) {
         $('#fun_form').submit();
      });

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
