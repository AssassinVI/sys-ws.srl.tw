<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.md-skin .navbar-static-side, .border-bottom, body.fixed-sidebar .navbar-static-side{display: none;}
	#page-wrapper{ margin:0px;  }

	.ibox-tools a{ color: #fff; }

	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
		font-size: 15px;}
    
    .loading{ position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); display: none; justify-content: center; align-items: center; z-index: 10;}
	.loading .box{ display: inline-block;}


	 #save_txtBtn.active, #dowload_qrcode.active{ display:inline-block;}


	
	 
	 @media (max-width:420px){
	   #page-wrapper{padding:0;}
	   .ibox-content{padding:10px; }

	   .src_label{ display: inline-block; padding: 4px 10px;  margin: 6px;     font-size: 14px;  background-color: #c31d1d;}

	   #case_url_div thead,
	   #save_url_div thead
	   {display:none;}

	   #case_url_div tbody tr,
	   #save_url_div tbody tr
	   { display: block; padding: 10px 5px; border-bottom: 1px solid #e7eaec; position: relative;}

	   #case_url_div tbody tr td,
	   #save_url_div tbody tr td
	   {display: block; padding-top: 1px;  padding-bottom: 1px; border:0;}

	   #case_url_div tbody tr td::before,
	   #save_url_div tbody tr td::before
	   { content: attr(data-th); font-weight: bold; width: 3em; display: inline-block; color: #0e4e7b;}

	   #case_url_div tbody tr td a{ line-height: 1;  display: inline-block;  overflow: hidden;  text-overflow: ellipsis;  width: 150px;}
	   #save_url_div tbody tr td button{padding: 3px 10px;   position: absolute;  bottom: 0px;  right: 5px;}


	   /*------------- QR code ---------------*/
	   .save_qrcode_div{ display: flex; align-items: center; padding: 20px 0; border-bottom: 1px solid #ccc;}
	   .save_qrcode_div:nth-child(odd) {background-color: #f3f3f4;}
       .qr_one_img{ display: inline-block;  margin: 0 8px;}
       .qr_one_img img{ width: 72px; padding: 0 5px;}
       .qr_one_d{ display: inline-block;}
       .qr_one_d p{margin-bottom: 3px;}
       .qr_one_d p:nth-child(1){font-weight: 600;}
       .qr_one_d button{margin: 0; padding: 1px 10px; }

	 } 

</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
  $row_case=pdo_select("SELECT aTitle FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_GET['Tb_index']]);

  $Tb_id=substr($_GET['Tb_index'], 4);
?>

<div class="wrapper wrapper-content animated fadeInRight">


<div id="QRcode_in">
<div class="col-lg-12" >
		<h2 class="text-primary"><?php echo $row_case['aTitle'];?><br>新增 QR Code</h2>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">

               <!-- 建案網址 -->
			   <input type="hidden" id="case_url" value="https://ws.srl.tw/cs/<?php echo $Tb_id;?>/">
			 	
			 	<div class="ibox-tools">

			 		 <!-- <form style="display: inline-block;" action="qr_code_down.php" method="POST">
			 		 	<button id="dowload_qrcode" type="submit" class="btn btn-primary">下載QR Code</button>
			 		    <input id="qr_url" type="hidden" name="qr_url">
			 		 </form> -->
			 	</div>
			 </div>
			<div class="ibox-content">
				<!-- =================== Loading ====================== -->
					  <div class="loading">
						<div class="box">
							產生中...
							 <div class="sk-spinner sk-spinner-wave">
                                    <div class="sk-rect1"></div>
                                    <div class="sk-rect2"></div>
                                    <div class="sk-rect3"></div>
                                    <div class="sk-rect4"></div>
                                    <div class="sk-rect5"></div>
                                </div>
						</div>
					  </div>

				<div class="row">
					<div class="col-sm-12" style="font-size: 16px;">
					 <p>Google 分析-定義媒體名稱</p>
					 <label class="col-sm-2 control-label">媒體名稱：</label><div class="col-sm-10"><input class="form-control qr_txt" type="text" name="source" value="" placeholder="ex : 報紙、google聯播網..."></div>
					 <label style="display:none;" class="col-sm-2 control-label">廣告媒體:</label><div style="display:none;" class="col-sm-10"><input class=" form-control qr_txt" type="text" name="media" value="網址" placeholder="ex : QR code、網址..."></div>
					 <label style="display:none;" class="col-sm-2 control-label">活動:</label><div style="display:none;" class="col-sm-9"><input class=" form-control qr_txt" type="text" name="active" value="一般"></div>
					  
					</div>
					<div class="col-sm-12 text-center" style="margin-top: 15px;">
						<a href="#" id="save_txtBtn" class="btn btn-success">確定</a>
				    </div>
				    <div class="col-sm-6" style="text-align: center; display:none;">
					<p>QR Code網址: <a href="" target="_block"></a></p> 
					  <img id="QR_code" src="">

					  
				    </div>
				</div>
				
			</div>
		</div>
		
	</div>
</div>
</div>



<div id="qrcode_div" class="col-lg-12">

		<h2 class="text-primary">已儲存QR code</h2>
		<p>顯示目前專案所儲存之QR code</p>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			 <div class="ibox-title">
			 	
			 	<div class="ibox-tools">
					<!-- <a target="_blank" href="../../TCPDF/examples/QRcode_PDF.php?case_id=<?php //echo $_GET['Tb_index'];?>&case_name=<?php //echo $row_case['aTitle'];?>" class="btn btn-primary">下載PDF檔</a> -->
			 	</div>
			 </div>
			<div class="ibox-content">
				<div id="save_url_div" class="table-responsive">


					
				</div>
				<input type="hidden" id="tk" value="<?php echo $_SESSION['token'];?>">
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
        
        //---------------- 專案網址 --------------
		var case_url=$('#case_url').val();
        //---------------- 短網址 --------------
        //get_shortURL(case_url,'#short_url');
        get_shortURL_new(case_url, '<?php echo $row_case['aTitle'];?>', '#short_url');
        //---------------- QR code --------------
        get_qrcode_new(case_url+"?utm_source=報紙&utm_medium=QR code&utm_campaign=一般", '<?php echo $row_case['aTitle'];?>', '#QR_code','#qr_url');
        //------ 更新已儲存QRcode ------
        save_QRcode();

	
	//----------------------------- 新增QR CODE ---------------------------------
	$('#open_qrcode').click(function (e) { 
	  $('#QRcode_in').slideToggle();
		
	});



	 //---------------------- 更新QR code 網址 ------------------------
	 $('#sel_txtBtn').click(function(event) {
	 	event.preventDefault();
		$('#save_txtBtn').addClass('active');
		$(this).removeClass('active');
	 	var source=$('[name="source"]').val();
	 	var media=$('[name="media"]').val();
	 	var active=$('[name="active"]').val();
	 	var new_url=case_url+"?utm_source="+source+"&utm_medium="+media+"&utm_campaign="+active;
	 	
	 	get_qrcode_new(new_url, '<?php echo $row_case['aTitle'];?>','#QR_code','#qr_url');
	 });
     
     //---------------------- 儲存QR code 網址 ------------------------
	 $('#save_txtBtn').click(function(event) {

	   if($('[name="source"]').val()!=''){


	    $('.loading').css('display', 'flex');

		var _this=$(this);
	 	event.preventDefault();

        // $('#save_txtBtn').addClass('active');
		// $(this).removeClass('active');
	 	var source=$('[name="source"]').val();
	 	var media=$('[name="media"]').val();
		var active=$('[name="active"]').val();

		var new_url=case_url+"?utm_source="+source+"&utm_medium="+media+"&utm_campaign="+active;
		var s_new_url='';
		//-- 短網址 --
		 $.ajax({
		  url: 'shortUrl_ajax.php',
		  async:false,
          type: 'POST',
          data: {
          	type:'Url',
          	longUrl: new_url,
            aTitle: '<?php echo $row_case['aTitle'];?>-網址',
			tk:$('#tk').val()
          },
          success:function (data) {
             s_new_url=data;
          }
         });
	 	
	 	get_qrcode_new(case_url+"?utm_source="+source+"&utm_medium=QR_code&utm_campaign="+active, '<?php echo $row_case['aTitle'];?>','#QR_code','#qr_url');
		 
        setTimeout(() => {
			$.ajax({
	 		url: 'catch_web_ajax.php',
	 		type: 'POST',
	 		data: {
	 			type:'insert',
	 			case_id: '<?php echo $_GET['Tb_index'];?>',
                QRcode_pic : $('#QR_code').attr('src'),
                QRcode_url  : s_new_url, //-- 改一般網址 --
                source : $('[name="source"]').val(),
                media : $('[name="media"]').val(),
                event_name  : $('[name="active"]').val(),
				tk:$('#tk').val()
	 		},
	 		success:function (data) {
	 			if (data=='1') {
	 			  //------ 更新已儲存QRcode ------
				  //admin.php$('#dowload_qrcode').addClass('active');
	    	      //_this.removeClass('active');
                  save_QRcode();
                  alert('已儲存');
	 			}else{
                  alert('儲存失敗或已有相同QR code');
	 			}
				
				$('.loading').css('display', 'none');

				//-- 下滑 --
				//指定視窗物件
                var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
				$body.animate({
                  scrollTop: $('#qrcode_div').offset().top - 40
                }, 1000);
	 		}
	 	});
		}, 1000);
	   
	}
	else{
	  alert('請輸入媒體名稱!');
	}
	 	
	 });






	 /*-- 判斷輸入 --*/
	 $('.qr_txt').change(function (e) { 
		$('#dowload_qrcode').removeClass('active');
		//$('#sel_txtBtn').addClass('active');
	 });






	 /*-- 複製btn --*/
     $('#save_url_div').on('click', '.copy_btn', function () {
	   var temp = $('<input>'); // 建立input物件
       $('body').append(temp); // 將input物件增加到body
       var url = $(this).parent().find('a').html(); // 取得要複製的連結
       temp.val(url).select(); // 將連結加到input物件value
       document.execCommand('copy'); // 複製
       temp.remove(); // 移除input物件
	   alert('複製成功\n'+url);
	 });





     /*-- 檢視btn --*/
     $('#save_url_div').on('click', '.view_btn', function () {
       window.open('qr_code_show.php?qr_id='+$(this).attr('qr_id'), 'QR code 網址', config='height=500,width=500');
	 });



	});



   /* =================================== 產生短網址(自製) ============================================= */
      function get_shortURL_new(get_url, aTitle, show_id) {

         $.ajax({
          url: 'shortUrl_ajax.php',
          type: 'POST',
          data: {
          	type:'Url',
          	longUrl:get_url,
            aTitle: aTitle,
			tk:$('#tk').val()
          },
          success:function (data) {

               $(show_id).html(data);
               $(show_id).attr('href', data);
            
          }
         });
      }



/* =================================== 產生QR code (自製) ============================================= */
      function get_qrcode_new(get_url, aTitle, show_id,put_id) {
 
         $.ajax({
          url: 'shortUrl_ajax.php',
          type: 'POST',
          data: {
          	type:'QR_code',
          	longUrl:get_url,
            aTitle: aTitle,
			tk:$('#tk').val()
          },
          success:function (data) {
                       //產生QR_code
              var qr_url='https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='+data+'&chld=L|1&choe=UTF-8';
			  //var qr_url='https://api.qrserver.com/v1/create-qr-code/?data='+data;
               $(show_id).attr('src', qr_url);
               $(show_id).prev().find('a').attr('href', data);
               $(show_id).prev().find('a').html(data);
              $(put_id).attr('value', qr_url);

            
          }
         });
      }


/* ======================================== 更新已儲存QR code ==================================================== */
      function save_QRcode() {
      	$.ajax({
	 		url: 'catch_web_ajax.php',
	 		type: 'POST',
	 		dataType:'json',
	 		data: {
	 			type:'select',
	 			case_id: '<?php echo $_GET['Tb_index'];?>',
				tk:$('#tk').val()
	 		},
	 		success:function (data) {

				 console.log(data);
	 			
				 $('#save_url_div').html('');
				var data_num=data.length;
	 			var x=data_num;
	 			var txt='';
	 			$.each(data, function() {


						txt+='<div class="save_qrcode_div">'+
					          '<div class="qr_one_img">'+
					           '<span>'+x+'.</span>'+
					           '<a href="javascript:window.open(\''+this['QRcode_pic']+'\',\'QR\', config=\'height=500,width=500\');"><img src="'+this['QRcode_pic']+'" alt=""></a>'+
					          '</div>'+
					          '<div class="qr_one_d">'+
					            '<p>媒體：'+this['source']+'</p>'+
					        	'<p>網址：<a target="_blank" href="'+this['QRcode_url']+'">'+this['QRcode_url']+'</a></p>'+
					        	'<button class="btn btn-success view_btn" qr_id="'+this['qr_id']+'">檢視</button>'+
					          '</div>'+
					        '</div>';
				  x--;
	 			});

	 			$('#save_url_div').append(txt);
	 		}
	 	});
      }


　　　//-- 複製 --
	  function CopyTextToClipboard(id) {
         var TextRange = document.createRange();
         TextRange.selectNode(document.getElementById(id));
         sel = window.getSelection();
             sel.removeAllRanges();
             sel.addRange(TextRange);
         document.execCommand("copy");
         alert("複製完成！") //此行可加可不加
	   }
	   
 

</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
