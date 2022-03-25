<?php  include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.is_deal{ color: green; }
	.no_deal{ color: red; }
</style>
<?php  include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
  
}

if ($_GET) {

   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT outside_web FROM maintable WHERE Tb_index=:Tb_index");
   $sql->execute(array('Tb_index'=>$_GET['MT_id']));
   $row=$sql->fetch(PDO::FETCH_ASSOC);
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?></h2>
		
	   <div class="new_div">
	  </div>


	</div>
	<div class="row">
    <div class="col-lg-6">
    	<div class="panel panel-primary">
		<div class="panel-heading">連外網址</div>
		<div class="panel-body">
			<p>
				此頁面為連外網頁<br>
				網址：<a target="_blank" href="<?php echo $row['outside_web'];?>"><?php echo $row['outside_web'];?></a>
			</p>
		</div>
	</div>
    </div>
	
		
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {

	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
