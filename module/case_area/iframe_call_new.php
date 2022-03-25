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

    // ======================== 刪除 ===========================
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    
        //----------------------- 多檔刪除 -------------------------------
        $sel_where=['Tb_index'=>$_POST['fun_id']];
        $otr_file=pdo_select('SELECT show_img FROM slideshow_tb WHERE Tb_index=:Tb_index', $sel_where);
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
            pdo_update('slideshow_tb', $param, $where);
      
       exit();
    }
  
  //----------------- 新增 ----------------------------------------------

  if(empty($_GET['fun_id'])){

    $Tb_index='call'.date('YmdHis').rand(0,99);

    $OnLineOrNot=empty($_POST['OnLineOrNot'])? 0:1;

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['fun_id'=>$Tb_index, 'OnLineOrNot'=>$OnLineOrNot], ['Tb_index'=>$_GET['rel_id']]);
    
    $param=[
       'Tb_index'=>$Tb_index,
       'case_id'=>$_GET['Tb_index'],
       'btn_name'=>$_POST['btn_name'],
       're_name'=>implode(',', $_POST['re_name']),
       're_mail'=>implode(',', $_POST['re_mail']),
       'OnLineOrNot'=>$OnLineOrNot
    ];
    pdo_insert('call_us_tb', $param);
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
    pdo_update('call_us_tb', $param, ['Tb_index'=>$Tb_index]);

    //---- 更新關聯資料表 -----
    pdo_update('Related_tb', ['OnLineOrNot'=>$OnLineOrNot], ['fun_id'=>$Tb_index]);
    location_up('iframe_call.php?Tb_index='.$_GET['Tb_index'].'&fun_id='.$Tb_index, '功能已更新');
  }
  
}//-- POST END --


  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM call_us_tb WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['fun_id']]);

  $re_name=explode(',', $row['re_name']);
  $re_mail=explode(',', $row['re_mail']);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-聯絡我們</h2>
      
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

          <!-- 欄位選擇 -->
          <div class="form-group">
            <label class="col-lg-1 control-label">欄位選擇</label>
            <div class="col-lg-3">
                <input type="text" class="form-control" name="re_name[]" value="<?php echo $re_name[$i];?>">
            </div>

            <label class="col-lg-1 control-label">欄位名稱</label>
            <div class="col-lg-3">
                <input type="text" class="form-control" name="re_name[]" value="姓名">
            </div>
          </div>
				   
          <?php 
           for ($i=0; $i <8 ; $i++) { 
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

         var err_txt='';
          err_txt = err_txt + check_input( '#fun_form [name="re_name[]"]:nth-child(1)', '姓名，' );
          err_txt = err_txt + check_input( '#fun_form [name="re_mail[]"]:nth-child(1)', '手機，' );

          if(err_txt!=''){
            alert("請輸入"+err_txt+"!!");
          }
          else{
             $('#fun_form').submit();
          }


      });

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
