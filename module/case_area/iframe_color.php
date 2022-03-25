<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
  .color_bar{ padding: 15px 25px; display: inline-block; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT * FROM color WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-更改顏色</h2>
        <p>可改主標、副標、內文、背景</p>
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
				<form action="#" method="POST" class="form-horizontal">
				   <div class="form-group">
              <label class="col-sm-2 control-label" for="h1_color">主標顏色</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="h1_color" name="h1_color" value="<?php echo $row['h1_color'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['h1_color'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="h2_color">副標顏色</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="h2_color" name="h2_color" value="<?php echo $row['h2_color'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['h2_color'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="p_color">內文顏色</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="p_color" name="p_color" value="<?php echo $row['p_color'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['p_color'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="back_color">背景顏色</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="back_color" name="back_color" value="<?php echo $row['back_color'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['back_color'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="marquee">跑馬燈文字</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="marquee" name="marquee" value="<?php echo $row['marquee'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['marquee'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="top_txt">錨點文字</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="top_txt" name="top_txt" value="<?php echo $row['top_txt'];?>">
              </div>
              <div class="col-sm-4">
                <span class="color_bar" style="background: <?php echo $row['top_txt'];?>"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="top_bar">導航欄底色</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="top_bar" name="top_bar" value="<?php echo $row['top_bar'];?>">
              </div>
              <div class="col-sm-4">
                <span  class="color_bar" style="background: <?php echo $row['top_bar'];?>"></span>
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
        
      $('input').change(function(event) {
        $(this).parent().next().find('.color_bar').css('background', $(this).val());
      });
      
      $('#save_btn').click(function(event) {
        
        $.ajax({
          url: 'iframe_ajax.php',
          type: 'POST',
          data: {
            type: 'color',
            h1_color:$('#h1_color').val(),
            h2_color:$('#h2_color').val(),
            p_color:$('#p_color').val(),
            back_color:$('#back_color').val(),
            marquee:$('#marquee').val(),
            top_txt:$('#top_txt').val(),
            top_bar:$('#top_bar').val(),
            Tb_index : '<?php echo $_GET['Tb_index']?>'
          },
          success:function (data) {
            alert('儲存完畢!');
          }
        });
        
      });

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
