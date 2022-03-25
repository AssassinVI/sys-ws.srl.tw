<?php  include("../../core/page/header01.php");//載入頁面heaer01?>
<meta name="google-signin-client_id" content="466826388971-alj2mf5lmgjq4bt0af2rog0pulp81nn1.apps.googleusercontent.com">
<meta name="google-signin-scope" content="https://www.googleapis.com/auth/analytics.readonly">
<style type="text/css">
	.sortable-list{ padding: 0px;  }
	.sk-spinner-rotating-plane.sk-spinner{ display: inline-block; width: 20px; height: 20px; background-color: #008578; }
	.loading{ opacity:0 ; }
	.google_signin{ width: 100%; height: 84vh; position: absolute; background: rgba(255, 255, 255, 0.85); z-index: 1; left: 0px; }
	.google_signin .g-signin2{display: none; width: 120px; height: 40px; margin: auto; top: 0; bottom: 0; left: 0; right: 0; position: absolute; }
	.google_signin .an_loading{ position: absolute; margin: auto; width: 100px; height: 30px; top: 0; bottom: 0; left: 0; right: 0; z-index: 2; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
// if ($_POST) {
//    // -- 更新排序 --
//   for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
//     $data=["OrderBy"=>$_POST['OrderBy'][$i]];
//     $where=["Tb_index"=>$_POST['Tb_index'][$i]];
//     pdo_update('FunBox', $data, $where);
//   }
// }


   // if (!empty($_GET['Tb_index'])) {//刪除

   //  $where=['Tb_index'=>$_GET['Tb_index']];

   // 	 pdo_delete('FunBox', $where);
   // }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT Tb_index, aTitle, google_view_code FROM build_case WHERE google_view_code!='' ORDER BY Tb_index ASC");
   $sql->execute();


?>

<!-- The Sign-in button. This will run `queryReports()` on success. -->
<div class="google_signin">
	 <div class="an_loading">
	 	<div class="sk-spinner sk-spinner-rotating-plane "></div>讀取中...
	 </div>
	 <p class="g-signin2" data-onsuccess="gn_signIn"></p>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">Google分析更新</h2>
		<p>更新後即可看到最新分析資訊，請確認是否有填入"檢視編碼"，否則無法更新</p>
	   <div class="new_div">

        <!-- <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button> -->
        <button id="an_ajax_all" type="button" class="btn btn-default"> <i class="fa fa-plus" aria-hidden="true"></i> 全部更新</button>
        
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>專案ID</th>
								<th>專案名稱</th>
								<th>更新日期</th>
								<th></th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
                             $row_update=pdo_select("SELECT set_time FROM google_analytics WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['Tb_index']]);
							?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['Tb_index'] ?></td>
								<td><?php echo $row['aTitle'] ?></td>
								<td id="time_<?php echo $row['Tb_index'];?>"><?php echo $row_update['set_time']?></td>
								<td id="<?php echo $row['Tb_index'];?>" class="loading"><div class="sk-spinner sk-spinner-rotating-plane "></div> Update...</td>
								

								<td class="text-right">
							 	  <button type="button" onclick="google_an_all('<?php echo $row['google_view_code'];?>','<?php echo $row['Tb_index'];?>')" class="btn btn-info ">更新</button>
								</td>

								<input type="hidden" class="case_id_one" value="<?php echo $row['Tb_index'];?>">
								<input type="hidden" class="view_code_one" value="<?php echo $row['google_view_code'];?>">
							</tr>
						<?php $i++; }?>

						       <input type="hidden" id="timeOut_num" ><!-- 讀取+匯入延遲時間次數 (1次100毫秒) -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- google 分析資料暫存 -->
<div id="an_data">
  <input type="hidden" name="week_user">
  <input type="hidden" name="month_user">
  <input type="hidden" name="total_user">
  <input type="hidden" name="sex">
  <input type="hidden" name="years">
  <input type="hidden" name="media">
  <input type="hidden" name="event">
  <input type="hidden" name="src">
  <input type="hidden" name="month_src">
  <input type="hidden" name="city">
  <input type="hidden" name="timeOnSite_years">
  <input type="hidden" name="user_date">
</div>

</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>

<!-- Load the JavaScript API client and Sign-in library. -->
<script src="https://apis.google.com/js/client:platform.js"></script>


<!-- 撈取分析資料+匯入資料 -->
<!-- 撈取分析資料+匯入資料 -->
<!-- 撈取分析資料+匯入資料 -->
<script type="text/javascript" src="google_an.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('#an_ajax_all').click(function(event) {

			var an_t;
			var x=0.5;//-- 初始延遲 --

			$.each($('.case_id_one'), function() {
				
                an_t=setTimeout(" google_an_all('"+$(this).next().val()+"', '"+$(this).val()+"')",2500*x);

                x++;
				
			});
		});
		
	});

	function test(data1, data2) {
		console.log(data1);
	    console.log(data2);
	}

	$(window).load(function() {
		 $('.an_loading').css('display', 'none');
		 $('.google_signin .g-signin2').css('display', 'block');
	});

	function gn_signIn() {
		$('.google_signin').css('display', 'none');
	}
</script>



<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>