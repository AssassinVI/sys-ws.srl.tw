<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

  $test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --

if($_POST){

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    
        //----------------------- 多檔刪除 -------------------------------
        $sel_where=['Tb_index'=>$_POST['fun_id']];
        $otr_file=pdo_select('SELECT show_img FROM slideshow_tb WHERE Tb_index=:Tb_index', $sel_where, $test_case_db);
        $otr_file=explode(',', $otr_file['show_img']);
        for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
           if ($otr_file[$i]!=$_POST['show_img']) {
            $new_file.=$otr_file[$i].',';
           }else{
             unlink('../../../product_html/'.$_POST['case_id'].'/img/'.$_POST['show_img']);
           }
        }
        $param=['show_img'=>$new_file];
            $where=['Tb_index'=>$_POST['fun_id']];
            pdo_update('slideshow_tb', $param, $where, $test_case_db);
      
       exit();
    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='call'.date('YmdHis').rand(0,99);

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']], $test_case_db);
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'btn_name'=>$_POST['btn_name'],
       're_name'=>implode(',', $_POST['re_name']),
       're_mail'=>implode(',', $_POST['re_mail']),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('call_us_tb', $param, $test_case_db);
    location_up('iframe_call.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已成功新增');
  }

  //----------------- 修改 ----------------------------------------------

  else{

    $Tb_index=$_GET['fun_id'];
    

      $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;
      $param=[
       'btn_name'=>$_POST['btn_name'],
       're_name'=>implode(',', $_POST['re_name']),
       're_mail'=>implode(',', $_POST['re_mail']),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_update('call_us_tb', $param, ['Tb_index'=>$Tb_index], $test_case_db);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index], $test_case_db);
    location_up('iframe_call.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], $test_case_db);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM call_us_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']], $test_case_db);

  $re_name=explode(',', $row['re_name']);
  $re_mail=explode(',', $row['re_mail']);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-聯絡我們 <span class="text-danger">(此為測試修改區)</span></h2>
      
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
              <label class="col-sm-2 control-label">按鈕名稱</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="btn_name" value="<?php echo $row['btn_name'];?>">
              </div>
              
            </div>
				   
          <?php 
           for ($i=0; $i <5 ; $i++) { 
          ?>

            <div class="form-group">
              <label class="col-sm-2 control-label">收件人</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="re_name[]" value="<?php echo $re_name[$i];?>">
              </div>

              <label class="col-sm-2 control-label">收件人E-mail</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="re_mail[]" value="<?php echo $re_mail[$i];?>">
              </div>
            </div>

          <?php
           }
          ?>
           

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

      //------------------------------ 刪圖檔 ---------------------------------
          $(".one_del_file").click(function(event) { 
      if (confirm('是否要刪除檔案?')) {
       var data={
                      case_id:'<?php echo $_GET['Tb_index'];?>',
                      fun_id: '<?php echo $_GET['fun_id'];?>',
                       show_img: $(this).next().next().val(),
                            type: 'delete'
                };  
               ajax_in('iframe_show.php', data, '成功刪除', 'no');
               $(this).parent().html('');
      }
    });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
