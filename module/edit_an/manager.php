<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style>
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }
	.d-none{display:none;}
	.alert-info{margin:0 !important; position: relative;}
	.alert-info a{  position: absolute; top: -5px; right: -5px; z-index: 1;}
	option:disabled{color:#ccc;}
	[name="one_event[]"], [name="one_src[]"]{width: 80px;}
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if ($_GET) {

	$case=$new_pdo->select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['case_id']], 'one');
  
	$row=$new_pdo->select("SELECT one_user FROM an_user WHERE date=:date AND case_id=:case_id", ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']], 'one');

	//-- 性別 --
	$sex_arr=[];
	$sex=$new_pdo->select("SELECT one_sex, sex_type FROM an_sex WHERE date=:date AND case_id=:case_id", ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
	foreach ($sex as $one) {
	   $sex_arr[$one['sex_type']]= $one['one_sex'];
	}

	//-- 年齡 --
	$years_arr=[];
	$years=$new_pdo->select("SELECT one_years, years_type FROM an_years WHERE date=:date AND case_id=:case_id", ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
	foreach ($years as $one) {
	   $years_arr[$one['years_type']]= $one['one_years'];
	}

	//-- 媒體 --
	$media_arr=[];
	$media=$new_pdo->select("SELECT one_media, media_type FROM an_media WHERE date=:date AND case_id=:case_id", ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
	foreach ($media as $one) {
	   $media_arr[$one['media_type']]= $one['one_media'];
	}
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>分析資料編輯 - <?php echo $case['aTitle'].' '.date("Y年m月d日", strtotime($_GET['date']));?></header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager_ajax.php" method="POST"  class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label">每日使用人數：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_user" name="one_user" value="<?php echo $row['one_user'];?>">
							</div>
							<label class="col-sm-2 control-label">性別男：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_sex_male" name="one_sex_male" value="<?php echo $sex_arr['male'];?>">
							</div>
							<label class="col-sm-2 control-label">性別女：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_sex_female" name="one_sex_female" value="<?php echo $sex_arr['female'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">25-34歲：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_years_25" name="one_years_25" value="<?php echo $years_arr['25-34'];?>">
							</div>
							<label class="col-sm-2 control-label">35-44歲：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_years_35" name="one_years_35" value="<?php echo $years_arr['35-44'];?>">
							</div>
							<label class="col-sm-2 control-label">45-54歲：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_years_45" name="one_years_45" value="<?php echo $years_arr['45-54'];?>">
							</div>
							<label class="col-sm-2 control-label">55-64歲：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_years_55" name="one_years_55" value="<?php echo $years_arr['55-64'];?>">
							</div>
							<label class="col-sm-2 control-label">65+歲：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_years_65" name="one_years_65" value="<?php echo $years_arr['65+'];?>">
							</div>
						</div>
						

						<div class="form-group">
							<label class="col-sm-2 control-label" for="aTarget">地區：</label>
							<div class="col-sm-2">
								<button id="add_area_btn" type="button" class="btn btn-success">新增地區</button>
							</div>
						</div>
						<div class="form-group">
						   <label class="col-sm-2 control-label"></label>
						   <div id="area_box" class="col-sm-10">
							 <?php
							    $city=$new_pdo->select("SELECT ct.one_city, ct.city_type, tw.tw_name
														FROM an_city as ct
														inner JOIN taiwan_area as tw ON tw.en_name=REPLACE(ct.city_type, ' City', '')
														WHERE ct.date=:date AND ct.case_id=:case_id
														ORDER BY ct.one_city DESC",
													  ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
								foreach ($city as $one) {

									echo '<div class="alert alert-info  d-inline-block">
												<a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
												<div class="twzipcode">
													<div data-role="county" data-name="city_type[]" data-value="'.$one['tw_name'].'"></div>
												</div>
												<div class="d-inline-block">
													<input type="text" class="form-control" name="one_city[]" value="'.$one['one_city'].'">
												</div>
											</div>';
								}
							 ?>
						   </div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">桌機：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_media_desktop" name="one_media_desktop" value="<?php echo $media_arr['desktop'];?>">
							</div>
							<label class="col-sm-2 control-label">手機：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_media_mobile" name="one_media_mobile" value="<?php echo $media_arr['mobile'];?>">
							</div>
							<label class="col-sm-2 control-label">平板：</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="one_media_tablet" name="one_media_tablet" value="<?php echo $media_arr['tablet'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="aTarget">使用的功能：</label>
							<div class="col-sm-2">
								<button id="add_event_btn" type="button" class="btn btn-success">新增使用功能</button>
							</div>
						</div>
						<div class="form-group">
						   <label class="col-sm-2 control-label"></label>
						   <div id="event_box" class="col-sm-10">
							 <?php
							    $event_type_arr=['食醫住行','預約賞屋','撥打手機','google 導航','加LINE或Line分享','fb分享','連結分享', 'QR code 分享'];
							    $event=$new_pdo->select("SELECT one_event, event_type
														 FROM an_event
														 WHERE date=:date AND case_id=:case_id
														 ORDER BY one_event DESC",
													  ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
								foreach ($event as $one) {

									$event_type_txt='';
									foreach ($event_type_arr as $one_type) {
										$selected=$one_type==$one['event_type'] ? 'selected':'';
										$event_type_txt.='<option '.$selected.' value="'.$one_type.'">'.$one_type.'</option>';
									}

									echo '<div class="alert alert-info  d-inline-block">
											    <a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
												<div class="d-inline-block">
													<select name="event_type[]" class="form-control">
														'.$event_type_txt.'
													</select>
												</div>
												<div class="d-inline-block">
													<input type="text" class="form-control" name="one_event[]" value="'.$one['one_event'].'">
												</div>
											</div>';
								}
							 ?>
						   </div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="aTarget">流量來源：</label>
							<div class="col-sm-2">
								<button id="add_src_btn" type="button" class="btn btn-success">新增流量來源</button>
							</div>
						</div>
						<div class="form-group">
						   <label class="col-sm-2 control-label"></label>
						   <div id="src_box" class="col-sm-10">
							 <?php
							 	$src_type_arr=[];
							    $src_type=$new_pdo->select("SELECT media, source FROM QRcode_tb WHERE case_id=:case_id AND OnlineOrNot=1",
															['case_id'=>$_GET['case_id']]);
								foreach ($src_type as $type_one) {
								  array_push($src_type_arr, $type_one['source'].' / '.$type_one['media']);
								}			
											
							    $src=$new_pdo->select("SELECT one_src, src_type
														 FROM an_src
														 WHERE date=:date AND case_id=:case_id
														 ORDER BY one_src DESC
														 LIMIT 0,10",
													  ['date'=>$_GET['date'], 'case_id'=>$_GET['case_id']]);
								foreach ($src as $one) {

									$src_type_txt='';
									foreach ($src_type_arr as $one_type) {
										$selected=$one_type==$one['src_type'] ? 'selected':'';
										$src_type_txt.='<option '.$selected.' value="'.$one_type.'">'.$one_type.'</option>';
									}

									echo '<div class="alert alert-info  d-inline-block">
											    <a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
												<div class="d-inline-block">
													<input type="text" class="form-control" name="src_type[]" value="'.$one['src_type'].'">
												</div>
												<div class="d-inline-block">
													<input type="text" class="form-control" name="one_src[]" value="'.$one['one_src'].'">
												</div>
											</div>';
								}
							 ?>
						   </div>
						</div>



						<input type="hidden" id="case_id" name="case_id" value="<?php echo $_GET['case_id'];?>">
						<input type="hidden" name="date" value="<?php echo $_GET['date'];?>">
						<input type="hidden" name="type" value="update_an">
						<input type="hidden" name="tk" value="<?php echo $_SESSION['token'];?>">
						
					</form>
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->




		</div>

		<div class="col-lg-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>儲存您的資料</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body text-center">
					<button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#settingsModal1" onclick="clean_all()">重設表單</button>
					<?php if(empty($_GET['Tb_index'])){?>
						<button type="button" id="submit_btn" class="btn btn-info  btn-raised">儲存</button>
					<?php }else{?>
						<button type="button" id="submit_btn" class="btn btn-info  btn-raised">更新</button>
					<?php }?>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

		select_one_ch('[name="city_type[]"]');
		select_one_ch('[name="event_type[]"]');

		// ################################################### 地區 ##############################################################
		let tw_option={
			'county':{
			    'name':'city_type[]',
				'value':''
			},
			'district':{
				'name':'district[]',
				'css':'d-none'
			}
		};
		let twzipcode = new TWzipcode('.twzipcode',tw_option);

		//-- 地區 (只能選擇一次) --
		$('#area_box').on('click', '[name="city_type[]"]', function () {
			select_one_ch('[name="city_type[]"]');
		});

		//-- 地區 --
		$('#add_area_btn').click(function (e) { 
			let twzipcode_num=$('.twzipcode').length+1;
			let area_txt=`<div class="alert alert-info  d-inline-block">
							<a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
							<div class="twzipcode" index="${twzipcode_num}">
								<div data-role="county" data-name="city_type[]" data-value=""></div>
							</div>
							<div class="d-inline-block">
								<input type="text" class="form-control" name="one_city[]" value="">
							</div>
                           </div>`;
			
			$('#area_box').append(area_txt);
			twzipcode= new TWzipcode(`.twzipcode[index="${twzipcode_num}"]`, tw_option);
			select_one_ch('[name="city_type[]"]');
		});
		// ################################################### 地區 END ##############################################################


		// ################################################### 使用功能 ##############################################################
		//-- 使用功能 (只能選擇一次) --
		$('#event_box').on('click', '[name="event_type[]"]', function () {
			select_one_ch('[name="event_type[]"]');
		});

		let event_type_arr=['食醫住行','預約賞屋','撥打手機','google 導航','加LINE或Line分享','fb分享','連結分享', 'QR code 分享'];
		$('#add_event_btn').click(function (e) { 

			let event_type_txt='';
			$.each(event_type_arr, function (index, valueOfElement) { 
				event_type_txt+=`<option  value="${this}">${this}</option>`;
			});
			let event_txt=`<div class="alert alert-info  d-inline-block">
							<a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
							<div class="d-inline-block">
							  <select name="event_type[]" class="form-control">
								${event_type_txt}
							  </select>
							</div>
							<div class="d-inline-block">
								<input type="text" class="form-control" name="one_event[]" value="">
							</div>
                           </div>`;
			
			$('#event_box').append(event_txt);

			select_one_ch('[name="event_type[]"]');
		});

		// ################################################### 使用功能 END ##############################################################


		// ################################################### 流量來源 ##############################################################
		//-- 流量來源 --
		$('#add_src_btn').click(function (e) { 

			let event_txt=`<div class="alert alert-info  d-inline-block">
							<a href="javascript:;" class="delete_item badge badge-danger" >Ｘ</a>
							<div class="d-inline-block">
							    <input type="text" class="form-control" name="src_type[]" value="">
							</div>
							<div class="d-inline-block">
								<input type="text" class="form-control" name="one_src[]" value="">
							</div>
                           </div>`;
			
			$('#src_box').append(event_txt);

			//-- 新增後需重新帶入 --
			$('[name="src_type[]"]').autocomplete({
			source: function (request, response) {
				$.ajax({
					type: "POST",
					url: "manager_ajax.php",
					data: {
						type:'src_type',
						case_id: $('#case_id').val(),
						tk:$('[name="tk"]').val(),
						term:request.term
					},
					dataType: "json",
					success: function (data) {
						response( data );
					}
				});
			},
			select: function( event, ui ) {
				//console.log( ui );
			},
			minLength:0
			});
		});

		$('[name="src_type[]"]').autocomplete({
		source: function (request, response) {
			$.ajax({
				type: "POST",
				url: "manager_ajax.php",
				data: {
					type:'src_type',
					case_id: $('#case_id').val(),
					tk:$('[name="tk"]').val(),
					term:request.term
				},
				dataType: "json",
				success: function (data) {
					response( data );
				}
			});
		},
		select: function( event, ui ) {
			//console.log( ui );
		},
		 minLength:0
		});
		

		$('#src_box').on('click', '[name="src_type[]"]', function () {
			$(this).autocomplete( "search", "" );
		});

		// ################################################### 流量來源 END ##############################################################


		


		//-- 刪除選項 --
		$('#area_box').on('click', '.delete_item', function () {
		   $(this).parent().remove();
		});
		$('#event_box').on('click', '.delete_item', function () {
		   $(this).parent().remove();
		});
		$('#src_box').on('click', '.delete_item', function () {
		   $(this).parent().remove();
		});



          $("#submit_btn").click(function(event) {

			//-- 使用的功能 重複判斷 --
			let event_type_arr=[];
			$.each($('[name="event_type[]"]'), function (index, valueOfElement) {
				event_type_arr.push($(this).val());
			});
			var event_repeat = event_type_arr.filter(function(element, index, arr){
				return arr.indexOf(element) !== index;
			});

			//-- 流量來源 重複判斷 --
			let src_type_arr=[];
			$.each($('[name="src_type[]"]'), function (index, valueOfElement) {
				src_type_arr.push($(this).val());
			});
			var src_repeat = src_type_arr.filter(function(element, index, arr){
				return arr.indexOf(element) !== index;
			});

			if(event_repeat.length>0){
			  alert('使用的功能，請勿重複');
			}
			else if(src_repeat.length>0){
			  alert('流量來源，請勿重複');
			}
			else{
			  $('#put_form').submit();
			}

          });
    
      });


	  //-- 只能選擇一次 --
	  function select_one_ch (DOM) {
		  $(`${DOM} option`).removeClass('d-none');
			$.each($(DOM), function (index, valueOfElement) { 
				$(`${DOM} [value="${$(this).val()}"]`).addClass('d-none');
		    });
	  }
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

