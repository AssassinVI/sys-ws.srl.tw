<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<link rel="stylesheet" type="text/css" href="../../css/codemirror.css">
<link rel="stylesheet" type="text/css" href="../../js/plugins/codemirror/theme/monokai.css">
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side, body.canvas-menu .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }
    .CodeMirror{ height: 70vh; }
	
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);

  $row=pdo_select("SELECT css FROM change_css WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-自訂CSS</h2>
        <p>直接寫入CSS代碼即可</p>
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
				
				  <textarea id="edit_css" name="css" class="form-control"  ><?php echo $row['css'];?></textarea>
				
			</div>
		</div>
	</div>
</div>


</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript" src="../../js/plugins/codemirror/codemirror.js"></script>
<script type="text/javascript" src="../../js/plugins/codemirror/mode/css/css.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        
       var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('edit_css'), {
          mode:"text/css",
          lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true,
                 theme: 'monokai'
    });

       $('#save_btn').click(function(event) {
       	 $.ajax({
       	 	url: 'iframe_ajax.php',
       	 	type: 'POST',
       	 	data: { 
            type:'css',
       	 		Tb_index : '<?php echo $_GET['Tb_index']?>',
       	 		css : myCodeMirror.getValue()
       	 	},
       	 	success:function (data) {
       	 		alert('儲存完畢!');
       	 	}
       	 });
       	 
       });
	});



</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
