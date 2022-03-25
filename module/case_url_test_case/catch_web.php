<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #626262; }

	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
		font-size: 15px;}
    
    .loading{ position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background-color: #fff; display: none;}
	.sk-spinner-cube-grid.sk-spinner{ margin: auto; top: -45px; bottom: 0; left: 0; right: 0; position: absolute; }
	.sk-spinner-cube-grid .sk-cube{ background-color: #2d2d2d; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
  
  $test_case_db='srltw_test_case'; //-- 測試用修改資料庫 --

  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']], $test_case_db);

  $Tb_id=substr($_GET['Tb_index'], 4);
?>

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?>-網址列表 <span class="text-danger">(此為測試修改區)</span></h2>
		<p>提供專案網址、短網址、QR Code、Google分析來源網址</p>

	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 		      
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>類型</th>
                                <th>網址</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td>1</td>
							<td>專案網址</td>
							<td><a id="case_url" target="_blank" href="http://ws.srl.tw/cs/<?php echo $Tb_id;?>/?test_case=test_case">http://ws.srl.tw/cs/<?php echo $Tb_id;?>/?test_case=test_case</a></td>
						</tr>
						<tr>
							<td>2</td>
							<td>短網址</td>
							<td><a id="short_url" target="_blank" href="#"></a></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>






<div class="col-lg-12">
		<h2 class="text-primary">QR Code</h2>
		<p>QR Code 產生、下載、總表</p>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 		 <!-- <a href="#" id="sel_txtBtn" class="btn btn-default">更新QR Code與網址</a>
			 		 <a href="#" id="save_txtBtn" class="btn btn-default">儲存QR Code與網址</a> -->

			 		 <form style="display: inline-block;" action="qr_code_down.php" method="POST">
			 		 	<button type="submit" class="btn btn-primary">下載QR Code</button>
			 		    <input id="qr_url" type="hidden" name="qr_url">
			 		 </form>
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="row">
					<!-- <div class="col-sm-6">
					 <p>Google 分析-定義廣告來源/媒介</p>
					 <label class="col-sm-3 control-label">來源:</label><div class="col-sm-9"><input class="form-control" type="text" name="source" value="報紙"></div>
					 <label class="col-sm-3 control-label">媒介:</label><div class="col-sm-9"><input class=" form-control" type="text" name="media" value="QR code"></div>
					 <label class="col-sm-3 control-label">活動:</label><div class="col-sm-9"><input class=" form-control" type="text" name="active" value="一般"></div>
				    </div> -->
				    <div class="col-sm-6" style="text-align: center;">
					<p>QR Code網址: <a href="" target="_block"></a></p> 
					  <img id="QR_code" src="">

					  <!-- =================== Loading ====================== -->
					  <div class="loading">
					  <div class="sk-spinner sk-spinner-cube-grid">
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					      <div class="sk-cube"></div>
					  </div>
					  </div>
				    </div>
				</div>
				
			</div>
		</div>
		
	</div>
</div>



<!-- <div class="col-lg-12">
		<h2 class="text-primary">已儲存QR code</h2>
		<p>顯示目前專案所儲存之QR code</p>

	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
			 	    <form style="display: inline-block;" action="qr_code_excel.php" method="POST">
			 	    	<button type="submit" class="btn btn-primary">下載Word檔</button>
			 	       <input type="hidden" name="case_id" value="<?php //echo $_GET['Tb_index'];?>">
			 	       <input type="hidden" name="case_name" value="<?php //echo $row_case['aTitle'];?>">
			 	    </form>  
			 	</div>
			 </div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>圖片</th>
                                <th>網址</th>
                                <th>來源</th>
                                <th>媒體</th>
                                <th>活動</th>
							</tr>
						</thead>
						<tbody id="save_QRcode">

						
						
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div> -->
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
        
        //---------------- 專案網址 --------------
		var case_url=$('#case_url').attr('href');
        //---------------- 短網址 --------------
        //get_shortURL(case_url,'#short_url');
        get_shortURL_new(case_url, '<?php echo $row_case['aTitle'];?>', '#short_url');
        //---------------- QR code --------------
        //get_qrcode(case_url,'#QR_code','#qr_url');
        get_qrcode_new(case_url, '<?php echo $row_case['aTitle'];?>', '#QR_code','#qr_url');
        //------ 更新已儲存QRcode ------
        save_QRcode();


		 $(".iframe_box").fancybox({
		 	'padding'               :'0',
            'type'                  : 'iframe'
		 });

	 //---------------------- 更新QR code 網址 ------------------------
	 $('#sel_txtBtn').click(function(event) {
	 	event.preventDefault();
	 	var source=$('[name="source"]').val();
	 	var media=$('[name="media"]').val();
	 	var active=$('[name="active"]').val();
	 	var new_url=case_url+"?utm_source="+source+"&utm_medium="+media+"&utm_campaign="+active;
	 	//get_shortURL(new_url,'#short_url');
	 	get_qrcode_new(new_url, '<?php echo $row_case['aTitle'];?>','#QR_code','#qr_url');
	 });
     
     //---------------------- 儲存QR code 網址 ------------------------
	 $('#save_txtBtn').click(function(event) {
	 	event.preventDefault();
	 	$.ajax({
	 		url: 'catch_web_ajax.php',
	 		type: 'POST',
	 		data: {
	 			type:'insert',
	 			case_id: '<?php echo $_GET['Tb_index'];?>',
                QRcode_pic : $('#qr_url').val(),
                QRcode_url  : $('#QR_code').prev().find('a').attr('href'),
                source : $('[name="source"]').val(),
                media : $('[name="media"]').val(),
                event_name  : $('[name="active"]').val()
	 		},
	 		success:function (data) {
	 			if (data=='1') {
	 			  //------ 更新已儲存QRcode ------
                  save_QRcode();
                  alert('已儲存');
	 			}else{
                  alert('儲存失敗或已有相同QR code');
	 			}
	 		}
	 	});
	 	
	 });


	});


	/* =================================== 產生短網址 ============================================= */
      function get_shortURL(get_url,show_id) {

         $.ajax({
          url: 'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyBmcZ9YTd68k4QYur5nowITqcI_kGZO5Ks',
          type: 'POST',
          dataType: 'json',
          data: JSON.stringify({longUrl:get_url}),
          contentType : "application/json",
          success:function (result,status,xhr) {
            if (status=="success") {

               $(show_id).html(result.id);
               $(show_id).attr('href', result.id);
            }
          }
         });
      }

   /* =================================== 產生短網址(自製) ============================================= */
      function get_shortURL_new(get_url, aTitle, show_id) {

         $.ajax({
          url: 'shortUrl_ajax.php',
          type: 'POST',
          data: {
          	type:'Url',
          	longUrl:get_url,
            aTitle: aTitle
          },
          success:function (data) {

               $(show_id).html(data);
               $(show_id).attr('href', data);
            
          }
         });
      }

/* =================================== 產生QR code ============================================= */
      function get_qrcode(get_url,show_id,put_id) {

         $.ajax({
          url: 'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyBmcZ9YTd68k4QYur5nowITqcI_kGZO5Ks',
          type: 'POST',
          dataType: 'json',
          data: JSON.stringify({longUrl:get_url}),
          contentType : "application/json",
          success:function (result,status,xhr) {
            if (status=="success") {
                       //產生QR_code
              var qr_url='http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl='+result.id+'&chld=H|0';
               $(show_id).attr('src', qr_url);
               $(show_id).prev().find('a').attr('href', result.id);
               $(show_id).prev().find('a').html(result.id);
              $(put_id).attr('value', qr_url);

            }
          },
          beforeSend:function () {
          	$('.loading').css('display', 'block');
          },
          complete:function () {
          	$('.loading').css('display', 'none');
          }
         });
      }


/* =================================== 產生QR code (自製) ============================================= */
      function get_qrcode_new(get_url, aTitle, show_id,put_id) {

      	         //產生QR_code
      	var qr_url='http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl='+get_url+'&chld=H|0';
      	 $(show_id).attr('src', qr_url);
      	 $(put_id).attr('value', qr_url);

         // $.ajax({
         //  url: 'shortUrl_ajax.php',
         //  type: 'POST',
         //  data: {
         //  	type:'QR_code',
         //  	longUrl:get_url,
         //    aTitle: aTitle
         //  },
         //  success:function (data) {
         //               //產生QR_code
         //      var qr_url='http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl='+data+'&chld=H|0';
         //       $(show_id).attr('src', qr_url);
         //       $(show_id).prev().find('a').attr('href', data);
         //       $(show_id).prev().find('a').html(data);
         //      $(put_id).attr('value', qr_url);

            
         //  },
         //  beforeSend:function () {
         //  	$('.loading').css('display', 'block');
         //  },
         //  complete:function () {
         //  	$('.loading').css('display', 'none');
         //  }
         // });
      }


/* ======================================== 更新已儲存QR code ==================================================== */
      function save_QRcode() {
      	$.ajax({
	 		url: 'catch_web_ajax.php',
	 		type: 'POST',
	 		dataType:'json',
	 		data: {
	 			type:'select',
	 			case_id: '<?php echo $_GET['Tb_index'];?>'
	 		},
	 		success:function (data) {
	 			
	 			$('#save_QRcode').html('');
	 			var x=1;
	 			var txt='';
	 			$.each(data, function() {

	 				    txt+='<tr>';
						txt+=' <td>'+x+'</td>';
						txt+=' <td><img src="'+this['QRcode_pic']+'"></td>';
						txt+=' <td><a href="'+this['QRcode_url']+'">'+this['QRcode_url']+'</a></td>';
						txt+=' <td>'+this['source']+'</td>';
						txt+=' <td>'+this['media']+'</td>';
						txt+=' <td>'+this['event_name']+'</td>';
						txt+='</tr>';
				  x++;
	 			});

	 			$('#save_QRcode').append(txt);
	 		}
	 	});
      }
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
